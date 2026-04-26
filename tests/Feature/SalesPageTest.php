<?php

namespace Tests\Feature;

use App\Jobs\GenerateSalesPageJob;
use App\Jobs\RegenerateSectionJob;
use App\Models\SalesPage;
use App\Models\UsageLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SalesPageTest extends TestCase
{
    use RefreshDatabase;

    private function verifiedUser(): User
    {
        return User::factory()->create(['email_verified_at' => now()]);
    }

    public function test_store_creates_pending_page_and_dispatches_job(): void
    {
        Queue::fake();
        $user = $this->verifiedUser();

        $response = $this->actingAs($user)->post('/sales-pages', [
            'product_name'    => 'Acme Widget',
            'description'     => 'A widget that does things very well for you.',
            'features'        => "Fast\nReliable\nAffordable",
            'target_audience' => 'Small business owners',
            'price'           => 49,
            'usp'             => 'Half the price of competitors',
        ]);

        $page = SalesPage::firstOrFail();

        $response->assertRedirect(route('sales-pages.show', $page));
        $this->assertSame(SalesPage::STATUS_PENDING, $page->status);
        $this->assertSame($user->id, $page->user_id);
        Queue::assertPushed(GenerateSalesPageJob::class, fn ($job) => $job->page->is($page));
    }

    public function test_user_cannot_view_anothers_page(): void
    {
        $owner = $this->verifiedUser();
        $stranger = $this->verifiedUser();
        $page = SalesPage::factory()->for($owner)->completed()->create();

        $this->actingAs($stranger)
            ->get(route('sales-pages.show', $page))
            ->assertForbidden();
    }

    public function test_user_cannot_delete_anothers_page(): void
    {
        $owner = $this->verifiedUser();
        $stranger = $this->verifiedUser();
        $page = SalesPage::factory()->for($owner)->completed()->create();

        $this->actingAs($stranger)
            ->delete(route('sales-pages.destroy', $page))
            ->assertForbidden();

        $this->assertDatabaseHas('sales_pages', ['id' => $page->id]);
    }

    public function test_status_endpoint_returns_json(): void
    {
        $user = $this->verifiedUser();
        $page = SalesPage::factory()->for($user)->create([
            'status' => SalesPage::STATUS_GENERATING,
        ]);

        $this->actingAs($user)
            ->getJson(route('sales-pages.status', $page))
            ->assertOk()
            ->assertJson([
                'status'       => 'generating',
                'is_generated' => false,
            ]);
    }

    public function test_quota_blocks_store_when_limit_reached(): void
    {
        config()->set('services.ai_quota.daily_per_user', 2);
        Queue::fake();
        $user = $this->verifiedUser();

        UsageLog::factory()->count(2)->create([
            'user_id' => $user->id,
            'action'  => UsageLog::ACTION_GENERATE,
            'success' => true,
        ]);

        $this->actingAs($user)->post('/sales-pages', [
            'product_name'    => 'Acme',
            'description'     => 'Desc',
            'features'        => 'a',
            'target_audience' => 'b',
        ])->assertSessionHas('error');

        $this->assertSame(0, SalesPage::count());
        Queue::assertNothingPushed();
    }

    public function test_regenerate_section_dispatches_job(): void
    {
        Queue::fake();
        $user = $this->verifiedUser();
        $page = SalesPage::factory()->for($user)->completed()->create();

        $this->actingAs($user)
            ->post(route('sales-pages.regenerate-section', $page), ['section' => 'headline'])
            ->assertRedirect();

        Queue::assertPushed(
            RegenerateSectionJob::class,
            fn ($job) => $job->page->is($page) && $job->section === 'headline'
        );
    }

    public function test_regenerate_blocked_while_processing(): void
    {
        Queue::fake();
        $user = $this->verifiedUser();
        $page = SalesPage::factory()->for($user)->create([
            'status' => SalesPage::STATUS_GENERATING,
        ]);

        $this->actingAs($user)
            ->post(route('sales-pages.regenerate', $page))
            ->assertSessionHas('error');

        Queue::assertNothingPushed();
    }

    public function test_store_persists_chosen_template(): void
    {
        Queue::fake();
        $user = $this->verifiedUser();

        $this->actingAs($user)->post('/sales-pages', [
            'product_name'    => 'Acme',
            'description'     => 'Desc',
            'features'        => 'a',
            'target_audience' => 'b',
            'template'        => 'bold',
        ]);

        $this->assertSame('bold', SalesPage::firstOrFail()->template);
    }

    public function test_store_rejects_invalid_template(): void
    {
        Queue::fake();
        $user = $this->verifiedUser();

        $this->actingAs($user)->post('/sales-pages', [
            'product_name'    => 'Acme',
            'description'     => 'Desc',
            'features'        => 'a',
            'target_audience' => 'b',
            'template'        => 'gothic',
        ])->assertSessionHasErrors('template');

        $this->assertSame(0, SalesPage::count());
    }

    public function test_owner_can_switch_template(): void
    {
        $user = $this->verifiedUser();
        $page = SalesPage::factory()->for($user)->completed()->create(['template' => 'aurora']);

        $this->actingAs($user)
            ->post(route('sales-pages.set-template', $page), ['template' => 'minimal'])
            ->assertRedirect();

        $this->assertSame('minimal', $page->fresh()->template);
    }

    public function test_stranger_cannot_switch_template(): void
    {
        $owner = $this->verifiedUser();
        $stranger = $this->verifiedUser();
        $page = SalesPage::factory()->for($owner)->completed()->create(['template' => 'aurora']);

        $this->actingAs($stranger)
            ->post(route('sales-pages.set-template', $page), ['template' => 'bold'])
            ->assertForbidden();

        $this->assertSame('aurora', $page->fresh()->template);
    }
}

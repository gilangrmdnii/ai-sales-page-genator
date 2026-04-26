<?php

namespace Database\Factories;

use App\Models\SalesPage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SalesPage>
 */
class SalesPageFactory extends Factory
{
    protected $model = SalesPage::class;

    public function definition(): array
    {
        return [
            'user_id'         => User::factory(),
            'product_name'    => $this->faker->words(2, true),
            'description'     => $this->faker->sentence(12),
            'features'        => [$this->faker->words(3, true), $this->faker->words(3, true)],
            'target_audience' => $this->faker->words(3, true),
            'price'           => 49.00,
            'usp'             => $this->faker->sentence(),
            'generated_content' => null,
            'status'          => SalesPage::STATUS_PENDING,
        ];
    }

    public function completed(): self
    {
        return $this->state(fn () => [
            'status' => SalesPage::STATUS_COMPLETED,
            'generated_content' => [
                'headline'     => 'Headline',
                'subheadline'  => 'Sub',
                'description'  => 'Body',
                'benefits'     => ['a', 'b'],
                'features'     => ['x', 'y'],
                'social_proof' => 'Trusted',
                'pricing'      => '$49',
                'cta'          => 'Buy now',
            ],
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\SalesPage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $demo = User::updateOrCreate(
            ['email' => 'demo@demo.com'],
            [
                'name'              => 'Demo User',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        );

        SalesPage::where('user_id', $demo->id)->delete();

        $this->createAuroraExample($demo);
        $this->createMinimalExample($demo);
        $this->createBoldExample($demo);
    }

    private function createAuroraExample(User $user): void
    {
        SalesPage::create([
            'user_id'         => $user->id,
            'product_name'    => 'Aurora Notes',
            'description'     => 'A delightful, end-to-end encrypted note-taking app that syncs in real-time across every device you own. Built for thinkers who value speed and privacy.',
            'features'        => [
                'Realtime cross-device sync',
                'End-to-end encryption by default',
                'Offline-first with conflict-free merge',
                'Markdown + slash commands',
                'Vault sharing with granular permissions',
                'Native apps for macOS, Windows, iOS, Android',
            ],
            'target_audience' => 'Indie founders and knowledge workers',
            'price'           => 9.00,
            'usp'             => 'The only encrypted notes app that feels as fast as plain text.',
            'template'        => 'aurora',
            'status'          => SalesPage::STATUS_COMPLETED,
            'generated_content' => [
                'headline'     => 'Notes that move at the speed of thought.',
                'subheadline'  => 'Encrypted, synced, and unbelievably fast — Aurora is the note-taking app you stop comparing to others.',
                'description'  => "Most note apps make you choose: speed, privacy, or polish. Aurora refuses the trade-off.\n\nEvery keystroke is encrypted before it leaves your device, then synced to every screen you own in under 80 milliseconds. Markdown, slash commands, and shared vaults all work offline — even on the subway.\n\nIf you've ever lost a thought because your tools were too slow, Aurora is your fix.",
                'benefits'     => [
                    'Capture ideas without latency — sub-100ms typing on every device.',
                    'Sleep easy knowing every byte is end-to-end encrypted.',
                    "Stay in flow offline; conflicts merge themselves when you're back.",
                    'Share workspaces with teammates without losing privacy.',
                    'Replace three apps with one — notes, tasks, and docs in one place.',
                ],
                'features'     => [
                    'Realtime sync that beats Notion by 10x',
                    'AES-256 end-to-end encryption — zero-knowledge by design',
                    'Conflict-free offline editing with automatic merge',
                    'Full Markdown plus slash commands and inline AI',
                    'Granular vault sharing — read, comment, or co-edit',
                    'Native apps on every platform you use',
                ],
                'social_proof' => 'Trusted by 12,000+ founders, researchers, and writers shipping their best work.',
                'pricing'      => 'Just $9/month — less than your last coffee, faster than your last note app. Cancel anytime.',
                'cta'          => 'Start writing free',
            ],
        ]);
    }

    private function createMinimalExample(User $user): void
    {
        SalesPage::create([
            'user_id'         => $user->id,
            'product_name'    => 'The Quiet Desk',
            'description'     => 'A weekly newsletter and printed journal for solo creators who want to focus deeply without the noise of social media.',
            'features'        => [
                'Weekly long-form essay',
                'Quarterly printed journal',
                'Subscriber-only podcast',
                'Annual reader meetup',
                'Private member library',
            ],
            'target_audience' => 'Independent writers, designers, and makers',
            'price'           => 6.00,
            'usp'             => 'No ads. No algorithms. Just thoughtful writing delivered to your inbox.',
            'template'        => 'minimal',
            'status'          => SalesPage::STATUS_COMPLETED,
            'generated_content' => [
                'headline'     => 'Quiet ideas, weekly.',
                'subheadline'  => 'A long-form letter for makers who are tired of the feed and ready to think again.',
                'description'  => "Every Sunday, one essay lands in your inbox. No tracker pixels, no engagement bait — just six minutes of careful writing about creative work, focus, and the craft of building things alone.\n\nFour times a year, a printed journal arrives at your door. Real paper, real ink, real weight in your hands.\n\nIf the internet has felt loud lately, this is the room you have been looking for.",
                'benefits'     => [
                    'Reclaim your Sunday morning with one thoughtful essay.',
                    'Hold quarterly journals in your hands — no screen required.',
                    'Hear the back-story on a private subscriber podcast.',
                    'Meet other readers in person at the annual gathering.',
                    'Browse a library curated by other quiet thinkers.',
                ],
                'features'     => [
                    'A 1,500-word essay every Sunday morning',
                    'Quarterly printed journal mailed worldwide',
                    'Subscriber-only podcast with extended interviews',
                    'In-person reader meetup once a year',
                    'Member library with reading lists and archives',
                ],
                'social_proof' => 'Read by 8,400 makers across 41 countries — including a few you would recognise.',
                'pricing'      => '$6 per month, billed annually. Cancel any time, keep every issue.',
                'cta'          => 'Subscribe now',
            ],
        ]);
    }

    private function createBoldExample(User $user): void
    {
        SalesPage::create([
            'user_id'         => $user->id,
            'product_name'    => 'Launchpad Bootcamp',
            'description'     => 'A 12-week intensive bootcamp that turns total beginners into hireable junior developers, with a job-offer guarantee.',
            'features'        => [
                '12 weeks live, in-person',
                '1:1 mentor pairing',
                'Build 4 portfolio projects',
                'Mock interviews weekly',
                'Hiring partner network',
                'Money-back if not hired in 6 months',
            ],
            'target_audience' => 'Career changers ready to commit',
            'price'           => 4900.00,
            'usp'             => 'No job in 6 months? Full refund. Period.',
            'template'        => 'bold',
            'status'          => SalesPage::STATUS_COMPLETED,
            'generated_content' => [
                'headline'     => 'Code in 12 weeks. Hired in 6 months.',
                'subheadline'  => "Or your money back — every cent. We are that confident, because we have done it 800+ times.",
                'description'  => "Tutorials do not get you hired. Bootcamps that ghost you after graduation do not either.\n\nLaunchpad is different. Twelve weeks of in-person grind, paired with a mentor who has been in the trenches, ending in a portfolio that recruiters actually open.\n\nThen we do not disappear. We keep mock-interviewing you, introducing you, pushing you — until you sign an offer. If you do not sign one in six months, we refund the entire tuition.",
                'benefits'     => [
                    'Skip 6 months of solo tutorial purgatory.',
                    'Build a portfolio that gets recruiter replies.',
                    'Practice interviews until they stop being scary.',
                    'Tap a hiring network of 60+ partner companies.',
                    'Risk zero — we eat the cost if you are not hired.',
                ],
                'features'     => [
                    '12 weeks of live, in-person instruction',
                    '1:1 weekly pairing with a working senior dev',
                    '4 portfolio projects shipped to production',
                    'Weekly mock interviews with real engineers',
                    'Direct intros to 60+ hiring partners',
                    '100% tuition refund if not hired in 6 months',
                ],
                'social_proof' => '94% job-placement rate. Average starting salary $78,000. 800+ alumni and counting.',
                'pricing'      => '$4,900 — refunded in full if you are not hired within 6 months of graduation. Financing and ISA options available.',
                'cta'          => 'Apply now',
            ],
        ]);
    }
}

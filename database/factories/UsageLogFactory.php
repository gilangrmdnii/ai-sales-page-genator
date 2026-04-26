<?php

namespace Database\Factories;

use App\Models\UsageLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UsageLog>
 */
class UsageLogFactory extends Factory
{
    protected $model = UsageLog::class;

    public function definition(): array
    {
        return [
            'user_id'           => User::factory(),
            'sales_page_id'     => null,
            'action'            => UsageLog::ACTION_GENERATE,
            'prompt_tokens'     => 100,
            'completion_tokens' => 200,
            'total_tokens'      => 300,
            'model'             => 'gpt-4o-mini',
            'success'           => true,
        ];
    }
}

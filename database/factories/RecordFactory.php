<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\DateFactory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Record>
 */
class RecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dates = [rand(1e9, 1e9 + 1e6), rand(1e9, 1e9 + 1e6)];
        $dateFactory = new DateFactory();
        $startedAt = $dateFactory->createFromTimestampUTC(min($dates[0], $dates[1]));
        $endedAt = $dateFactory->createFromTimestampUTC(max($dates[0], $dates[1]));
        return [
            'content' => Str::random(20),
            'started_at' => $startedAt,
            'ended_at' => null,
        ];
    }
}

<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Jobs\RunSecretSanta;
use Database\Factories\PersonFactory;
use Illuminate\Database\Seeder;

class PersonSeeder extends Seeder
{
    public function __construct(private PersonFactory $factory)
    {
    }

    public function run(): void
    {
        $this->factory->count(config('seeding.count'))->createQuietly();

        RunSecretSanta::dispatch();
    }
}

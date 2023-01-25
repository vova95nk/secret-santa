<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\SecretSantaRandomizeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RunSecretSanta implements ShouldQueue
{
    use Dispatchable;
    use Queueable;

    public function __construct()
    {
        $this->onConnection('sync');
        $this->onQueue('default');
    }

    public function handle(SecretSantaRandomizeService $service): void
    {
        $service->runGame();
    }
}

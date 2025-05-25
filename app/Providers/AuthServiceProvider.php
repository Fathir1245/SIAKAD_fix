<?php

namespace App\Providers;

use App\Models\Kelas;
use App\Policies\KelasPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Kelas::class => KelasPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
} 
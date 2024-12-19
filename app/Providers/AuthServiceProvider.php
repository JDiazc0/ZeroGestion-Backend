<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Client;
use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\RawMaterial;
use App\Models\RawMaterialInventory;
use App\Policies\ClientPolicy;
use App\Policies\InventoryProductPolicy;
use App\Policies\InventoryRawMaterialPolicy;
use App\Policies\ProductPolicy;
use App\Policies\RawMaterialPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        RawMaterial::class => RawMaterialPolicy::class,
        Product::class => ProductPolicy::class,
        Client::class => ClientPolicy::class,
        ProductInventory::class => InventoryProductPolicy::class,
        RawMaterialInventory::class => InventoryRawMaterialPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}

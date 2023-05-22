<?php

declare(strict_types=1);

namespace Dune\Database;

use Illuminate\Database\Capsule\Manager as EloquentManager;

class EloquentBooter
{
    /**
     * boot up the eloquent orm
     * configuration from config/database.php
     */
    public function boot(): void
    {
        $manager = new EloquentManager();
        $manager->addConnection(config('database'));
        $manager->setAsGlobal();
        $manager->bootEloquent();
    }
}

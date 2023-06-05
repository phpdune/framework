<?php

/*
 * This file is part of Dune Framework.
 *
 * (c) Abhishek B <phpdune@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        if(!env('DB_DRIVER')) {
           return;
        }
        $manager = new EloquentManager();
        $manager->addConnection(config('database'));
        $manager->setAsGlobal();
        $manager->bootEloquent();
    }
}

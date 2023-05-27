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

namespace Dune\Facades;

use Dune\Routing\Router;
use Dune\Session\Session;
use Dune\Cookie\Cookie;
use Dune\Csrf\Csrf;
use Exception;
use Dune\App;

abstract class Facade
{
    /**
     * The resolved instances of the facades.
     *
     * @var array
     */
    protected static array $resolvedInstances = [];
    /**
     * getAccessor abstract method to resolve the specific instance
     *
     * @return string;
     */
    abstract protected static function getAccessor(): string;
    /**
     * static call
     *
     * @param string $method
     * @param array<mixed>
     *
     * @return mixed
     */
    public static function __callStatic(string $method, array $arags): mixed
    {
        $instance = static::resolveFacadeInstance(static::getAccessor());
        return $instance->$method(...$arags);
    }
      /**
       * to resolve the facade instance
       *
       * @param string $name
       *
       * @return mixed
       */
    public static function resolveFacadeInstance(string $name): mixed
    {
        if(isset(static::$resolvedInstances[$name])) {
            return static::$resolvedInstances[$name];
        }
        static::$resolvedInstances[$name] = static::getResolvable($name);
        return static::$resolvedInstances[$name];
    }
       /**
       * return the specific instance by accessor
       *
       * @param string $name
       *
       * @throw Exception
       *
       * @return mixed
       */
    public static function getResolvable(string $name): mixed
    {
        $container = App::container();
        return match($name) {
            'route' => $container->get(Router::class),
            'session' => $container->get(Session::class),
            'csrf' => $container->get(Csrf::class),
            'cookie' => $container->get(Cookie::class),
            default => throw new Exception('cannot resolve this class')
        };
    }
}

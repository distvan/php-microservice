<?php

declare(strict_types=1);

namespace App\Infrastructure;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Router class
 *
 * @package App\Infrastructure
 */
class Router implements IRouter
{
    private array $routes = [];

    /**
     * Add route
     *
     * @param string $method    HTTP method name
     * @param string $path      path name
     * @param callable $handler callable function
     */
    public function add(string $method, string $path, callable $handler): void
    {
        $this->routes[] = new Route($method, $path, $handler);
    }

    /**
     * Match
     *
     * @param ServerRequestInterface $request
     */
    public function match(ServerRequestInterface $request): ?callable
    {
        foreach ($this->routes as $route) {
            if ($route->matches($request)) {
                return $route->handler;
            }
        }

        return null;
    }
}

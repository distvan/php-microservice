<?php

namespace App\Infrastructure;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface
 *
 * @package App\Infrastructure
 */
interface IRouter
{
    public function add(string $method, string $path, callable $handler): void;
    public function match(ServerRequestInterface $request): ?callable;
}

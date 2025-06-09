<?php

declare(strict_types=1);

namespace App\Infrastructure;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Route class
 *
 * @package App\Infrastructure
 */
class Route
{
    /**
     * Constructor
     *
     * @param string $method
     * @param string $path
     * @param callable $handler
     */
    public function __construct(
        public readonly string $method,
        public readonly string $path,
        public $handler
    ) {
    }

    /**
     * Matches
     *
     * @param ServerRequestInterface $request
     */
    public function matches(ServerRequestInterface $request): bool
    {
        return  $this->method === $request->getMethod() &&
                $this->path === $request->getUri()->getPath();
    }
}

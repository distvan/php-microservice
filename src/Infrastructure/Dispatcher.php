<?php

declare(strict_types=1);

namespace App\Infrastructure;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Dispatcher class
 *
 * @package App\Infrastructure
 */
class Dispatcher
{
    private const NOT_FOUND = 404;

    /**
     * Constructor
     *
     * @param IRouter $router
     */
    public function __construct(
        private IRouter $router
    ) {
    }

    /**
     * handle
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $handler = $this->router->match($request);

        if ($handler === null) {
            return new Response(self::NOT_FOUND, [], 'Not Found');
        }

        return $handler($request);
    }
}

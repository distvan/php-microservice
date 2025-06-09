<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Http\Controllers\WatermarkController;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * RoutesConfigurator
 *
 * @package App\Infrastructure
 */
class RoutesConfigurator
{
    /**
     * configure
     *
     * @param IRouter $router
     * @param ContainerInterface $container
     */
    public function configure(IRouter $router, ContainerInterface $container): void
    {
        $router->add('POST', '/watermark', fn(ServerRequestInterface $request) => $container->get(WatermarkController::class)->__invoke($request));
    }
}

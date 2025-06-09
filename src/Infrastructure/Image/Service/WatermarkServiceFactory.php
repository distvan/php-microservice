<?php

declare(strict_types=1);

namespace App\Infrastructure\Image\Service;

/**
 * WatermarkService factory
 *
 * @package App\Infrastructure\Image\Service
 */
class WatermarkServiceFactory
{
    /**
     * create
     */
    public static function create(): WatermarkService
    {
        return new WatermarkService();
    }
}

<?php

declare(strict_types=1);

namespace App\Shared;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

/**
 * NotFoundException
 *
 * @package App\Shared
 */
class NotFoundException extends Exception implements NotFoundExceptionInterface
{
}

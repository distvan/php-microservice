<?php

declare(strict_types=1);

namespace App\Shared;

use Exception;
use Psr\Container\ContainerExceptionInterface;

/**
 * ContainerException
 *
 *  @package App\Shared
 */
class ContainerException extends Exception implements ContainerExceptionInterface
{
}

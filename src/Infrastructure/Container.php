<?php
declare(strict_types=1);

namespace App\Infrastructure;

use App\Shared\NotFoundException;
use Psr\Container\ContainerInterface;

/**
 * Container class
 *
 * @package App\Infrastructure
 */
class Container implements ContainerInterface
{
    protected array $bindings = [];
    protected array $instances = [];

    /**
     * Bind
     *
     * @param string $id
     * @param callable $resolver
     */
    public function bind(string $id, callable $resolver): void
    {
        $this->bindings[$id] = $resolver;
    }

    /**
     * Get
     *
     * @param string $id
     */
    public function get(string $id): mixed
    {
        if (!$this->has($id)) {
            throw new NotFoundException("No entry found for $id");
        }

        if (!isset($this->instances[$id])) {
            $this->instances[$id] = $this->bindings[$id]($this);
        }

        return $this->instances[$id];
    }

    /**
     * Has
     *
     * @param string $id
     */
    public function has(string $id): bool
    {
        return isset($this->bindings[$id]);
    }
}

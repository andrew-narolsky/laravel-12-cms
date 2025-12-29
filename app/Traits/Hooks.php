<?php

namespace App\Traits;

use Illuminate\Support\Arr;

trait Hooks
{
    protected array $callbacks = [];

    public function hookSet(string $key, $object, $method): void
    {
        $this->callbacks = Arr::add(
            $this->callbacks,
            $key . '.' . get_class($object),
            [ 'object' => $object, 'method' => $method ]
        );
    }

    public function hookRemove(string $key, $object): void
    {
        Arr::forget($this->callbacks, $key . '.' . get_class($object));
    }

    public function hookExists(string $key): bool
    {
        return Arr::has($this->callbacks, $key);
    }

    public function hookRun(string $key, array $args = []): void
    {
        $callbacks = Arr::get($this->callbacks, $key, []);

        foreach ($callbacks as $item)
        {
            call_user_func_array([$item['object'], $item['method']], $args);
        }
    }
}

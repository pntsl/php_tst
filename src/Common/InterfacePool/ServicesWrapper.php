<?php
namespace Common\InterfacePool;

class ServicesWrapper implements ServicesInterface
{
    public function __construct(
        protected $target = null
    ) {
    }

    public function get(string $key)
    {
        return $this->target->get($key);
    }
}

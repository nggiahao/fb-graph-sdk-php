<?php


namespace Nggiahao\Facebook\Models;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Nggiahao\Facebook\Models\Concerns\HasAttributes;

abstract class Model implements Arrayable, Jsonable
{
    use HasAttributes;

    /**
     * Model constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setAttributes($attributes);
    }

    public function toArray()
    {
        return $this->attributesToArray();
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }


}
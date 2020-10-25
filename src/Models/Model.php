<?php


namespace Nggiahao\Facebook\Models;


abstract class Model
{
    public $original = [];

    public $attribute = [];

    /**
     * Model constructor.
     *
     * @param array $attribute
     */
    public function __construct(array $attribute = [])
    {
        $this->original = $attribute;
        $this->casting();
    }

    /**
     * @return $this
     */
    public function casting() {
        $this->attribute = $this->original;

        return $this;
    }


}
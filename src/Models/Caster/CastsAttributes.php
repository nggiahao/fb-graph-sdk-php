<?php


namespace Nggiahao\Facebook\Models\Caster;


interface CastsAttributes
{
    /**
     * Cast the given value
     * @param $value
     *
     * @return mixed
     */
    public function get($value);

    /**
     * Prepare the given value for storage.
     * @param $value
     * @return mixed
     */
    public function set($value);
}
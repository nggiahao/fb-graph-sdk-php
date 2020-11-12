<?php


namespace Nggiahao\Facebook\Models\Caster;


use Nggiahao\Facebook\Models\User;

class UserCaster implements CastsAttributes
{

    public function get($value)
    {
        return new User($value);
    }

    public function set($value)
    {
        // TODO: Implement set() method.
    }
}
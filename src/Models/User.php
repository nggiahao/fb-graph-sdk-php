<?php


namespace Nggiahao\Facebook\Models;

/** @property mixed $picture */
class User extends Model
{
    public function getAvatarAttribute() {
        return $this->picture['data']['url'];
    }
}
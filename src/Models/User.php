<?php


namespace Nggiahao\Facebook\Models;


class User extends Model
{
    protected $casts = [
      'date' => 'datetime'
    ];

    protected function getFullNameAttribute() {
        return $this->first_name . ' ' . $this->last_name;
    }
}
<?php


namespace Nggiahao\Facebook\Models;


class Factory
{
    /**
     * @param $classname
     * @param array $attribute
     *
     * @return mixed
     * @throws \Exception
     */
    public static function make($classname, array $attribute = []) {
        if (!class_exists($classname)) {
            throw new \Exception("Class $classname not found");
        } elseif (!is_subclass_of($classname, Model::class)) {
            throw new \Exception("$classname must extend " . Model::class);
        } else {
            return new $classname($attribute);
        }
    }
}
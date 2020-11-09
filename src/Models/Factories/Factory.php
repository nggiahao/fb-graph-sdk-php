<?php


namespace Nggiahao\Facebook\Models\Factories;

use Illuminate\Support\Collection;
use Nggiahao\Facebook\Models\Model;

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

    /**
     * @param $classname
     * @param array $data
     *
     * @return Collection
     * @throws \Exception
     */
    public static function makeCollection($classname, array $data) {
//        if (!empty($data['paging'])) {
//            $paging = $data['paging'];
//            unset($data['paging']);
//        }

        $data = array_map(function ($item) use ($classname) {
            return self::make($classname, $item);
        }, $data);

        return new Collection($data);
    }
}
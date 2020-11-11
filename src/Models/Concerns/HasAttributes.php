<?php


namespace Nggiahao\Facebook\Models\Concerns;


use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Nggiahao\Facebook\Models\Caster\AttachmentCaster;
use Nggiahao\Facebook\Models\Caster\CastsAttributes;
use Nggiahao\Facebook\Support\Helpers;

trait HasAttributes
{
    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The model attribute's original state.
     *
     * @var array
     */
    protected $original = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * @var string[]
     */
    protected static $default_casts = [
        'birthday' => 'datetime',
        'created_time' => 'datetime',
        'updated_time' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'backdated_time' => 'datetime',
        'issued_at' => 'datetime',
        'expires_at' => 'datetime',
        'publish_time' => 'datetime',
        'joined' => 'datetime',
    ];

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'ISO8601';

    /**
     * Indicates whether attributes are snake cased on arrays.
     *
     * @var bool
     */
    public static $snakeAttributes = true;

    /**
     * The cache of the mutated attributes for each class.
     *
     * @var array
     */
    protected static $mutatorCache = [];

    /**
     * @param $name
     *
     * @return mixed|null
     */
    public function __get($name) {
        if ($this->hasGetMutator($name)) {
            return $this->mutateAttribute($name, $this->getAttribute($name));
        } elseif (array_key_exists($name, $this->getAttributes())) {
            return $this->getAttribute($name);
        } else {
            return null;
        }
    }

    /**
     * @param $key
     * @param $value
     *
     * @return HasAttributes
     */
    public function __set($key, $value) {
        return $this->setAttribute($key, $value);
    }
    /**
     * @return array
     */
    public function getOriginal(): array
    {
        return $this->original;
    }

    /**
     * @param array $original
     *
     * @return $this
     */
    protected function setOriginal(array $original)
    {
        $this->original = $original;
        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public function setAttributes(array $attributes)
    {
        $this->setOriginal($attributes);
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
        return $this;
    }

    /**
     * Get a plain attribute.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        return $this->getAttributes()[$key] ?? null;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        if ($this->isCastAttribute($key)) {
            $value = $this->castAttribute($key, $value);
        }
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * @return array
     */
    protected function getCasts(): array
    {
        return array_merge(self::$default_casts, $this->casts);
    }

    /**
     * @param $key
     *
     * @return bool
     */
    protected function isCastAttribute($key) {
        $casts = $this->getCasts();
        return in_array($key, array_keys($casts));
    }

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    protected function castAttribute($key, $value) {
        $cast_type = $this->getCastType($key);
        if (is_null($value)) {
            return null;
        }

        if (is_subclass_of($cast_type, CastsAttributes::class)) {
            return (new $cast_type)->get($value);
        }

        switch ($cast_type) {
            case 'int':
            case 'integer':
                return (int) $value;
            case 'real':
            case 'float':
            case 'double':
                return Helpers::fromFloat($value);
            case 'string':
                return (string) $value;
            case 'bool':
            case 'boolean':
                return (bool) $value;
            case 'array':
            case 'json':
                return json_decode($value, true);
            case 'collection':
                if (!is_array($value)) {
                    $value = json_decode($value, true);
                }
                return new Collection($value);
            case 'datetime':
                $timestamp = strtotime($value);

                $time = new Carbon();
                $time->setTimestamp($timestamp);
                return $time;
        }

        return $value;
    }

    /**
     * @param $key
     *
     * @return mixed|null
     */
    protected function getCastType($key) {
        return $this->getCasts()[$key] ?? null;
    }

    /**
     * Convert the model's attributes to an array.
     *
     * @return array
     */
    protected function attributesToArray() {
        $attributes = $this->addMutatedAttributesToArray(
            $this->getAttributes(), $this->getMutatedAttributes()
        );

        $result = [];
        foreach ($attributes as $key => $value) {
            if ($cast_type = $this->getCastType($key)) {
                if ($cast_type === 'date' || $value === 'datetime' || $value instanceof DateTimeInterface) {
                    $value = $this->serializeDate($value);
                } elseif ($cast_type instanceof Arrayable) {
                    $value = $value->toArray();
                }
            } elseif ($value instanceof Arrayable) {
                $value = $value->toArray();
            }
            $result[$key] = $value;
        }
        return $result;
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param DateTimeInterface $date
     *
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return Carbon::instance($date)->toJSON();
    }

    /**
     * Add the mutated attributes to the attributes array.
     *
     * @param array $attributes
     * @param array $mutated_attributes
     *
     * @return array
     */
    protected function addMutatedAttributesToArray(array $attributes, array $mutated_attributes)
    {
        foreach ($mutated_attributes as $key) {
            $attributes[$key] = $this->mutateAttributeForArray(
                $key, $attributes[$key] ?? null
            );
        }

        return $attributes;
    }

    /**
     * Determine if a get mutator exists for an attribute.
     *
     * @param  string  $key
     * @return bool
     */
    protected function hasGetMutator($key)
    {
        return method_exists($this, 'get'.Helpers::strStudly($key).'Attribute');
    }

    /**
     * Get the value of an attribute using its mutator.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function mutateAttribute($key, $value)
    {
        return $this->{'get'.Helpers::strStudly($key).'Attribute'}($value);
    }

    /**
     * Get the value of an attribute using its mutator for array conversion.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function mutateAttributeForArray($key, $value)
    {
        $value = $this->mutateAttribute($key, $value);

        return $value instanceof Arrayable ? $value->toArray() : $value;
    }

    /**
     * Get the mutated attributes for a given instance.
     *
     * @return array
     */
    protected function getMutatedAttributes()
    {
        $class = static::class;

        if (empty(static::$mutatorCache)) {
            static::cacheMutatedAttributes($class);
        }

        return static::$mutatorCache[$class];
    }

    /**
     * Extract and cache all the mutated attributes of a class.
     *
     * @param  string  $class
     * @return void
     */
    protected static function cacheMutatedAttributes($class)
    {
        static::$mutatorCache[$class] = collect(static::getMutatorMethods($class))->map(function ($match) {
            return lcfirst(static::$snakeAttributes ? Helpers::strSnake($match) : $match);
        })->all();
    }

    /**
     * Get all of the attribute mutator methods.
     *
     * @param  mixed  $class
     * @return array
     */
    protected static function getMutatorMethods($class)
    {
        preg_match_all('/(?<=^|;)get([^;]+?)Attribute(;|$)/', implode(';', get_class_methods($class)), $matches);

        return $matches[1];
    }

}
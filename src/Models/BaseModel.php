<?php

namespace Quiz\Models;


use JsonSerializable;

class BaseModel implements JsonSerializable
{

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
        return $this;
    }

    /**
     * Convert snake_case to camelCase
     *
     * TODO: make this a global function?
     *
     * @param $name
     * @return mixed|string
     */
    private function snakeToCamel($name)
    {
        $exploded = explode('_', $name);
        $ret = array_shift($exploded);
        foreach ($exploded as $word) {
            $ret = $ret . ucfirst($word);
        }
        return $ret;
    }

    /**
     * Sets attributes...
     *
     * @param array $attributes
     */
    public function setAttributes(array $attributes = [])
    {
        foreach ($attributes as $name => $value) {
            $name = $this->snakeToCamel($name);
            if (property_exists(static::class, $name)) {
                $this->$name = htmlspecialchars($value);
            }
        }
    }
}
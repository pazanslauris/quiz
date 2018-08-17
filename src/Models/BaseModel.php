<?php
/**
 * Created by PhpStorm.
 * User: Lauris
 * Date: 8/17/2018
 * Time: 11:19 AM
 */

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
}
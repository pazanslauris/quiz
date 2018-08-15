<?php
/**
 * Created by PhpStorm.
 * User: Lauris
 * Date: 8/14/2018
 * Time: 10:14 AM
 */

namespace Quiz\Models;


class UserModel
{
    /** @var int */
    public $id = 0;

    /** @var string */
    public $name;

    /**
     * UserModel constructor.
     * @param int $id
     * @param string $name
     */
    public function __construct(int $id = 0, string $name = '')
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isNew(): bool {
        return ($this->id == 0);
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return ($this->id !== 0);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Lauris
 * Date: 8/14/2018
 * Time: 10:07 AM
 */

namespace Quiz\Models;


class QuizModel
{
    /** @var int */
    public $id;
    /** @var string */
    public $name;

    public function __construct(int $id = 0, string $name = '')
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return ($this->id !== 0);
    }
}
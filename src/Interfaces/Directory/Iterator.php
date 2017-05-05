<?php
/**
 * Created by PhpStorm.
 * User: igorek
 * Date: 12.04.17
 * Time: 2:26
 */

namespace Zakirovir6\Interfaces\Directory;


interface Iterator extends \Iterator
{
    /**
     * @return bool
     */
    public function hasErrors();

    /**
     * @return string[]
     */
    public function getErrors();
}
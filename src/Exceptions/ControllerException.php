<?php
/**
 * Created by PhpStorm.
 * User: hamdon
 * Date: 2019/4/10
 * Time: 9:11
 */
namespace Hamdon\Beaver\Exceptions;

class ControllerException extends \Exception
{
    function __construct($msg='')
    {
        parent::__construct($msg);
    }
}
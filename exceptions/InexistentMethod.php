<?php
namespace exceptions;

/**
 * Description of InexistentMethod
 *
 * @author caiorezende
 */
class InexistentMethod extends \Exception{
    public function __construct($class, $method) {
        $message = "Method '{$method}' doesn\'t exist in '{$class}'.";
        parent::__construct($message);
    }
}
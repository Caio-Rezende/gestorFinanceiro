<?php
namespace exceptions;

/**
 * Description of InexistentPropertie
 *
 * @author caiorezende
 */
class InexistentPropertie extends \Exception{
    public function __construct($class, $propertie) {
        $message = "Propertie '{$propertie}' doesn\'t exist in '{$class}'.";
        parent::__construct($message);
    }
}
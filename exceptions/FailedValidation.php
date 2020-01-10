<?php
namespace exceptions;

/**
 * Description of FailedValidation
 *
 * @author caiorezende
 */
class FailedValidation extends \Exception{
    protected $fails = array();

    public function __construct(array $fails) {
        $this->fails = $fails;
    }

    public function getFails() {
        $aux = array_merge($this->fails, array());
        $this->fails = array();
        return $aux;
    }
}
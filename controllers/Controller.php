<?php
namespace controllers;

/**
 * Description of Controller
 *
 * @author caiorezende
 */
abstract class Controller {

    abstract public function init();
    abstract public function prepare();
}
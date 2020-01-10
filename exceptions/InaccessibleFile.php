<?php
namespace exceptions;

/**
 * Description of FileNotFound
 *
 * @author caiorezende
 */
class InaccessibleFile  extends \Exception{
    public function __construct($file) {
        $message = "File '{$file}' not found.";
        parent::__construct($message);
    }
}
<?php
namespace exceptions;

/**
 * Description of RowNotFound
 *
 * @author caiorezende
 */
class RowNotFound extends \Exception {
    public function __construct($table, $id) {
        $message = "ID '{$id}' doesn't exist in '{$table}'.";
        parent::__construct($message);
    }
}
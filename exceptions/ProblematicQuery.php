<?php
namespace exceptions;

/**
 * Description of ProblematicQuery
 *
 * @author caiorezende
 */
class ProblematicQuery extends \Exception {
    public function __construct(\PDOStatement $sth) {
        $message = $sth->errorInfo();
        parent::__construct($message[2]);
    }
}
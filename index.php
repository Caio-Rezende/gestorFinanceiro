<?php

define('PATH', __DIR__);

$whitelist = array('localhost', '127.0.0.1');

if (array_key_exists('HTTP_HOST', $_SERVER)) {
    $host = $_SERVER['HTTP_HOST'];
    if (strpos($host, ':')!==FALSE) {
        $host = explode(':', $host);
        $host = $host[0];
    }
} else {
    $host = '';
}

if(in_array($host, $whitelist)){
    $url = explode(DIRECTORY_SEPARATOR, __DIR__);
    $url = array_reverse($url);
    define('URL', '/'.$url[1] .'/'.$url[0]);
    define('LOCALHOST', true);
    unset($url);
} else {
    if( ! isset($_SERVER['HTTPS'] ) ) {
        header('Status-Code: 301');
        header('Location: https://'.$host.$_SERVER['REQUEST_URI']);
    }
    define('URL', '');
    define('LOCALHOST', false);
}

if (array_key_exists('debug', $_GET)) {
    $_SESSION['debug'] = $_GET['debug'];
}

define('DEBUG', (LOCALHOST || $_SESSION['debug']));

session_set_cookie_params(30*60*60, URL);
session_start();
date_default_timezone_set('America/Sao_Paulo');

function autoload($class) {
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    if (is_readable($file)) {
        require_once $file;
    } else {
        throw new \exceptions\InaccessibleFile($file);
    }
}

use controllers\Login;
use core\DB;
use exceptions\FailedValidation;
use views\Errors;

try {
    
    $isAjax = array_key_exists('ajax', $_GET);

    if (isset($_GET['debug']) === TRUE) {
        error_reporting(E_ALL);
    }

    // Use default autoload implementation
    spl_autoload_register('autoload');

    $objControl = decideControl();
    $method = isset($_GET['method']) ? $_GET['method'] : 'init';

    if (method_exists($objControl, $method) === TRUE) {
        $ret = $objControl->{$method}();
    } else {
        $ret = $objControl->init();
    }
    if ($isAjax === true) {
        header('Content-type: application/json; charset=utf-8');
        echo json_encode(array('ret' => $ret));
    }
} catch (FailedValidation $e) {
    if (DB::get()->inTransaction()) {
        DB::get()->rollBack();
    }
    unset($_GET);
    unset($_POST);
    $_GET  = array();
    $_POST = array();

    if ($isAjax === false) {
        $aux = $e->getFails();
        foreach($aux as $fail) {
            Errors::addError($fail);
        }
        $objControl->init();
    } else {
        header('Content-type: application/json; charset=utf-8');
        echo json_encode(array('ret' => 0));
    }
} catch (Exception $e) {
    if (DB::get()->inTransaction()) {
        DB::get()->rollBack();
    }
    
    if ($isAjax === false) {
        if (DEBUG) {
            echo '<pre>' . print_r($e, true);
        } else {
           Errors::addError('<pre>' . htmlentities(print_r($e->getMessage(), true), ENT_COMPAT, 'UTF-8'));
           unset($_GET);
           $_GET = array();
           $objControl = decideControl();
           $objControl->init();   
        }
    } else {
        header('Content-type: application/json; charset=utf-8');
        echo json_encode(array('ret' => 0));
    }
}

function decideControl() {
    $control = 'controllers\\'
            . (
                !Login::isLogged()
                ? 'Login'
                : ( isset($_GET['control'])
                    ? $_GET['control']
                    : 'Main'
                )
            );
    return new $control();
}
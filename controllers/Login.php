<?php
namespace controllers;

use views\View;
use views\BreadCrumb;
use views\Errors;
use models\mdUsuario;
use views\Menu as vwMenu;

/**
 * Description of Login
 *
 * @author caiorezende
 */
class Login extends Controller implements Menu{

    public function init() {
        vwMenu::setController($this);
        vwMenu::setName('gestorFinanceiro');
        BreadCrumb::addPath('Login','Login');
        View::renderTemplate('basic', array('content' => 'login.html'));
    }

    public function authenticate() {
        if (isset($_POST['usuario']) === TRUE && $_POST['usuario'] != ''
            && isset($_POST['senha']) === TRUE
        ) {
            $users = mdUsuario::findBy(array('strLogin' => $_POST['usuario']));
            if (count($users) == 1) {
                $user = $users[0];
                if (self::comparePasswords($_POST['senha'], $user['pasSenha']) === TRUE) {
                    $_SESSION['USER'] = $user;
                    $control = new Main();
                    $control->init();
                    return;
                }
            }
        }
        Errors::addError('Usuario nao confere! Tente novamente.');
        $this->init();
    }

    public function logout(){
        $_SESSION['USER'] = null;
        unset($_SESSION['USER']);
        $this->init();
    }

    public static function isLogged(){
        return isset($_SESSION['USER']) === TRUE && $_SESSION['USER'] !== null;
    }
    
    public static function getLoggedUser() {
        return $_SESSION['USER'];
    }

    public static function encrypt($val, $salt = '') {
        if ($salt == '') {
            for ($i = 0; $i<8; $i++) {
                $salt .= chr(rand(48, 126));
            }
        }
        $val  =  substr($salt, 0, 4)
            . sha1(substr($salt, 0, 4) . $val . substr($salt, 4))
            . substr($salt, 4);

        return $val;
    }

    public static function comparePasswords($unencrypted, $encrypted) {
        $salt = substr($encrypted, 0, 4) . substr($encrypted, -4);
        $unencrypted = self::encrypt($unencrypted, $salt);
        return $unencrypted == $encrypted;
    }

    public function prepare() {

    }

}
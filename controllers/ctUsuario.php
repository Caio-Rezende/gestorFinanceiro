<?php
namespace controllers;

use core\DB;
use models\mdUsuario;
use views\Messages;
use mappers\mpUsuario;

/**
 * Description of Usuario
 *
 * @author caiorezende
 */
class ctUsuario extends Controller implements FormBasico {
    public function init() {
        $control = new Main();
        $_GET['opt'] = Main::MAIN_OPT_USUARIOS;
        $control->init();
    }

    public function save() {
        if (isset($_POST['usuario'])) {
            $usuario = new mdUsuario();
            $usuario->setArray($_POST['usuario']);
            DB::get()->beginTransaction();
            $usuario->getMapper()->save($usuario);
            DB::get()->commit();

            $_GET['id'] = $usuario->getId();
            Messages::addMessage('Salvamento de usu&aacute;rio bem sucedido.');
            
            if($usuario->getId() == $_SESSION['USER']['id']) {
                $_SESSION['USER']['strNome'] = $usuario->getStrNome();
            }
        }

        $this->init();
    }

    public function delete() {
        if (isset($_POST['usuario'])) {
            $usuario = new mdUsuario();
            $usuario->setArray($_POST['usuario']);
            DB::get()->beginTransaction();
            $usuario->getMapper()->delete($usuario);
            DB::get()->commit();
            Messages::addMessage('Exclus&atilde;o de usu&aacute;rio bem sucedida.');
        }

        $this->init();
    }

    public function prepare() {
        global $usuario, $usuarios;

        if(isset($_GET['id']) === true) {
            $usuario = mdUsuario::find($_GET['id']);
        } else {
            $usuario = new mdUsuario();
        }

        $usuarios = mdUsuario::findBy(array(), array(), array('strNome'));
    }

    public static function makeCondition($alias = 'id') {
        if (isset($_SESSION['USER']) && is_array($_SESSION['USER'])
            && isset($_SESSION['USER']['id']) && $_SESSION['USER']['id'] > 0) {
            $condition = mpUsuario::makeCondition($alias);
        } else {
            $condition = '';
        }

        return $condition;
    }
    
    public function salvarSemVinculo() {
        if (isset($_POST['usuario']) && $_SESSION['USER']['id'] == 1) {
            $usuario = new mdUsuario();
            $usuario->setArray($_POST['usuario']);
            DB::get()->beginTransaction();
            $usuario->getMapper()->directSave($usuario);
            
            Messages::addMessage('Salvamento de usu&aacute;rio (' . $usuario->getStrNome() . ') bem sucedido.');
            
            ctGrupo::criarGruposBasicos($usuario->getId());
            DB::get()->commit();
        }
        
        $this->init();
    }
}
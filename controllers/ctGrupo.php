<?php
namespace controllers;

use core\DB;
use models\mdGrupo;
use views\Messages;

/**
 * Description of Grupo
 *
 * @author caiorezende
 */
class ctGrupo extends Controller implements FormBasico {
    public function init() {
        $control = new Main();
        $_GET['opt'] = Main::MAIN_OPT_GRUPOS;
        $control->init();
    }

    public function save() {
        if (isset($_POST['grupo'])) {
            $grupo = new mdGrupo();
            $grupo->setArray($_POST['grupo']);
            $grupo->setIdUsuario($_SESSION['USER']['id']);
            DB::get()->beginTransaction();
            $grupo->getMapper()->save($grupo);
            DB::get()->commit();

            $_GET['id'] = $grupo->getId();
            Messages::addMessage('Salvamento de grupo bem sucedido.');
        }

        $this->init();
    }

    public function delete() {
        if (isset($_POST['grupo'])) {
            $grupo = new mdGrupo();
            $grupo->setArray($_POST['grupo']);
            DB::get()->beginTransaction();
            $grupo->getMapper()->delete($grupo);
            DB::get()->commit();
            Messages::addMessage('Exclus&atilde;o de grupo bem sucedida.');
        }

        $this->init();
    }

    public function novaContaGrupo(){
        if (isset($_POST['grupo'])) {
            $_GET['idGrupo'] = $_POST['grupo']['id'];
            $conta = new ctConta();
            $conta->init();
        } else {
            $this->init();
        }
    }

    public function prepare() {
        global $grupo, $grupos;

        if(isset($_GET['id']) === true) {
            $grupo = mdGrupo::find($_GET['id']);
        } else {
            $grupo = new mdGrupo();
        }

        $grupos = mdGrupo::findBy(array(), array(), array('strTipo', 'strNome'));
    }
    
    public static function criarGruposBasicos($idUsuario) {
        $grupos = array(
            array('strNome' => 'Dízimo e Ofertas', 'strTipo' => 's'),
            array('strNome' => 'Salário', 'strTipo' => 'e'),
            array('strNome' => 'Outros', 'strTipo' => 's'),
            array('strNome' => 'Tecnologia', 'strTipo' => 's'),
            array('strNome' => 'Contas Fixas (Energia, Água, Telefone)', 'strTipo' => 's'),
            array('strNome' => 'Roupa', 'strTipo' => 's'),
            array('strNome' => 'Carro', 'strTipo' => 's'),
            array('strNome' => 'Supermercado', 'strTipo' => 's'),
            array('strNome' => 'Restaurante', 'strTipo' => 's'),
            array('strNome' => 'Saúde', 'strTipo' => 's'),
            array('strNome' => 'Presentes', 'strTipo' => 's'),
            array('strNome' => 'Presentes', 'strTipo' => 'e'),
            array('strNome' => 'Outros', 'strTipo' => 'e'),
            array('strNome' => 'Educação (Livros, Escola)', 'strTipo' => 's'),
            array('strNome' => 'Jóias / Bijouterias', 'strTipo' => 's'),
            array('strNome' => 'Mobília', 'strTipo' => 's'),
            array('strNome' => 'Poupança', 'strTipo' => 's')
        );
        foreach($grupos as $grupo) {
            $grupo['idUsuario'] = $idUsuario;
            $mdGrupo = new mdGrupo();
            $mdGrupo->setArray($grupo);
            $mdGrupo->getMapper()->save($mdGrupo);
            Messages::addMessage('Salvamento de grupo (' . $grupo['strNome'] . ') bem sucedido.');
        }
    }
}
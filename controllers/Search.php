<?php
namespace controllers;

use models\mdConta;
use models\mdGrupo;

/**
 * Description of Search
 *
 * @author caiorezende
 */
class Search extends Controller {
    public function init() {
        // Logica de controle sendo desviada para Main Controller
        $_GET['opt'] = Main::MAIN_OPT_SEARCH;
        $control = new Main();
        $control->init();
    }

    public function prepare() {
        global $resultados;

        $resultados = array('grupos' => array(), 'contas' => array());

        // Considera uma pesquisa informada ou vazio
        if (isset($_POST['pesquisa']) === TRUE) {
            $resultados['grupos'] = mdGrupo::findBy(
                array('strNome' => $_POST['pesquisa']),
                array(),
                array('strNome')
            );
            $resultados['contas'] = mdConta::findBy(
                array('strNome' => $_POST['pesquisa']),
                array(),
                array('dteInicial desc', 'strNome')
            );
        }
    }
}
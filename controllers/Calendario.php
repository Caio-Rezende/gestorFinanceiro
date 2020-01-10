<?php
namespace controllers;

use models\mdConta;
use models\mdGrupo;

/**
 * Description of Calendario
 *
 * @author caiorezende
 */
class Calendario extends Controller {
    public function init() {
        // Logica de controle sendo desviada para Main Controller
        $_GET['opt'] = Main::MAIN_OPT_CALENDARIO;
        $control = new Main();
        $control->init();
    }

    public function prepare() {
        global $dateSearch, $tipoDte, $tipoPago;
        $dateSearch = isset($_POST['dateSearch'])
            ? intval($_POST['dateSearch'], 10)
            : time();
        $tipoDte    = isset($_POST['tipoDte'])
            ? $_POST['tipoDte']
            : 'dte_inicial';
        $tipoPago    = isset($_POST['tipoPago'])
            ? $_POST['tipoPago']
            : 'all';
    }

    public function getEventos(){
        $tipo = (isset($_GET['tipoDte']) === false)
            ? 'dte_inicial'
            : $_GET['tipoDte'];
        $aux = explode('_', $tipo);
        $dteField = $aux[0] . ucfirst($aux[1]);

        $whereTipoPago = (isset($_GET['tipoPago']) === false || $_GET['tipoPago'] == 'all')
            ? ''
            : ' AND bol_paga = ' . $_GET['tipoPago'];


        $dateStart = (isset($_GET['start']) === TRUE && $_GET['start'] != '')
            ? date('Y-m-d H:i:s', $_GET['start'])
            : date('Y-m-01 00:00:00');
        $dateEnd = (isset($_GET['end']) === TRUE && $_GET['end'] != '')
            ? date('Y-m-d H:i:s', $_GET['end'])
            : date('Y-m-t 23:59:59');

        $contas = mdConta::findBy(
            array(
                'where' =>
                    " {$tipo} > '{$dateStart}'"
                    . " AND {$tipo} < '{$dateEnd}'"
                    . $whereTipoPago
            ),
            array(),
            array(),
            TRUE
        );

        $aux = array();
        foreach ($contas as $indexCnt => $cnt) {
            $className = $cnt['strTipo']==mdGrupo::TIPO_ENTRADA ? 'positive' : 'negative';
            $title     =
                $cnt['strNome']
                .($cnt['intParcelas'] > 1
                    ? ' ' . $cnt['intParcelaAtual']
                        . '/' . $cnt['intParcelas']
                    : '')
                . " ({$cnt['numValor']})"
                . '<input type="checkbox" '
                . 'data-id="' . $cnt['id'] . '"'
                . ' data-strNome="' . htmlentities($cnt['strNome'], ENT_COMPAT, 'UTF-8') . '"'
                . ' onclick="alteraBolPaga(this)" '
                . ' title="A conta já foi paga?"'
                . ( $cnt['bolPaga'] ? 'checked="checked"' : '' )
                . '/>'
            ;
            $aux[]     = array(
                'title' => $title,
                'className' => $className,
                'start' => strtotime($cnt[$dteField]),
                'url'  => 'index.php?control=ctConta&id=' . $cnt['id']
            );
        }
        echo json_encode($aux);
    }
}
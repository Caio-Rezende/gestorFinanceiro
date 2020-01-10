<?php
namespace controllers;

use models\mdGrupo;
use models\mdConta;
use views\View;

/**
 * Description of Relatorio
 *
 * @author caiorezende
 */
class Relatorio extends Controller{
    public function init() {
        $control = new Main();
        $_GET['opt'] = Main::MAIN_OPT_RELATORIOS;
        $control->init();
    }

    public function prepare() {
        global $dateSearch, $grupos, $contas, $contasOrd, $mesBusca, $tipoPago;

        $grupos = mdGrupo::findBy(array(), array(), array('strTipo','strNome'));

        $dateSearch = (isset($_POST['dateSearch']) === TRUE && $_POST['dateSearch'] != '')
            ? $_POST['dateSearch'] . ' 00:00:00'
            : date('Y-m-01 00:00:00');

        $tipoPago    = isset($_POST['tipoPago'])
            ? $_POST['tipoPago']
            : 'all';

        $whereTipoPago = ($tipoPago == 'all')
            ? ''
            : ' AND bol_paga = ' . $tipoPago;

        $contas = mdConta::findBy(
            array(
                'where' =>
                    " dte_inicial >= '{$dateSearch}' - INTERVAL 2 MONTH "
                    . " AND dte_inicial < '{$dateSearch}' + INTERVAL 3 MONTH "
                    . $whereTipoPago
            ),
            array(),
            array('id_grupo'),
            TRUE
        );

        $dateSearch = strtotime($dateSearch);

        $contasOrd = array();
        for($i = -2; $i < 3; $i++) {
            $timeAtual = mktime(0, 0, 0, date('n', $dateSearch) + $i, 1, date('Y', $dateSearch));
            $mes = View::mesDoAno(date('n', $timeAtual))
                . date(' (Y)', $timeAtual);
            if ($i == 0) {
                $mesBusca = $mes;
            }
            $contasOrd[$mes] = array();
            foreach ($grupos as $grupo) {
                $contasOrd[$mes][$grupo['id']] = array('parcial' => 0, 'contas' => array());
            }
        }

        foreach ($contas as $indexCnt => &$cnt) {
            $time = strtotime($cnt['dteInicial']);
            $mes = View::mesDoAno(date('n', $time))
                . date(' (Y)', $time);
            //Se o mes nao estiver presente, ignora
            if (in_array($mes, array_keys($contasOrd)) === FALSE) {
                continue;
            }
            $contasOrd[$mes][$cnt['idGrupo']]['parcial'] += $cnt['numValor'];
            $contasOrd[$mes][$cnt['idGrupo']]['contas'][] = $indexCnt;
            foreach($contasOrd as $mes => $gruposAux) {
                foreach ($gruposAux as $id => $grupo) {
                    if ($id == $cnt['idGrupo'] && in_array($indexCnt, $grupo['contas']) === FALSE) {
                        $contasOrd[$mes][$id]['contas'][] = 'z.'.$indexCnt;
                    }
                }
            }
        }
    }
}
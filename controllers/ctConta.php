<?php
namespace controllers;

use core\DB;
use models\mdGrupo;
use models\mdConta;
use mappers\mpConta;
use views\Messages;

/**
 * Description of Conta
 *
 * @author caiorezende
 */
class ctConta extends Controller implements FormBasico {
    public function init() {
        // Logica de controle sendo desviada para Main Controller
        $_GET['opt'] = Main::MAIN_OPT_CONTAS;
        $control = new Main();
        $control->init();
    }

    public function save() {
        if (isset($_POST['conta'])) {
            $conta = new mdConta();
            $conta->setArray($_POST['conta']);
            $conta->setIdUsuario($_SESSION['USER']['id']);
            $conta->setIntParcelaAtual(0);
            DB::get()->beginTransaction();
            $conta->getMapper()->save($conta);
            DB::get()->commit();

            $_GET['id'] = $conta->getId();
            Messages::addMessage('Salvamento de conta bem sucedido.');
        }

        $this->init();
    }

    public function delete() {
        if (isset($_POST['conta'])) {
            $conta = new mdConta();
            $conta->setArray($_POST['conta']);
            DB::get()->beginTransaction();
            $conta->getMapper()->delete($conta);
            DB::get()->commit();

            $_GET['idGrupo'] = $conta->getIdGrupo();
            Messages::addMessage('Exclus&atilde;o de conta bem sucedida.');
        }

        $this->init();
    }

    public function alteraBolPaga() {
        if (isset($_POST['id'])) {
            $conta = new mpConta();
            $conta->alteraBolPaga($_POST['id']);
        }
        return 1;
    }

    public function prepare() {
        global $conta, $grupos, $mdGrupo, $contasOrd;

        // Considera um id informado ou vazio
        if (isset($_GET['id']) === TRUE) {
            $conta = mdConta::find($_GET['id']);
            $_GET['idGrupo'] = $conta->getIdGrupo();
        } else {
            $conta = new mdConta();
            if (isset($_GET['date']) === TRUE) {
                if (isset($_GET['tipoDte']) === FALSE || $_GET['tipoDte'] == 'dte_inicial') {
                    $conta->setDteInicial(date('Y-m-d', $_GET['date'] / 1000));
                } else {
                    $conta->setDteCompra(date('Y-m-d', $_GET['date'] / 1000));
                }
            }
        }

        // Considera um grupo informado ou vazio
        if (isset($_GET['idGrupo']) === TRUE) {
            $mdGrupo = mdGrupo::find($_GET['idGrupo']);
        } else {
            $mdGrupo = new mdGrupo();
        }

        // Caso haja um grupo, listar as contas para ele, ordenadas por mes - ano
        if (isset($_GET['idGrupo']) === FALSE) {
            $contasOrd = array();
        } else {
            $contas = mdConta::findBy(
                array('idGrupo' => $_GET['idGrupo']),
                array(),
                array('dte_inicial desc','str_nome')
            );

            $contasOrd = array();

            foreach ($contas as &$cnt) {
                $cnt['strNome'] = $cnt['strNome']
                    . ($cnt['intParcelas'] > 1
                        ? ' ' . $cnt['intParcelaAtual']
                            . '/' . $cnt['intParcelas']
                        : '');
                $time = strtotime($cnt['dteInicial']);
                $mes = mktime(0, 0, 0, date('n', $time) + 1, 0, date('Y', $time));
                if (in_array($mes, array_keys($contasOrd)) === FALSE) {
                    $contasOrd[$mes] = array($cnt);
                } else {
                    $contasOrd[$mes][] = $cnt;
                }
            }

            ksort($contasOrd);
            $contasOrd = array_reverse($contasOrd, TRUE);

            unset($contas);
        }

        // Lista de Grupos
        $grupos = mdGrupo::findBy(array(), array(), array('strTipo','strNome'));
    }
}
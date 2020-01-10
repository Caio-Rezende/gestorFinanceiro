<?php
namespace controllers;

use views\View;
use views\BreadCrumb;
use views\Menu as vwMenu;

/**
 * Description of Principal
 *
 * @author caiorezende
 */
class Main extends Controller implements Menu{
    const MAIN_OPT_CONTAS     = 'con';
    const MAIN_OPT_CALENDARIO = 'cal';
    const MAIN_OPT_GRUPOS     = 'gru';
    const MAIN_OPT_MAIN       = 'mai';
    const MAIN_OPT_RELATORIOS = 'rel';
    const MAIN_OPT_SEARCH     = 'sea';
    const MAIN_OPT_USUARIOS   = 'usu';

    public static $labels = array(
        self::MAIN_OPT_MAIN => array(
            'Nome'       => 'Home',
            'Controller' => 'Main',
            'Title'      => 'P&aacute;gina inicial de dicas.',
            'Page'       => 'main.php'
        ),
        self::MAIN_OPT_USUARIOS => array(
            'Nome'       => 'Usu&aacute;rios',
            'Controller' => 'ctUsuario',
            'Title'      => 'Edite ou adicione novos usu&aacute;rios',
            'Page'       => 'usuarios.php'
        ),
        self::MAIN_OPT_GRUPOS => array(
            'Nome'       => 'Grupos',
            'Controller' => 'ctGrupo',
            'Title'      => 'Edite ou adicione novos grupos',
            'Page'       => 'grupos.php'
        ),
        self::MAIN_OPT_RELATORIOS => array(
            'Nome'       => 'Relat&oacute;rios',
            'Controller' => 'Relatorio',
            'Title'      => 'Veja o relat&oacute;rio das contas cadastradas',
            'Page'       => 'relatorios.php'
        ),
        self::MAIN_OPT_CALENDARIO => array(
            'Nome'       => 'Calend&aacute;rio',
            'Controller' => 'Calendario',
            'Title'      => 'Veja suas contas cadastradas em um calend&aacute;rio',
            'Page'       => 'calendario.php'
        ),
        self::MAIN_OPT_CONTAS => array(
            'Nome'       => 'Contas',
            'Controller' => 'ctConta',
            'Title'      => 'Edite ou adicione novas contas',
            'Page'       => 'contas.php'
        ),
        self::MAIN_OPT_SEARCH => array(
            'Nome'       => 'Pesquisa',
            'Controller' => 'Search',
            'Title'      => 'Voc&ecirc; est&aacute; nos resultados da pesquisa realizada',
            'Page'       => 'search.php',
            'noPrint'    => true
        )
    );

    public function init() {
        vwMenu::setController($this);
        vwMenu::setName('gestorFinanceiro');
        foreach (self::$labels as $id => $opts) {
            if (isset($opts['noPrint']) === FALSE) {
                vwMenu::addMenus(
                    $id,
                    $opts['Nome'],
                    $opts['Title']
                );
            }
        }
        if (array_key_exists('opt', $_GET) === FALSE 
            || in_array($_GET['opt'], array_keys(self::$labels)) === FALSE) {
            $_GET['opt'] = self::MAIN_OPT_MAIN;
        }
        vwMenu::setActive($_GET['opt']);
        BreadCrumb::addPath(
            self::$labels[self::MAIN_OPT_MAIN]['Nome'],
            self::$labels[self::MAIN_OPT_MAIN]['Controller'],
            self::$labels[self::MAIN_OPT_MAIN]['Title']
        );

        if ($_GET['opt'] != self::MAIN_OPT_MAIN) {
            $content = self::$labels[$_GET['opt']]['Page'];
            BreadCrumb::addPath(
                self::$labels[$_GET['opt']]['Nome'],
                self::$labels[$_GET['opt']]['Controller'],
                self::$labels[$_GET['opt']]['Title']
            );
            $cnt = 'controllers\\'.self::$labels[$_GET['opt']]['Controller'];
            $cnt = new $cnt();
            $cnt->prepare();
        } else {
            $content = self::$labels[self::MAIN_OPT_MAIN]['Page'];
        }

        View::renderTemplate(
            'basic',
            array('content' => $content)
        );
    }

    public function prepare() {

    }
}
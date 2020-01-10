<?php
namespace views;


use controllers\Menu as ctMenu;
use controllers\Main;
use controllers\Login;

/**
 * Description of Menu
 *
 * @author caiorezende
 */
class Menu extends Component{
    private static $active     = '';
    private static $name       = '';
    private static $controller = '';
    private static $menus      = array();

    public static function setActive($active) {
        self::$active = $active;
    }

    public static function setName($name) {
        self::$name = $name;
    }

    public static function setController(ctMenu $controller) {
        $aux = get_class($controller);
        $aux = explode('\\', $aux);
        $aux = array_reverse($aux);

        self::$controller = $aux[0];
    }

    public static function addMenus($key, $opcao, $title = '') {
        self::$menus[$key] = array(
            'opcao' => $opcao,
            'title' => $title
        );
    }

    public function render($params = array()) {
        
        $sortTitle = function($a, $b){
            return $a['opcao'] > $b['opcao'];
        };
        
        uasort(self::$menus, $sortTitle);
        
        if (self::$name != '' && self::$controller != '') {
            ?>
            <div class="header">
                <div class="wrapper">
                    <div>
                        <h2><?=self::$name?></h2>
                    </div>
                    <?
                    if (count(self::$menus) > 0) {
                    ?>
                    <div>
                        <ul>
                            <li><h1>&nbsp;</h1></li>
                            <?
                            foreach (self::$menus as $key => $menu) {
                                $opcao = $menu['opcao'];
                                $title = $menu['title'];
                                $class = ($key == self::$active)?'class="active"':'';?>
                                <li <?=$class?> onclick="document.location.href='<?=URL?>/index.php?control=<?=self::$controller?>&opt=<?=$key?>'"
                                    title="<?=$title?>">
                                    <h1>
                                        <?=$opcao?>
                                    </h1>
                                </li>
                            <?
                            }
                            ?>
                        </ul>
                    </div>
                    <?
                    }
                    if (Login::isLogged()) {
                        $user = Login::getLoggedUser();
                    ?>
                    <div class="user">
                        <?=$user['strNome']?>
                        <a href="index.php?control=<?
                            echo Main::$labels[Main::MAIN_OPT_USUARIOS]['Controller'];
                            ?>&id=<?
                            echo $user['id'];
                            ?>" title="Editar usu&aacute;rio">editar</a>
                        <a href="index.php?control=Login&method=logout" title="Sair do sistema">sair</a>
                    </div>
                    <?
                    }
                    ?>
                </div>
            </div>
            <?
        }
    }
}
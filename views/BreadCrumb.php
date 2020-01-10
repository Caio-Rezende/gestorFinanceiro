<?php
namespace views;

/**
 * Description of BreadCrumb
 *
 * @author caiorezende
 */
class BreadCrumb extends Component {

    private static $paths = array();

    /**
     * Adiciona os caminhos que aparecerao no breadcrumb gerado
     * @param type $path
     * @param type $control
     * @param type $method
     * @param type $title
     */
    public static function addPath($path, $control = '', $title = '') {
        if ($control != '') {
            self::$paths[$path] = array(
                'control' => $control,
                'title'   => $title
            );
        } else {
            self::$paths[$path] = array();
        }
    }

    public function render($params = array()) {
        echo '<div id="breadcrumb" class="grid_8">';
        $breadcrumb = array();
        foreach (self::$paths as $path => $link) {
            if (is_array($link) === TRUE && count($link) == 2) {
                $url   = "href=\"index.php?control={$link['control']}\"";
                $title = $link['title'];
            } else {
                $url   = 'href="#"';
                $title = '';
            }
            $breadcrumb[] = "<a name='path' {$url} title='{$title}'>{$path}</a>";
        }
        echo implode('&nbsp;&raquo;&nbsp;', $breadcrumb);
        echo '</div>';
    }

}
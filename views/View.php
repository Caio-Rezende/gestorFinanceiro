<?php
namespace views;

use exceptions\InaccessibleFile;

/**
 * Description of View
 *
 * @author caiorezende
 */
class View {

    /**
     * Faz um include direto no arquivo a ser renderizado
     * O arquivo deve estar dentro de assets/pages/
     *
     * @param string $file
     * @throws InaccessibleFile
     */
    public static function render($file) {
        $file = PATH . DIRECTORY_SEPARATOR
                . 'assets' . DIRECTORY_SEPARATOR
                . 'pages' . DIRECTORY_SEPARATOR
                . $file;

        if (is_readable($file)) {
            include $file;
        } else {
            throw new InaccessibleFile($file);
        }
    }

    /**
     * Cria a chamada para o componente indicando os parametros
     *
     * @param type $cmpNome
     * @param array $params
     */
    public static function useComponent($cmpNome, array $params = array()) {
        if (count($params) > 0) {
            $aux = array();
            foreach($params as $key => $val) {
                $aux[] = $key . '=>' . $val;
            }
            $params = '|'.implode('|', $aux);
        } else {
            $params = '';
        }
        echo '{{'.$cmpNome.$params.'}}';
    }

    /**
     * Faz um echo do template trocando parametros sobre ele
     *
     * @param type $tpl
     * @param array $params
     * @param array $variaveis
     * @throws InaccessibleFile
     */
    public static function renderTemplate($tpl, array $params, array $vars = array()) {
        $tpl = PATH . DIRECTORY_SEPARATOR
                . 'assets' . DIRECTORY_SEPARATOR
                . 'templates' . DIRECTORY_SEPARATOR
                . $tpl . '.html';
        if (!is_readable($tpl)) {
            throw new InaccessibleFile($tpl);
        }
        $tpl = file_get_contents($tpl);

        foreach ($params as $key => $file) {
            ob_start();
            $file = PATH . DIRECTORY_SEPARATOR
                . 'assets' . DIRECTORY_SEPARATOR
                . 'pages' . DIRECTORY_SEPARATOR
                . $file;
            if (is_readable($file)) {
                include $file;
            } else {
                $file = PATH . DIRECTORY_SEPARATOR
                    . 'assets' . DIRECTORY_SEPARATOR
                    . 'templates' . DIRECTORY_SEPARATOR
                    . $file;
                if (is_readable($file)) {
                    echo $file;
                } else {
                    ob_end_clean();
                    throw new InaccessibleFile($file);
                }
            }
            $content = ob_get_clean();

            $tpl = str_replace('{{' . $key . '}}', $content, $tpl);
        }

        $components = array();
        preg_match_all('/\{\{(.*)\}\}/', $tpl, $components);

        foreach($components[1] as $component) {
            try {
                $paramsCmp      = explode('|', $component);
                $componentClass = $paramsCmp[0];
                unset($paramsCmp[0]);
                $paramsCmp      = array_merge($paramsCmp, array());

                /** @var views/Component $cmp */
                $cmp = new $componentClass();
                if ($cmp instanceof Component) {
                    $aux       = array();
                    foreach ($paramsCmp as $param) {
                        if (strpos($param, '=>') === FALSE ) {
                            $aux[] = $param;
                        } else {
                            $split = explode('=>', $param);
                            if (count($split) == 2) {
                                $aux[$split[0]] = $split[1];
                            } else {
                                $aux[] = $param;
                            }
                        }
                    }

                    ob_start();
                    $cmp->render($aux);
                    $content = ob_get_clean();
                    $tpl = str_replace('{{' . $component . '}}', $content, $tpl);
                }
            } catch (InaccessibleFile $e) {

            }
        }


        foreach ($vars as $key => $val) {
            $tpl = str_replace('{{' . $key . '}}', $val, $tpl);
        }

        $tpl = preg_replace('/\{\{(.)*\}\}/', '', $tpl);

        echo $tpl;
    }

    public static function mesDoAno($m) {
        switch ($m) {
            case 1:
                return 'Janeiro';
                break;
            case 2:
                return 'Fevereiro';
                break;
            case 3:
                return 'Março';
                break;
            case 4:
                return 'Abril';
                break;
            case 5:
                return 'Maio';
                break;
            case 6:
                return 'Junho';
                break;
            case 7:
                return 'Julho';
                break;
            case 8:
                return 'Agosto';
                break;
            case 9:
                return 'Setembro';
                break;
            case 10:
                return 'Outubro';
                break;
            case 11:
                return 'Novembro';
                break;
            case 12:
                return 'Dezembro';
                break;
        }
    }
}
<?php

namespace views;

/**
 * Description of Button
 *
 * @author caiorezende
 */
class Button extends Component{
    private static $buttons = array();

    /**
     * Adiciona um botao do tipo submit para salvar
     *
     * @param array $params
     * @return int index do array interno para renderizar o botao especificado
     * @throws \exceptions\WrongInvocation
     */
    public static function addSave(array $params) {
        if (self::check('form', $params) === FALSE) {
            throw new \exceptions\WrongInvocation('Faltando parametro form');
        }
        $other =
            ' onclick="'
            . "document.forms['{$params['form']}'].action = "
            . "document.forms['{$params['form']}'].action + '&method=save';"
            . '" class="positive"';

        self::$buttons[] = array(
            'name'  => 'Salvar',
            'type'  => 'submit',
            'other' => $other
        );
        return count(self::$buttons) - 1;
    }

    /**
     * Adiciona um botao do tipo submit para deletar
     *
     * @param array $params
     * @return int index do array interno para renderizar o botao especificado
     * @throws \exceptions\WrongInvocation
     */
    public static function addDelete(array $params) {
        if (self::check('form', $params) === FALSE) {
            throw new \exceptions\WrongInvocation('Faltando parametro form');
        }
        $other =
            ' onclick="'
            . "document.forms['{$params['form']}'].action = "
            . "document.forms['{$params['form']}'].action + '&method=delete';"
            . '" class="negative"';

        self::$buttons[] = array(
            'name'  => 'Excluir',
            'type'  => 'submit',
            'other' => $other
        );
        return count(self::$buttons) - 1;
    }

    /**
     * Adiciona um botao do tipo novo
     *
     * @param array $params
     * @return int index do botao adicionado para referenciar no render
     * @throws \exceptions\WrongInvocation
     */
    public static function addNew(array $params) {
        if (self::check('form', $params) === FALSE) {
            throw new \exceptions\WrongInvocation('Faltando parametro form');
        }
        $other =
                ' onclick="'
                . "document.location.href = "
                . "document.forms['{$params['form']}'].action";
        if (self::check('action', $params)) {
            $other .= "+ '{$params['action']}'";
        }
        $other .= ';"';
        self::$buttons[] = array(
            'name'  => 'Novo',
            'type'  => 'button',
            'other' => $other
        );
        return count(self::$buttons) - 1;
    }

    public function render($params = array()) {
        foreach ($params as $index) {
            if (count(self::$buttons) > 0 && array_key_exists($index, self::$buttons)) {
                $btn = self::$buttons[$index];
            ?>
                <button type="<?=$btn['type']?>"<?=$btn['other']?>>
                    <?=$btn['name']?>
                </button>
            <?php
                self::$buttons[$index] = null;
            }
        }
    }

    private static function check($attr, $arr) {
        return (isset($arr[$attr]) && $arr[$attr] != '');
    }
}
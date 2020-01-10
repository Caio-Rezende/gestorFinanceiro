<?php
namespace views;

/**
 * Description of Errors
 *
 * @author caiorezende
 */
class Errors extends Component{
    protected static $errors = array();
    protected static $id = '';
    
    public static function setId($id){
        self::$id = '+\\"&id=' . $id . '\\"';
    }

    public static function addError($msg){
        self::$errors[] = $msg;
    }

    public function render($params = array()){
        if (count(self::$errors) > 0) {
        ?>
        <script type="text/javascript">
            $('.errorSpace').html("<h1>Erros encontrados:</h1><ul><li><?=implode('</li><li>', self::$errors)?></li></ul>"+
                "<br><button onclick='document.location.href=document.location.href<?=self::$id?>;'>Voltar</button>"
            );
        </script>
        <?
        }
    }
}
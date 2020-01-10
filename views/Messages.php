<?php
namespace views;

/**
 * Description of Messages
 *
 * @author caiorezende
 */
class Messages extends Component{
    protected static $messages = array();

    public static function addMessage($msg){
        self::$messages[] = $msg;
    }

    public function render($params = array()){
        if (count(self::$messages) > 0) {
        ?>
        <script type="text/javascript">
            $('.messageSpace').html("<h1>Mensagens:</h1><ul><li><?=implode('</li><li>', self::$messages)?></li></ul>");
        </script>
        <?
        }
    }
}
<?php
namespace mappers;

use core\DB;
use controllers\ctUsuario;
use models\Model;
use models\mdConta;
use models\mdGrupo;
use exceptions\FailedDeleteValidation;
/**
 * Description of Usuario
 *
 * @author caiorezende
 */
class mpUsuario extends Mapper{
    public static $tableRelacao = 'gf_relacoes_usuarios';
    private static $directSaving = false;

    public function __construct() {
        $this->table  = 'gf_usuarios';
        $this->nick   = 'gu';
        $this->fields = array(
            'str_nome'  => array(),
            'str_login' => array(),
            'pas_senha' => array()
        );

        $this->condition = ctUsuario::makeCondition('id');
    }

    public static function makeCondition($alias) {
        return " {$alias} = " . $_SESSION['USER']['id'] . ' OR EXISTS ( '
            . ' SELECT * FROM ' . self::$tableRelacao . ' WHERE '
                . ' ( id_usuario1 = ' . $_SESSION['USER']['id']
                    . ' OR id_usuario2 = ' . $_SESSION['USER']['id']
                . ' ) '
            . ' AND '
                . " ( id_usuario1 = {$alias}  OR id_usuario2 = {$alias} )"
            . ') ';
    }

    public function save (Model $obj)
    {
        if ($obj->getId() > 0 || self::$directSaving) {
            parent::save($obj);
        } else {
            parent::save($obj);
            DB::get()->exec(
                ' INSERT INTO ' . self::$tableRelacao . ' (id_usuario1, id_usuario2) VALUES ( '
                . $_SESSION['USER']['id'] . ', ' . $obj->getId()
                . ' )'
            );
        }
    }

    public function directSave (Model $obj)
    {
        self::$directSaving = true;
        parent::save($obj);
    }

    public function delete (Model $obj)
    {
        $msg = array();
        $resGrupo = mdGrupo::findBy(array('idUsuario' => $obj->getId()));
        if (count($resGrupo) > 0) {
            $msg[] = 'O usu&aacute;rio n&atilde;o pode ser exclu&iacute;do, pois tem Grupos cadastrados em seu nome!';
        }

        $resConta = mdConta::findBy(array('idUsuario' => $obj->getId()));
        if (count($resConta) > 0) {
            $msg[] = 'O usu&aacute;rio n&atilde;o pode ser exclu&iacute;do, pois tem Contas cadastradas em seu nome!';
        }

        if (count($msg) > 0) {
            throw new FailedDeleteValidation($msg);
        }

        $sql   =
            'DELETE FROM ' . self::$tableRelacao .  ' WHERE '
            . 'id_usuario1 = ' . self::BINDMARK . 'id_usuario1'
            . ' OR id_usuario2 = ' . self::BINDMARK . 'id_usuario2';
        $marks = array(
            'id_usuario1' => $obj->getId(),
            'id_usuario2' => $obj->getId(),
        );
        DB::get()->exec($sql, $marks);
        parent::delete($obj);
    }
}
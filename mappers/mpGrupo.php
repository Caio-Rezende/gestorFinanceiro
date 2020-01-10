<?php
namespace mappers;

use controllers\ctUsuario;
use exceptions\FailedDeleteValidation;
use models\Model;
use models\mdConta;

/**
 * Description of Grupo
 *
 * @author caiorezende
 */
class mpGrupo extends Mapper{
    public function __construct() {
        $this->table  = 'gf_grupos';
        $this->nick   = 'gu';
        $this->fields = array(
            'str_nome'  => array(),
            'str_tipo' => array(),
            'id_usuario' => array()
        );

        $this->condition = ctUsuario::makeCondition('id_usuario');
    }
    public function delete (Model $obj)
    {
        $msg = array();

        $resConta = mdConta::findBy(array('idGrupo' => $obj->getId()));
        if (count($resConta) > 0) {
            $msg[] = 'O grupo n&atilde;o pode ser exclu&iacute;do, pois tem Contas cadastradas nele!';
        }

        if (count($msg) > 0) {
            throw new FailedDeleteValidation($msg);
        }

        parent::delete($obj);
    }
}
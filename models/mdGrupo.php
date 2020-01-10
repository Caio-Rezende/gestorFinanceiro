<?php
namespace models;

use mappers\mpGrupo;
use exceptions\FailedSaveValidation;
use views\Errors;

/**
 * Description of Grupo
 *
 * @author caiorezende
 */
class mdGrupo extends Model{

    public function __construct() {
        $this->mapper = new mpGrupo();
    }

    const TIPO_ENTRADA = 'e';
    const TIPO_SAIDA   = 's';

    protected $strNome;
    protected $strTipo;
    protected $idUsuario;

    public function onSave(Model $obj) {
        $msg = array();
        if ($this->strNome == '') {
            $msg[] = 'O grupo deve ter um nome!';
        }
        if ($this->strTipo == '') {
            $msg[] = 'O tipo do grupo deve ser informado!';
        }
        if ($this->idUsuario == '') {
            $msg[] = 'O usu&aacute;rio de cadastro deve ser informado!';
        }
        if (count($msg) > 0) {
            Errors::setId($this->id);
            throw new FailedSaveValidation($msg);
        }
    }
}
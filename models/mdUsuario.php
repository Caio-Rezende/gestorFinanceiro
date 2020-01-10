<?php
namespace models;

use mappers\mpUsuario;
use exceptions\FailedSaveValidation;
use views\Errors;

/**
 * Description of Usuario
 *
 * @author caiorezende
 */
class mdUsuario extends Model{

    public function __construct() {
        $this->mapper = new mpUsuario();
    }

    protected $strNome;
    protected $strLogin;
    protected $pasSenha;

    public function setPasSenha($val) {
        if ($val == '') {
            $this->pasSenha = null;
        } else {
            $this->pasSenha = $val;
        }
    }

    public function onSave(Model $obj) {
        $msg = array();
        if ($this->strNome == '') {
            $msg[] = 'O usu&aacute;rio deve ter um nome!';
        }
        if ($this->strLogin == '') {
            $msg[] = 'O usu&aacute;rio deve ter um nome de usu&aacute;rio!';
        }
        if ($this->id == '' && $this->pasSenha == '') {
            $msg[] = 'O usu&aacute;rio deve ter uma senha!';
        }

        $checkNome = mdUsuario::findBy(array(
            'strLogin' => $this->strLogin,
        ));

        if (count($checkNome) == 1 && $this->id != $checkNome[0]['id']) {
            $msg[] = 'O nome de usu&aacute;rio informado j&aacute; existe, escolha outro!';
        }

        if (count($msg) > 0) {
            Errors::setId($this->id);
            throw new FailedSaveValidation($msg);
        }
    }

}
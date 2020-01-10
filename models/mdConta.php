<?php
namespace models;

use mappers\mpConta;
use exceptions\FailedSaveValidation;
use views\Errors;

/**
 * Description of Conta
 *
 * @author caiorezende
 */
class mdConta extends Model{

    public function __construct() {
        $this->mapper = new mpConta();
    }

    protected $idGrupo;
    protected $strNome;
    protected $numValor;
    protected $dteInicial;
    protected $intParcelas;
    protected $intParcelaAtual;
    protected $dteCompra;
    protected $idUsuario;
    protected $bolPaga;
    protected $idRelacao;

    public function getDteInicial(){
        if ($this->dteInicial != '') {
            return date('Y-m-d', strtotime($this->dteInicial));
        } else {
            return '';
        }
    }

    public function getDteCompra(){
        if ($this->dteCompra != '') {
            return date('Y-m-d', strtotime($this->dteCompra));
        } else {
            return '';
        }
    }
    
    public function setBolPaga($val){
        if ($val != 1) {
            $this->bolPaga = 0;
        } else {
            $this->bolPaga = 1;
        }
    }

    public function onSave(Model $obj) {
        $msg = array();
        if ($this->idGrupo == '') {
            $msg[] = 'A conta deve ter um grupo associado!';
        }
        if ($this->strNome == '') {
            $msg[] = 'A conta deve ter um descritivo!';
        }
        if ($this->numValor == '') {
            $msg[] = 'A conta deve ter um valor!';
        }
        if ($this->dteInicial == '') {
            $msg[] = 'A conta deve ter uma data inicial de pagamento!';
        }
        if ($this->id == '' && $this->idUsuario == '') {
            $msg[] = 'A conta deve ter um usu&aacute;rio de cadastro!';
        }
        if ($this->idUsuario == '') {
            $msg[] = 'O usu&aacute;rio de cadastro deve ser informado!';
        }

        if (count($msg) > 0) {
            if ($this->id != '') {
                Errors::setId($this->id);
            }
            throw new FailedSaveValidation($msg);
        }
    }

}
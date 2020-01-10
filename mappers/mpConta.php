<?php
namespace mappers;

use controllers\ctUsuario;
use models\Model;
use models\mdConta;
use core\DB;
use views\Errors;
/**
 * Description of Conta
 *
 * @author caiorezende
 */
class mpConta extends Mapper{
    public function __construct() {
        $this->table  = 'gf_contas';
        $this->nick   = 'gc';
        $this->fields = array(
            'id_grupo'  => array(),
            'str_nome' => array(),
            'num_valor' => array(),
            'dte_inicial' => array(),
            'dte_compra' => array(),
            'id_usuario' => array(),
            'bol_paga' => array(),
            'id_relacao' => array()
        );

        $this->condition = ctUsuario::makeCondition('id_usuario');
    }
    
    public function load(Model $obj, $id) {
        parent::load($obj, $id);
        
        $sql = 'SELECT ('
                . ' SELECT count(id) FROM gf_contas '
                . ' WHERE id_relacao = ' . self::BINDMARK . 'idRelacao1 '
            .') as int_parcelas,'
            .'('
                . ' SELECT count(id) FROM gf_contas '
                . ' WHERE id_relacao = ' . self::BINDMARK . 'idRelacao2 '
                    . ' AND dte_inicial <= ' . self::BINDMARK . 'dte_inicial'
            .') as int_parcela_atual';
        
        $marks = array(
            'idRelacao1' => $obj->getIdRelacao(),
            'idRelacao2' => $obj->getIdRelacao(),
            'dte_inicial' => $obj->getDteInicial()
        );
        
        $result = DB::get()->exec($sql, $marks);

        self::fieldArrayToModel($obj, $result[0]);
        return $obj;
    }
    
    public function save(Model $obj) {
        if ($obj->getId()!='') {
            $model = mdConta::find($obj->getId());
            $obj->setIdRelacao($model->getIdRelacao());
        }
        
        parent::save($obj);
        
        if (isset($model) === false) {
            $model = mdConta::find($obj->getId());
        }
        
        if ($obj->getIdRelacao() == '') {
            $sql = 'UPDATE ' . $this->table 
                . ' SET '
                    . ' id_relacao = id '
                . ' WHERE id = ' . self::BINDMARK . 'id';
            $marks = array(
                'id' => $obj->getId()
            );
            DB::get()->exec($sql, $marks);
            $obj->setIdRelacao($obj->getId());
            $model->setIdRelacao($model->getId());
        }
        
        $parcelasNov = $obj->getIntParcelas() - $obj->getIntParcelaAtual();
        $parcelasAnt = $model->getIntParcelas() - $model->getIntParcelaAtual();
        if ($parcelasAnt != $parcelasNov) {
            if ($parcelasAnt > 0) {
                $sql = 'DELETE FROM ' . $this->table 
                    . ' WHERE id_relacao = ' . self::BINDMARK . 'idRelacao'
                    . ' AND id > ' . self::BINDMARK . 'id';
                $marks = array(
                    'idRelacao' => $model->getIdRelacao(),
                    'id' => $model->getId()
                );
                DB::get()->exec($sql, $marks);
            }
            if ($parcelasNov > 0) {
                if ($obj->getId() != $obj->getIdRelacao() && $obj->getIdRelacao() != ''){
                    $relacao = $obj->getIdRelacao();
                } else {
                    $relacao = $obj->getId();
                }

                $contaRel = clone $obj;
                $contaRel->setId(null);
                $contaRel->setIdRelacao($relacao);
                $contaRel->setIntParcelas(0);
                $time = strtotime($obj->getDteInicial());
                for($i = 1; $i <= $obj->getIntParcelas(); $i++) {
                    $contaRel->setId(null);
                    $dteInicial = mktime(0, 0, 0, date('n', $time) + $i, date('j', $time), date('Y', $time));
                    $contaRel->setDteInicial(date('Y-m-d', $dteInicial));
                    $contaRel->getMapper()->save($contaRel);
                }
            }
        }
        return $obj;
    }
    
    public function delete(Model $obj) {
        $model = mdConta::find($obj->getId());
        if ($model->getIntParcelas() - $model->getIntParcelaAtual() > 0) {
            Errors::setId($obj->getId());
            throw new \exceptions\FailedValidation(
                array(
                    'Esta Conta est&aacute; conectada a Contas futuras. Ou exclua primeiro as &uacute;ltimas parcelas ou v&aacute; em uma parcela anterior e reduza o n&uacute;mero de parcelas.'
                )
            );
        }
        
        $sql = 'UPDATE ' . $this->table 
            . ' SET '
                . ' id_relacao = NULL '
            . ' WHERE id = ' . self::BINDMARK . 'id';
        $marks = array(
            'id' => $obj->getId()
        );
        DB::get()->exec($sql, $marks);
        return parent::delete($obj);
    }
    
    public function select($where = '', array $marks = array(), array $fields = array(), array $order = array()) {
        if (count($fields) == 0) {
            $fields = array(
                '*',
                '(select str_tipo from gf_grupos g where g.id = gc.id_grupo) as str_tipo',
                '('
                    . ' select count(id) from gf_contas c '
                    . ' where c.id_relacao = gc.id_relacao '
                . ') as int_parcelas',
                '('
                    . ' select count(id) from gf_contas c '
                    . ' where c.id_relacao = gc.id_relacao '
                        . ' and c.dte_inicial <= gc.dte_inicial'
                . ') as int_parcela_atual'
            );
        }
        return parent::select($where, $marks, $fields, $order);
    }
    
    public function alteraBolPaga($id) {
        $sql   = "UPDATE {$this->table} "
                . 'SET bol_paga = not bol_paga '
                . 'WHERE id = ' . self::BINDMARK . 'id';
        $marks = array('id' => $id);
        DB::get()->exec($sql, $marks);
    }
}
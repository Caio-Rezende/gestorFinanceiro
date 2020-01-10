<?php
namespace models;

use mappers\Mapper;

/**
 * Description of Model
 *
 * @author caiorezende
 */
abstract class Model {
    /** @var \mappers\Mapper $mapper */
    protected $mapper;

    protected $id;

    public function getMapper() {
        return $this->mapper;
    }

    /**
     * Funçao a ser chamada antes de salvar no banco
     *
     * o objeto passado como parametro e resultado de um find pelo id
     * com o objetivo de comparar valores ja existentes para o mesmo objeto
     *
     * @param models\Model $obj
     * @throws exceptions\FailedSaveValidation
     */
    abstract public function onSave(Model $obj);

    /**
     * Instancia o objeto invocado e procura ele pela primary key indicada
     * Retornando o proprio objeto
     *
     * @param type $id
     * @return \models\Model
     */
    public static function find($id) {
        $objModel = new static();

        return $objModel->mapper->load($objModel, $id);
    }

    /**
     * Instancia o objeto invocado e procura pelos parametros informados
     * Retornando o resultado encontrado que pode ser de campos escolhidos
     * e ordenado
     *
     * @param array $params
     * @param array $fields
     * @param array $order
     * @param bool $untouch
     * @return \models\Model
     */
    public static function findBy(array $params = array(), array $fields = array(), array $order = array(), $untouch = FALSE) {
        $objModel = new static();

        $where = array();
        $marks = array();

        if($untouch === FALSE){
            foreach ($params as $param => $val) {
                if (strpos($param, 'str') !== FALSE) {
                    $where[] = Mapper::attributeToField($param)
                        . ' LIKE ' . Mapper::BINDMARK . $param;
                    $val = "%{$val}%";
                } else {
                    $where[] = Mapper::attributeToField($param)
                        . ' = ' . Mapper::BINDMARK . $param;
                }
                $marks[$param] = $val;
            }
            $where = implode(' AND ', $where);
        } else {
            if (isset($params['where'])) {
                $where = $params['where'];
            }
            if (isset($params['marks'])) {
                $marks = $params['marks'];
            }
        }

        if (count($fields) > 0) {
            $fields = array_map(array('mappers\Mapper', 'attributeToField'), $fields);
        }
        if (count($order) > 0) {
            $order = array_map(array('mappers\Mapper', 'attributeToField'), $order);
        }

        return $objModel->mapper->select($where, $marks, $fields, $order);
    }

    public function __call($name, $arguments) {
        $type = substr($name, 0, 3);
        $name = strtolower(substr($name, 3, 1)) . substr($name, 4);
        switch ($type) {
            case 'set':
                if ($this->propertieExists($name) === TRUE) {
                    if (count($arguments) == 1) {
                        $this->{$name} = $arguments[0];
                        return $this;
                    } else {
                        throw  new \exceptions\WrongInvocation("Method set MUST have 1 argument.");
                    }
                }
                break;
            case 'get':
                if ($this->propertieExists($name) === TRUE) {
                    if (count($arguments) == 0) {
                        return $this->{$name};
                    } else {
                        throw  new \exceptions\WrongInvocation("Method get MUST have 0 arguments.");
                    }
                }
                break;
            default:
                throw  new \exceptions\InexistentMethod(get_called_class(), $name);
                break;
        }
    }

    protected function propertieExists($propertie) {
        if (property_exists($this, $propertie) === TRUE) {
            return TRUE;
        }
        throw new \exceptions\InexistentPropertie(get_called_class(), $propertie);
    }

    public function toArray() {
        $result = get_object_vars($this);
        unset($result['mapper']);

        return $result;
    }

    public function setArray(array $attributes) {
        foreach ($attributes as $attr => $val) {
            $set = 'set' . ucfirst($attr);
            $this->{$set}($val);
        }
    }
}
<?php

namespace mappers;

use controllers\Login;
use models\Model;
use core\DB;
use \PDO;
use exceptions\RowNotFound;

/**
 * Description of Mapper
 *
 * @author caiorezende
 */
abstract class Mapper
{

    protected $table     = '';
    protected $nick      = '';
    protected $fields    = array();
    protected $condition = '';

    const BINDMARK = ':';

    /**
     * Carrega um objeto do banco e seta seus valores
     *
     * @param \models\Model $obj
     * @param type $id
     * @throws \exceptions\RowNotFound
     */
    public function load (Model $obj, $id)
    {
        $sql   = "SELECT * FROM {$this->table} WHERE id = " . self::BINDMARK . 'id';
        $marks = array('id' => $id);

        if ($this->condition != '') {
            $sql .= " AND ({$this->condition})";
        }

        $result = DB::get()->exec($sql, $marks);

        if (count($result) == 0) {
            throw new \exceptions\RowNotFound($this->table, $id);
        }

        self::fieldArrayToModel($obj, $result[0]);

        return $obj;
    }

    /**
     * salva um objeto, seja insert ou update
     * no caso de inserte registra o id no objeto recem salvo
     *
     * @param \models\Model $obj
     * @return \models\Model
     */
    public function save (Model $obj)
    {
        $class = get_class($obj);
        try {
            $model = $class::find($obj->getId());
        } catch (RowNotFound $e) {
            $model = new $class();
        }
        $obj->onSave($model);

        $marks = array();

        foreach (array_keys($this->fields) as $field) {
            if ($field == 'id') continue;

            $get = 'get' . ucfirst(self::fieldToAttribute($field));
            $val = $obj->{$get}();

            if ($val === null || ($model->getId() != '' && $model->{$get}() == $val)) continue;

            $marks[$field] = $val;
        }

        if (count($marks) > 0) {
            if ($obj->getId() == '') {
                $sql = "INSERT INTO {$this->table} (" . implode(', ', array_keys($marks))
                    . ') VALUES (' . self::BINDMARK . implode(', ' . self::BINDMARK, array_keys($marks))
                    . ')';
            } else {
                $sql    = "UPDATE {$this->table} SET ";
                $fields = array();
                foreach (array_keys($marks) as $field) {
                    $fields[] = $field . ' = ' . self::BINDMARK . $field;
                }
                $sql .= implode(', ', $fields);
                $sql .= ' WHERE id = ' . self::BINDMARK . 'id';
                $marks['id'] = $obj->getId();
            }

            DB::get()->exec($sql, $marks);
            if ($obj->getId() == '') {
                $obj->setId(DB::get()->lastInsertId());
            }
        }

        return $obj;
    }

    /**
     * Deleta um objeto caso ele tenha um id setado
     *
     * @param \models\Model $obj
     * @return boolean
     */
    public function delete (Model $obj)
    {
        if ($obj->getId() == "") return false;

        $sql   = "DELETE FROM {$this->table} WHERE id = " . self::BINDMARK . 'id';
        $marks = array('id' => $obj->getId());

        DB::get()->exec($sql, $marks);

        return true;
    }

    /**
     * Carrega objetos do banco dado parametros e retorna o resultado
     *
     * @param type $where
     * @param array $marks
     * @param array $fields
     * @param array $order
     * @return type
     */
    public function select ($where = '', array $marks = array(), array $fields = array(), array $order = array())
    {
        if ($this->condition != '') {
            $where = "({$this->condition})" . ($where != '' ? " AND ( $where )" : '');
        }

        $sql = 'SELECT '
            . (count($fields) > 0 ? implode(', ', $fields) : '*')
            . " FROM {$this->table} {$this->nick}"
            . ($where != '' ? " WHERE {$where} " : '')
            . (count($order) > 0 ? ' ORDER BY ' . implode(', ', $order) : '');

        $result = DB::get()->exec($sql, $marks);

        foreach ($result as &$row) {
            $keys = array_keys($row);
            foreach ($keys as $field) {
                $row[self::fieldToAttribute($field)] = $row[$field];
                if ($field != 'id') {
                    unset($row[$field]);
                }
            }
        }

        return $result;
    }

    /**
     * Seta os valores do array no Model usando a funçao set
     *
     * @param \models\Model $obj
     * @param array $fields
     */
    public static function fieldArrayToModel (Model $obj, array $fields)
    {
        foreach ($fields as $field => $val) {
            $set = 'set' . ucfirst(self::fieldToAttribute($field));
            $obj->{$set}($val);
        }
    }

    /**
     * Transforma um campo de banco em atributo de objeto
     * str_login vira strLogin
     * Logica envolve separar pelos _ e capitalizar proxima letra
     *
     * @param string $field
     * @return string
     */
    public static function fieldToAttribute ($field)
    {
        $field = explode('_', $field);
        $attr  = array();
        foreach ($field as $part) {
            $attr[] = ucfirst($part);
        }
        $attr[0] = strtolower($attr[0]);
        $attr    = implode('', $attr);

        return $attr;
    }

    public static function attributeToField ($attr)
    {
        $field = preg_replace('/(\p{Lu})/', '_$1', $attr);
        $field = strtolower($field);

        return $field;
    }

    /**
     * Devolve o tipo de parametro PDO equivalente para bind em statement
     *
     * @param string $field
     * @param mixed $field
     * @return PDO_PARAM
     */
    public static function fieldPdoType ($field, $val)
    {
        if ($val === NULL) {
            return PDO::PARAM_NULL;
        }
        if ($field == 'id') {
            return PDO::PARAM_INT;
        }
        switch (substr($field, 0, 3)) {
            case 'bol':
            //vazando
            case 'num':
            //vazando
            case 'int':
                return PDO::PARAM_INT;
                break;
            case 'str':
            //vazando
            case 'pas':
            //vazando
            default:
                return PDO::PARAM_STR;
                break;
        }
    }

    /**
     * Dado um atributo, procura formata-lo previamente ao envio para o banco
     *
     * @param string $attr
     * @param mixed $val
     * @return mixed
     */
    public static function attributeValueToFieldValue ($attr, &$val)
    {
        if ($val === '' || $val === null) {
            $val = NULL;
        } else {
            if ($attr == 'id') {
                $val = (int) $val;
            }
            switch (substr($attr, 0, 3)) {
                case 'pas':
                    $val = Login::encrypt($val);
                    break;
                case 'str':
                //vazando
                case 'num':
                //vazando
                case 'int':
                //vazando
                case 'bol':
                //vazando
                default:
                    break;
            }
        }

        return $val;
    }

    /**
     * Dado um atributo, procura formata-lo previamente ao envio para o banco
     *
     * @param string $field
     * @param mixed $val
     * @return mixed
     */
    public static function fieldValueToAttributeValue ($field, &$val)
    {
        if ($val == '') {
            $val = '';
        }

        return $val;
    }
}
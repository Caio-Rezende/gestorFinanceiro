<?php
namespace core;

use mappers\Mapper;
use \PDO;
use \PDOException;

/**
 * Description of Core
 *
 * @author caiorezende
 */
class DB extends Core{
    /** @var $pdo \PDO */
    private static $pdo;
    private static $db;
    private static $activeTrans;

    private function __construct() {
        $this->loadConfs();

        if (!isset($_ENV['OPENSHIFT_MYSQL_DB_HOST']) || $_ENV['OPENSHIFT_MYSQL_DB_HOST'] == '') {
            $_ENV['OPENSHIFT_MYSQL_DB_HOST'] = $this->confs['host'];
        }
        if (!isset($_ENV['OPENSHIFT_MYSQL_DB_PORT']) || $_ENV['OPENSHIFT_MYSQL_DB_PORT'] == '') {
            $_ENV['OPENSHIFT_MYSQL_DB_PORT'] = $this->confs['port'];
        }

        $dns = $this->confs['engine']
                . ':dbname=' . $this->confs['database']
                . ';host=' . $_ENV['OPENSHIFT_MYSQL_DB_HOST']
                . ';port=' . $_ENV['OPENSHIFT_MYSQL_DB_PORT']
                . ';charset=UTF8';
        self::$pdo = new \PDO($dns, $this->confs['user'], $this->confs['pass'], array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ));
    }

    /**
     * Pega instancia do DB, que utiliza PDO
     *
     * @return \PDO
     */
    public static function get(){
        if (self::$db === NULL) {
            self::$db = new DB();
        }
        return self::$db;
    }

    /**
     * Executa uma sql com marcadores
     *
     * @param string $sql
     * @param array $marks
     * @return type
     * @throws \exceptions\ProblematicQuery
     */
    public function exec($sql, array $marks = array()) {
        $sth = $this->prepare($sql);

        foreach ($marks as $mark => $val) {
            $sth->bindValue(
                Mapper::BINDMARK . $mark,
                Mapper::attributeValueToFieldValue($mark, $val),
                Mapper::fieldPdoType($mark, $val)
            );
        }

        if ($sth->execute() === TRUE) {
            $result = array();
            try {
                $result = $sth->fetchAll(PDO::FETCH_ASSOC);
                foreach ($result as &$row) {
                    foreach ($row as $field => $val) {
                        $row[$field] = Mapper::fieldValueToAttributeValue($field, $val);
                    }
                }
            } catch (PDOException $e) {

            }
            return $result;
        } else {
            throw new \exceptions\ProblematicQuery($sth);
        }
    }

    public function __call($name, $arguments) {
        return call_user_func_array(array(self::$pdo, $name), $arguments);
    }

    public function beginTransaction() {
        if (self::$activeTrans == 0) {
            self::$pdo->beginTransaction();
        }
        self::$activeTrans++;
        return true;
    }

    public function commit() {
        self::$activeTrans--;
        if (self::$activeTrans == 0) {
            self::$pdo->commit();
        }
        return true;
    }
}
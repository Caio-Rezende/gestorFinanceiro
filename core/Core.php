<?php
namespace core;

/**
 * Description of Core
 *
 * @author caiorezende
 */
abstract class Core {
    protected $confs = array();

    public function loadConfs() {
        if (count ($this->confs) == 0) {
            $name = get_called_class();
            $name = explode('\\', $name);
            $name = array_reverse($name);
            $name = strtolower($name[0]);
            $name = PATH . DIRECTORY_SEPARATOR
                    . 'configs' . DIRECTORY_SEPARATOR
                    . $name . '.ini';
            if (is_readable($name)) {
                $confs = parse_ini_file($name, true);
                if (LOCALHOST === true) {
                    $section = 'localhost';
                } else {
                    $section = 'osrh';
                }
                $this->confs = $confs[$section];
            }
        }
    }
}
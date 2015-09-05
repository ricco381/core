<?php namespace app\core;

class Model
{
    static private $mysqli;
    public $id;
    public $sql = ''; //Строка запроса
    public $result = []; //Результат запроса из базы данных

   function __construct()
   {
       @self::$mysqli = new \Mysqli(Config::$config['db']['host'], Config::$config['db']['user'], Config::$config['db']['password'], Config::$config['db']['db']);

       @self::$mysqli->set_charset('utf8');

       if (self::$mysqli->connect_errno) {
           exit('Ошибка соединения с базой данных: ' . self::$mysqli->connect_error);
       }
   }

    public function __set($index, $value)
    {
        $this->$index = $value;
    }
    
    /**
     * Метод для выборки из базы данных
     * @param $sql Запрос
     * @param $data Данные для запроса
     */
    public function query($sql, $data = [], $bind = true, $array = true)
    {
        /**
         * Массив данных для возврата
         */
        $fields = [];

        if ($data) {
            $stmt = self::$mysqli->prepare($sql) or die(self::$mysqli->error);

            call_user_func_array(array($stmt, 'bind_param'), $this->bind_param($data));

            $stmt->execute();

            if ($bind) {
                $result = $stmt->result_metadata();

                while ($field = $result->fetch_field()) {
                    $params[] =  &$row[$field->name];
                    //$this->table = $field->table;
                }

                call_user_func_array(array($stmt, 'bind_result'), $params);

                while ($stmt->fetch()) {
                    foreach ($row as $key => $val) {
                        $temp[$key] = $val;
                        $this->$key = $val;
                    }
                    if ($array)
                        $fields[] = $temp;
                    else
                        $fields = $temp;
                }
            }
            $stmt->close();
        } else {
            $res = self::$mysqli->query($sql) or die(self::$mysqli->error);

            if (is_object($res)) {
                //$this->table = $res->fetch_field();

                while ($row = $res->fetch_assoc()) {
                    foreach ($row as $key => $val) {
                        $temp[$key] = $val;
                        $this->$key = $val;
                    }
                    if ($array)
                        $fields[] = $temp;
                    else
                        $fields = $temp;
                }
            }

        }
        return $fields;
    }

    /**
     * Подготовка параметров для запроса
     * @param $data
     * @return array|bool
     */
    private function bind_param($data)
    {
        $type = "";

        foreach ($data as $value) {
            switch(gettype($value)) {
                case 'string' :
                    $type .= 's'; break;
                case 'integer' :
                    $type .= 'i'; break;
                case 'double' :
                    $type .= 'd'; break;
                default:
                    return false;
            }
        }

        $param = [];
        $param = [$type];

        foreach ($data as $key => $value) {
            $param[] = &$data[$key];
        }

        return $param;
    }

    public function insert_id()
    {
        return self::$mysqli->insert_id;
    }

    public function load($class)
    {
        $mapClass = include PATH . "config/class_map.php";

        $nameClass = '';

        foreach ($mapClass as $path) {
            if (class_exists($path . ucfirst($class))) {
                $nameClass =  $path . ucfirst($class);
                break;
            }
        }

        if (!class_exists($nameClass))
            throw new \Exception($nameClass);

       return $this->$nameClass = new $nameClass;
    }
}
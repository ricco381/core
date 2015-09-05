<?php namespace app\helpers;

class Session
{
    /**
     * @param $name Имя поля в сесси
     * @return mixed
     *
     * Получение данных из сесси
     */
    public function getName($name)
    {
        if (!empty($_SESSION)) {
            foreach ($_SESSION as $key => $val) {
                $this->$key = filter_var($val, FILTER_SANITIZE_STRIPPED);
            }
        }
        return $this->$name;
    }

    /**
     * @param $data массив данных
     * @return bool
     *
     * Задаем новые значения в ссесию
     */
    public function set($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $_SESSION[$key] = filter_var($val, FILTER_SANITIZE_STRIPPED);
            }
            return true;
        }
    }

    public function getAll()
    {
        return $_SESSION;
    }

    public function clear()
    {
        if (isset($_SESSION)) {
            foreach ($_SESSION as $key => $val) {
                unset($_SESSION[$key]);
            }
        }
    }
}
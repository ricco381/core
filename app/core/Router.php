<?php namespace app\core;

class Router
{
    private $rules = []; //Правила для роутера
    private $url = []; //Адресная строка

    /**
     * Добавление правил для роутинга
     *
     * @param array $rules
     */
    public function __construct($rule)
    {
        foreach ($rule as $url => $param) {
            $this->rules[$url] = $param;
            $this->rules[$url]['url'] = explode('/', trim($url, '/'));
        }
    }

    /**
     * @return string
     */
    private function breakUrl()
    {
        $this->url = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
    }

    /**
     * Провека на совпадение адресной строки и массива правил
     */
    private function compareUrl()
    {;

        foreach ($this->rules as $param) {
            if (count($this->url) == count($param['url']))
            foreach ($param['url'] as $key => $val) {
                echo $val;
            }
        }

    }


    /**
     * Запус роутера
     *
     * @return bool
     */
    public function run()
    {
        /**
         * проверка существуют ли правила
         */
        if (empty($this->rules)) {
            return false;
        }

        /**
         * Получаем массив адресной строки
         */
        $this->breakUrl();

        /**
         * Проверяем массив $rules и $url на совпадение
         */
        if (!$this->compareUrl()) {
            //return false;
        }

    }
}
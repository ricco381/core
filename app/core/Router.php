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
    public function addRules($rules = [])
    {
        $this->rules[] = $rules;

        return $this;
    }

    /**
     * Разбите адресной строки
     *
     * @return array
     */
    private function breakUrl()
    {
        $this->url[] = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

        return $this->url;
    }

    public function run()
    {
        print_r($this->rules);
    }
}
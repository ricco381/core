<?php namespace app\core;

class App
{

    public function start($config)
    {

        Config::$config = $config;

        require_once __DIR__ . "/../../bootstrap.php";
        $rules = include_once PATH . 'config/router/rules.php';

        $router = (new Router($rules))->run();

        /**
         * Определнее контроллера и метода по умолчанию
         */
        $controller_name = (!empty($router)) ? $router['action'][0] : $config['controller'];
        $action = (!empty($router)) ? $router['action'][1] : $config['action'];


        $controller = 'web\controller\\' . ucfirst($controller_name);



        /**
         * Проверяем доступность контроллера
         */
        if (!class_exists($controller)) {
            $this->error();
        }


        /**
         * Создаем экземпляр класса контроллера
         */
         $controller = new $controller();

        /**
         * Проверка доступности метода
         */
        if (!method_exists($controller, $action)) {
            $this->error();
        }

        /**
         * Вызываем метод по умолчанию
         */
        try {
        if(count($router['param']))
            call_user_func_array(array($controller, $action), $router['param']);
        else
            $controller->$action();
        } catch (\Exception $e) {
            echo $e->getMessage();
            //file_put_contents('error.log', $e->getMessage() . " ---- " . date('m-d-Y H:i:s', time()) . "\r\n", FILE_APPEND);
        }

    }

    protected function error()
    {
        (new Controller())->twig('error\404.php')->display([]);
        exit;
    }

}
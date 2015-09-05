<?php namespace app\core;

class App
{

    public function start($config)
    {

        Config::$config = $config;

        require_once __DIR__ . "/../../bootstrap.php";
        $rules = include_once PATH . 'config/router/rules.php';

        (new Router($rules))->run();

        /**
         * Определнее контроллера и метода по умолчанию
         */
        $controller_name = (!empty($controller[0])) ? array_shift($controller) : $config['controller'];
        $action = (!empty($fragment)) ? array_shift($fragment) : $config['action'];


        $controller = 'web\controller\\' . ucfirst($controller_name);



        /**
         * Проверяем доступность контроллера
         */
        if (!class_exists($controller)) {
            $this->error($controller);
        }

        /**
         * Создаем экземпляр класса контроллера
         */
         $controller = new $controller();

        /**
         * Проверка доступности метода
         */
        if (!method_exists($controller, $action)) {
            $this->error($action);
        }

        /**
         * Вызываем метод по умолчанию
         */
        try {
        if(count($fragment))
            call_user_func_array(array($controller, $action), $fragment);
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
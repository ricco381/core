<?php namespace app\core;

class Controller
{
    public $load; //Load model
    public $router; //Роутер

    public function __construct()
    {
        $this->router = new Router();
    }

    public function action_index()
    {
        /**
         * Если не существует метода вызываем ошибку 404
         */
        $this->twig('error/404.php')->display([]);
    }

    /**
     * @param $view Имя вида
     * @param array $param данные которые передаются в вид
     *
     */
    public function twig($view = "index.php")
    {
        /**
         * Подгружаем папке с шаблонами
         */
        $loader = new \Twig_Loader_Filesystem([WEB_PATH . "template", CORE_BASE . "view"]);
        $twig = new \Twig_Environment($loader);

        return $twig->loadTemplate($view);
    }


    /**
     * @param $class Название класса который нужно подключить
     * @return mixed
     *
     * Метод загрузки классов
     */
    public function load($class)
    {
        $model = new Model();

        return $this->$class = $model->load($class);
    }

    public function header($action = '/', $error = '')
    {
        if (!empty($error) AND is_array($error)) {
            foreach ($error as $key => $val) {
                $this->load('session')->set([$key => $val]);
            }
        } elseif (is_string($error) AND !empty($error)) {
            $this->load('session')->set(['error' => $error]);
        }

        header('Location: ' . $action);
        exit;
    }
}
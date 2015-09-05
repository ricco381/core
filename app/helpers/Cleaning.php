<?php namespace app\helpers;

class Cleaning
{
    private $rules = []; //Правила обработки
    private $result = []; //Результат обработанных данных
    private $error = []; //Ошибки

    public function start($field = '', $rules = '', $error = '')
    {
        if (is_array($field)) {

            foreach ($field as $field => $rules) {
                $this->start($field, explode('|', $rules['rules']), $rules['error']);
            }

            return $this;
        }

        $postdata = isset($_POST[$field]) ? $_POST[$field] : null;
        $this->rules[$field] = ['rules' => $rules, 'post' => $postdata, 'error' => $error];
    }

    public function run()
    {
        foreach ($this->rules as $field => $rules) {

            foreach ($rules['rules'] as $rule) {

                if (!method_exists($this, $rule)) {

                    if (function_exists($rule)) {
                            $this->result[$field] = $rule($rules['post']);
                        continue;
                    }

                } else {
                    $result = $this->$rule($rules['post']);
                }

                $this->result[$field] = $result;

                if ($result === false) {
                    $this->error[$field] = $rules['error'];
                }
            }
        }

        return $this->result;
    }

    public function error()
    {
        foreach ($this->error as $field => $message) {
            $this->$field = $message;
        }
        return $this->$field;
    }

    public function errorString()
    {
        foreach ($this->error as $field => $message) {
            return $this->error[$field] = $message;
        }
    }

    protected function required($str)
    {
        if ($str == '' OR empty($str))
            return false;
        else
            return $str;
    }

    protected function int($str)
    {
        return filter_var($str, FILTER_VALIDATE_INT);
    }

}
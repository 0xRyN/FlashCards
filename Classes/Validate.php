<?php


class Validate
{
    private $_db,
    $_errors = array(),
    $success = false;

    public function __construct() {
        $_errors = array();
        $this->_db = DB::getInstance();
    }

    //Utilise un tableau chainé multiple dimensions pour checker les input de l'utilisateur
    public function check($fields = array()) {
        //ARRAY DE TRADUCTION
        $temp = array(
            'username' => 'Username',
            'password' =>  'Password',
            'passwordConf' =>  'Passwords',
            'name' =>  'Full name'
        );
        foreach ($fields as $field => $params) {
            foreach ($params as $param => $val) {
                if($param == 'required' && $val = true && empty(Input::get($field))) {
                    $this->addError("<p id=\"errorLogin\">Enter your {$temp[$field]}. </p>");
                }

                if($param == 'matches' && (Input::get($field) != Input::get($val))) {
                    $this->addError("<p id=\"errorLogin\">{$temp[$field]} are not matching. </p>");
                }

                if($param == 'min' && strlen(Input::get($field)) < $val) {
                    $this->addError("<p id=\"errorLogin\">{$temp[$field]} must be at least {$val} characters. </p>");
                }

                if($param == 'max' && strlen(Input::get($field)) > $val) {
                    $this->addError("<p id=\"errorLogin\">{$temp[$field]} must be less than {$val} characters. </p>");
                }

                if($param == 'unique' && $this->_db->get($val, array($field, "=", Input::get($field)))->getCount() != 0) {
                    $this->addError("<p id=\"errorLogin\">{$temp[$field]} " . Input::get($field) . " is already taken. </p>");
                }
            }
        }
        return $this;
    }

    private function addError($error) {
        $this->_errors[] = $error;
    }

    public function error() {
        foreach ($this->_errors as $error) {
            echo $error;
        }
    }

    //Les inputs sont corrects
    public function passed() {
        return empty($this->_errors);
    }
}
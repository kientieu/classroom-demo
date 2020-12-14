<?php

//require_once('app/model/user_model.php');
class base_controller
{
    protected $name;

    public function process($view,$data){
        require_once('app/view/' . $this->name . '/' . $view . '.php');
    }

}
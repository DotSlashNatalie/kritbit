<?php

class main extends HF_Controller
{
    public function index()
    {

        echo $this->loadRender("login.html");

    }
}
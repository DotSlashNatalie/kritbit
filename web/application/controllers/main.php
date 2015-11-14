<?php

class main extends base
{
    public function index()
    {

        if ($this->isLoggedIn()) {
            echo "Hello - " . $this->sessionData->userId;
            echo "email = " . $this->user->email;
        }
        //echo "hello";

    }
}
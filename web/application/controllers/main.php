<?php

class main extends base
{
    public function index()
    {
        $jobs = \application\models\Jobs::getByField("user_id", $this->user->id);
        echo $this->loadRender("main.html", ["jobs" => $jobs]);
    }
}
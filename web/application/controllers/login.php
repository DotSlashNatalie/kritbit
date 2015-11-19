<?php

use system\vendor\phpoauthlib2\providers\GoogleAuthProvider;
use system\vendor\phpoauthlib2\OAuth;
use application\models\Users;

class login extends base {

    protected $loginRequired = false;

    private function accessDenied() {
        return "ACCESS DENIED";
    }
    public function index() {
        $authProvider = new GoogleAuthProvider($_GET, [
            "client_id" => $this->config["GOOGLE_OAUTH_ID"],
            "client_secret" => $this->config["GOOGLE_OAUTH_SECRET"],
            "redirect_uri" => "http://localhost/login"
        ]);
        $oauth = new OAuth($authProvider, $_GET);

        $check = $oauth->check();

        if ($check === true) {
            $email = $authProvider->getEmail();
            /** @var Users $user */
            $users = Users::getByField("email", $email);
            if (count($users) == 0) {
                echo $this->accessDenied();
                return;
            }
            $user = $users[0];
            $this->session->data = json_encode(["userId" => $user->id]);
            $this->session->save();
            $this->sessionData = $this->session->data;
            header("Location: /");
        } else {
            header("Location: " . $check);
        }
    }
}
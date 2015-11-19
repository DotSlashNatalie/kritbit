<?php

class job extends base {
    public function add() {
        if (!isset($_POST["jobName"])) {
            echo $this->loadRender("add.html");
        } else {
            $data = $_POST;
            $data["user_id"] = $this->user->id;
            \application\models\Jobs::create($data)->save();
            header("Location: /");
        }
    }

    public function edit($id) {
        /** @var \application\models\Jobs $job */
        $job = \application\models\Jobs::getByField("id", $id);
        if ($job && $job[0]->user_id == $this->user->id) { //secuirty check
            if (isset($_POST["jobName"])) {
                $job[0]->update($_POST)->save();
                header("Location: /");
            } else {
                echo $this->loadRender("add.html", ["job" => $job[0]]);
            }
        } else {
            header("Location: /");
        }
    }

    public function delete($id) {
        $job = \application\models\Jobs::getByField("id", $id);
        if ($job && $job[0]->user_id == $this->user->id) { //secuirty check
            $job[0]->deleteRelated(["histories"]);
            $job[0]->delete();
            header("Location: /");
        } else {
            header("Location: /");
        }
    }

}
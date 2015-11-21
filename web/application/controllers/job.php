<?php

use \vendor\DB\DB;

class job extends base {
    public function add() {
        if (!isset($_POST["jobName"])) {
	        $sharedkey = bin2hex(openssl_random_pseudo_bytes(16, $bool));
			$hash = bin2hex(openssl_random_pseudo_bytes(32, $bool));
            echo $this->loadRender("add.html", ["hash" => $hash, "sharedkey" => $sharedkey]);
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

    public function force($id) {
	    $job = \application\models\Jobs::getByField("id", $id);
	    if ($job && $job[0]->user_id == $this->user->id) { //secuirty check
		    if ($job[0]->force_run == 1) {
			    $job[0]->force_run = 0;
		    } else {
			    $job[0]->force_run = 1;
		    }
		    $job[0]->save();
		    header("Location: /");
	    } else {
		    header("Location: /");
	    }
    }

	public function search() {
		if (isset($_GET["q"])) {
			$histories = DB::fetch("
SELECT
	jobs.id as job_id, jobs.jobName, histories.id, run_date, time_taken, result
FROM histories
INNER JOIN jobs ON jobs.user_id = ?  AND histories.jobs_id = jobs.id
WHERE output LIKE ?
", [$this->user->id, "%" . $_GET["q"] . "%"]);
			echo $this->loadRender("search.html", ["search" => $_GET["q"], "histories" => $histories]);
		}
	}

}
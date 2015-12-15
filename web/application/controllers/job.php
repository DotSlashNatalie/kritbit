<?php

use \vendor\DB\DB;

class job extends base {
    public function add() {
        if (!isset($_POST["jobName"])) {
	        $keyChars = [
		        'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
	            'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
		        '1','2','3','4','5','6','7','8','9','0',
		        '!','{','}','-','#','%','^','*','[',']','<','>',':','?'
	        ];
	        shuffle($keyChars);
	        shuffle($keyChars);
	        $sharedkey = "";
	        $bool = true;
	        for($i = 0; $i < 32; $i++) {
		        $sharedkey .= $keyChars[mt_rand(0, count($keyChars) - 1)];
	        }
	        //$sharedkey = bin2hex(openssl_random_pseudo_bytes(16, $bool));
			$hash = bin2hex(openssl_random_pseudo_bytes(32, $bool));
            echo $this->loadRender("add.html", ["hash" => $hash, "sharedkey" => $sharedkey]);
        } else {
            $data = $_POST;
            $data["user_id"] = $this->user->id;
            \application\models\Jobs::create($data)->save();
            header("Location: /kritbit");
        }
    }

    public function edit($id) {
        /** @var \application\models\Jobs $job */
        $job = \application\models\Jobs::getByField("id", $id);
        if ($job && $job[0]->user_id == $this->user->id) { //secuirty check
            if (isset($_POST["jobName"])) {
                $job[0]->update($_POST)->save();
                header("Location: /kritbit");
            } else {
                echo $this->loadRender("add.html", ["job" => $job[0]]);
            }
        } else {
            header("Location: /kritbit");
        }
    }

    public function delete($id) {
        $job = \application\models\Jobs::getByField("id", $id);
        if ($job && $job[0]->user_id == $this->user->id) { //secuirty check
            $job[0]->deleteRelated(["histories"]);
            $job[0]->delete();
            header("Location: /kritbit");
        } else {
            header("Location: /kritbit");
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
		    header("Location: /kritbit");
	    } else {
		    header("Location: /kritbit");
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
<?php

use \vendor\DB\DB;

class history extends base
{
    public $loginRequired = false;

    protected function checkAccess($job) {
        if ($job->view_private == 1 && !$this->user) {
            $this->login();
            return false;
        }
        if ($job->view_private == 1 && $this->user && $this->user->id != $job->user_id) {
            $this->login();
            return false;
        }
        return true;
    }

	public function runscript($jobId) {
		$jobObject = \application\models\Jobs::getByField("id", $jobId);
		if ($this->checkAccess($jobObject[0])) {
			header("Content-Type: text/plain");
			echo $jobObject[0]->runScript;
		}
	}

	public function failscript($jobId) {
		$jobObject = \application\models\Jobs::getByField("id", $jobId);
		if ($this->checkAccess($jobObject[0])) {
			header("Content-Type: text/plain");
			echo $jobObject[0]->failScript;
		}
	}

    public function view($id) {
        $idArr = explode("-", $id);
	    try {
		    if (count($idArr) == 2) {
			    /** @var \application\models\Histories $historyArr */
			    $historyArr = DB::fetchObject("SELECT * FROM histories WHERE jobs_id = ? ORDER BY run_date DESC", '\application\models\Histories', [$idArr[1]]);
			    /** @var \application\models\Jobs[] $jobObject */
			    $jobObject = \application\models\Jobs::getByField("id", $idArr[1]);
			    if ($this->checkAccess($jobObject[0])) {
				    $csv = [];
				    /** @var \application\models\histories $history */
				    foreach($historyArr as $history) {
					    $csv[] = "\"" . str_replace("-", "/", $history->run_date) . "," . $history->time_taken . "," . $history->getRawSize() . "," . $history->result . "\\n\"";
				    }
				    echo $this->loadRender("history.html", ["csv" => implode("+", $csv), "job" => $jobObject[0], "histories" => $historyArr]);
			    }
		    }
	    } catch (\Exception $e) {
		    header("Location: /kritbit");
	    }
    }

    public function log($jobId, $logId) {
	    try {
		    $jobObject = \application\models\Jobs::getByField("id", $jobId);
		    if ($this->checkAccess($jobObject[0])) {
			    /** @var \application\models\Histories[] $historyArr */
			    $historyArr = \application\models\Histories::getByField("id", $logId);
			    header("Content-Type: text/plain");
			    echo $historyArr[0]->output;
		    }
	    } catch (\Exception $e) {
		    header("Location: /kritbit");
	    }
    }
}
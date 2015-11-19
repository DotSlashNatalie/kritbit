<?php

class history extends base
{
    public $loginRequired = false;

    protected function checkAccess($job) {
        if ($job->view_private == 1 && !$this->user) {
            header("Location: /login");
            return false;
        }
        if ($job->view_private == 1 && $this->user && $this->user->id != $job->user_id) {
            header("Location: /");
            return false;
        }
        return true;
    }

    public function view($id) {
        $idArr = explode("-", $id);
        if (count($idArr) == 2) {
            /** @var \application\models\Histories $historyArr */
            $historyArr = \application\models\Histories::getByField("jobs_id", $idArr[1]);
            /** @var \application\models\Jobs[] $jobObject */
            $jobObject = \application\models\Jobs::getByField("id", $idArr[1]);
            if ($this->checkAccess($jobObject[0])) {
                echo $this->loadRender("history.html", ["jobid" => $idArr[1], "histories" => $historyArr]);
            }
        }
    }

    public function log($jobId, $logId) {
        $jobObject = \application\models\Jobs::getByField("id", $jobId);
        if ($this->checkAccess($jobObject[0])) {
            /** @var \application\models\Histories[] $historyArr */
            $historyArr = \application\models\Histories::getByField("id", $logId);
            echo $historyArr[0]->output;
        }


    }
}
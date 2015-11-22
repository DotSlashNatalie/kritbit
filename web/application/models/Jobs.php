<?php

namespace application\models;

class Jobs extends \system\engine\HF_Model {
    public $jobName;
    public $runType;
    public $runScript;
    public $cron;
    public $failScript;
    public $last_run;
    public $last_result = null;
    public $user_id;
    public $hash;
    public $sharedkey;
    public $view_private;
	public $force_run;
	public $comments;

    public $h2o_safe = true;

    public function getRunType() {
        switch ($this->runType) {
            case "1":
                return "Ran by Kritbit";
                break;
            case "2":
                return "External Source";
                break;
        }
        return "";
    }

    public function getLastRun() {
        if ($this->last_run == "") {
            return "Never";
        } else {
            return $this->last_run;
        }

    }

	public function getTRClass() {
		if ($this->last_run == "" || $this->last_result == null) {
			return "";
		} elseif ($this->last_result == 0) {
			return "success";
		} else {
			return "danger";
		}
	}
}
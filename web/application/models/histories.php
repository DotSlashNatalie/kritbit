<?php

namespace application\models;

class Histories extends \system\engine\HF_Model {
    public $output;
    public $jobs_id;
    public $run_date;
    public $time_taken;
    public $result = null;
    public $nonce;

	public $h2o_safe = true;

	public function getTRClass() {
		if ($this->result == null) {
			return "";
		} elseif ($this->result == 0) {
			return "success";
		} else {
			return "danger";
		}
	}
}
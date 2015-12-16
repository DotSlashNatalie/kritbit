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

	private function formatBytes($bytes, $precision = 2) {
	    $units = array('B', 'KB', 'MB', 'GB', 'TB');

	    $bytes = max($bytes, 0);
	    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
	    $pow = min($pow, count($units) - 1);

	    // Uncomment one of the following alternatives
	    // $bytes /= pow(1024, $pow);
	    $bytes /= (1 << (10 * $pow));

	    return round($bytes, $precision) . ' ' . $units[$pow];
	}

public function getSize() {
		return $this->formatBytes(strlen($this->output));
	}
}
<?php

use \vendor\DB\DB;

class service extends base {

	protected $loginRequired = false;
	protected $sessionRequired = false;

	/**
	 * This service will expect a JSON POST data of:
	 * ["data"] => {"nonce": "randomString", "message": "cipherText", "signature": "abcdef"}
	 * Signature will be a sha256 of the message pre-encrypt with nonce appended to the end
	 * ie
	 * {JSON} + nonce + sharedhash
	 * Note: sharedhash should NOT be the sharedkey that is used to encrypt the message
	 *
	 *
	 * Unencrypted cipherText will look like
	 * {"output": "stdout of run", "time_taken": 10, "result": 0}
	 * Just like in most modern programs - a result of anything but 0 indicates an error
	 *
	 * @param $jobId
	 */
	public function upload($jobId) {
		if ($jobId && is_numeric($jobId)) {
			try {
				/** @var \application\models\Jobs $job */
				$job = \application\models\Jobs::getByField("id", $jobId)[0];
				//decrypt message
				$data = json_decode($_POST["data"], true);
				$rawMessage = aes_decrypt($job->sharedkey, $data["message"]);
			} catch (\Exception $e) {
				echo $e;
				exit(1);
			}

			// if decryption was successful -
			// check signature
			if (hash("sha256", $rawMessage . $data["nonce"] . $job->hash) == $data["signature"]) {
				// the message is verified
				$messageJson = json_decode($rawMessage);
				$history = \application\models\Histories::create($messageJson);
				$history->jobs_id = $job->id;
			}
		}
	}

	public function run() {
		if (in_array($_SERVER["REMOTE_ADDR"], $this->config["ACCEPTED_IPS"])) { // not very secure - but worst case they fire off the run early
			if (!file_exists("/tmp/kritbot")) {
				touch("/tmp/kritbot");
				try {
					/** @var \application\models\Jobs[] $jobs */
					$jobs = DB::fetchObject("SELECT * FROM jobs", "\\application\\models\\Jobs");
					foreach ($jobs as $job) {
						if ($job->runType == 1) {
							$cron = Cron\CronExpression::factory($job->cron);
							if ($cron->isDue() || $job->force_run == 1) {
								$output = [];
								$returnVar = 0;

								$start = microtime(true);
								// grumble grumble something something windows
								if (stripos(php_uname("s"), "Win") !== false) {
									file_put_contents("/tmp/kritscript.bat", $job->runScript);
									exec("c:\\windows\\system32\\cmd.exe /c c:/tmp/kritscript.bat", $output, $returnVar);
								} else {
									file_put_contents("/tmp/kritscript", $job->runScript);
									exec("/tmp/kritscript", $output, $returnVar);
									chmod("/tmp/kritscript", 0777);
								}
								$end = microtime(true);
								$delta = $end - $start;
								$scriptOutput = implode("\n", $output);
								if ($returnVar != 0) {
									if (stripos(php_uname("s"), "Win") !== false) {
										file_put_contents("/tmp/kritscript.bat", $job->failScript);
										exec("c:\\windows\\system32\\cmd.exe /c c:/tmp/kirtscript.bat");
									} else {
										file_put_contents("/tmp/kritscript", $job->failScript);
										exec("/tmp/kritscript", $output, $returnVar);
										chmod("/tmp/kritscript", 0777);
									}
								}
								$historyObj = new \application\models\Histories();
								$historyObj->output = $scriptOutput;
								$historyObj->result = $returnVar;
								$historyObj->time_taken = $delta;
								$historyObj->jobs_id = $job->id;
								$now = date("Y-m-d H:i:s");
								$historyObj->run_date = $now;
								$historyObj->save();
								$job->force_run = 0;
								$job->last_run = $now;
								$job->last_result = $returnVar;
								$job->save();
							}
						}
					}
					unlink("/tmp/kritbot");
				} catch (\Exception $e) {
					unlink("/tmp/kritbot");
				}
			}

		}
	}
}
<?php
	App::uses('AppShell', 'Console/Command');
	App::uses('HttpSocket', 'Network/Http');

	class MailchimpShell extends AppShell {

		public $token = "aabbccdd"; //API Key

		public $ids = [
			"certification" => [
				"list_id" => "xxx",
				"automation_id" => "xxx",
				"email_id" => "xxx"
			],
			"hero-image" => [
				"list_id" => "xxx",
				"automation_id" => "xxx",
				"email_id" => "xxx"
			]
		];

		public function register_to_list($list_id, $person) {
			$http = new HttpSocket();
			$http->configAuth('Basic', 'user', $this->token);
			$this->out("===== Registering ". $person['email_address'] . " to List =====");
			$this->out($http->post("https://us10.api.mailchimp.com/3.0/lists/$list_id/members", json_encode($person)));
			$this->out("==============================");
		}

		public function queue_automation($automation_id , $email_id, $person) {
			$http = new HttpSocket();
			$http->configAuth('Basic', 'user', $this->token);
			$this->out("===== Queueing ". $person['email_address'] . " For Automation ". $automation_id . "=====");
			$this->out($http->post("https://us10.api.mailchimp.com/3.0/automations/$automation_id/emails/$email_id/queue", json_encode($person)));
			$this->out("==============================");
		}

		public function automate($type=false, $person=false) {
			if(empty($type)) {
				$type = $this->args[0];
			}

			if(empty($person)) {
				$person = $this->args[1];
			}
			if (!empty($person)) {
				if (!empty($this->ids[ strtolower($type) ])) {
					$this->register_to_list($this->ids[ strtolower($type) ]['list_id'], $person);
					$this->queue_automation($this->ids[ strtolower($type) ]['automation_id'], $this->ids[ strtolower($type) ]['email_id'], $person);
					return true;
				} else {
					$this->out("Error - Automate type " . $type . " isn't implemented");
					return false;
				}
			} else {
				$this->out("Error - No Person Record passed");
				return false;
			}


		}
	}

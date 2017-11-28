<?php

class MailCatcherTrialShell extends AppShell {

	public function welcome() {

	}

	public function main() {
		App::uses('CakeEmail', 'Network/Email');
		$mail = new CakeEmail('mailCatcherTrial');
		
		try {
			
			if (!$mail->send('Success!')) throw new Exception('fail to send mail process');
			
		} catch (Exception $e) {
			$this->out($e->getMessage());
		}
	}
}
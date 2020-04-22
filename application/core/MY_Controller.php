<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @property document_m $document_m
 * @property email_m $email_m
 * @property error_m $error_m
 */


class MY_Controller extends CI_Controller {
	public $data = array();

	public function __construct() {
		parent::__construct();
		$this->load->config('iniconfig');
		$this->data['errors'] = array();

        // if(!$this->config->config_install()) {
        //     redirect(site_url('install'));
		// }

		$this->output->set_header('X-XSS-Protection: 1; mode=block');
		$this->output->set_header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
		$this->output->set_header('X-Frame-Options: DENY');
		$this->output->set_header('X-Content-Type-Options: nosniff');
		$this->output->set_header('Referrer-Policy: same-origin');
		$this->output->set_header('Expect-CT: max-age=7776000, enforce');
		$this->output->set_header('Cross-Origin-Resource-Policy: same-origin');


		//$this->output->set_header("Content-Security-Policy: default-src 'self'; script-src 'nonce-4AEemGb0xJptoIGFP3Nd'");

	}
	protected function dbSuccess(){
        return empty($this->db->error()->message);
    }

}


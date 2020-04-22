<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class signin_m extends MY_Model {
	function __construct() {
		parent::__construct();
		$this->load->model("setting_m");
		$this->load->model('usertype_m');
		$this->load->model('loginlog_m');
		$this->load->model('emailtoken_m');
	}

	public function signin() {
		$returnArray = array(
			'return' => FALSE,
			'message' => ''
		);
		$tables = array('user' => 'user', 'systemadmin' => 'systemadmin', 'agent' => 'agent');

		$settings = $this->setting_m->get_setting(1);
		$lang = $settings->language;
		$array = array();
		$array['permition'] = [];
		$i = 0;
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		//die(password_hash($password, PASSWORD_DEFAULT));
		$userdata = '';

		//Failures within 5 minutes
		$failuresRes = $this->checkFailure();
		$failures = $failuresRes["check"];
		$tenMinutesBlock = false;
		//die(var_dump($failuresRes));
		if($failuresRes["last"] && !$failures){
			$m = new \Moment\Moment($failuresRes["last"]->created_at);
			if($m){
				$minutesAgo = $m->fromNow()->getMinutes();
				if($minutesAgo >= 30){
					$failures = null;
					$this->clearFailures();
				}else{
					$tenMinutesBlock = true;
				}
			}
		}
		if((!$failures || count($failures)  < 5) && !$tenMinutesBlock){
			foreach ($tables as $table) {
				$user = $this->db->get_where($table, array("email" => $username));
				$alluserdata = $user->row();
				if($alluserdata){
					$hash = $alluserdata->password;
					// The cost parameter can change over time as hardware improves
					$options = array('cost' => 11);
					// Verify stored hash against plain-text password
					if (password_verify($password, $hash)) {
						// Check if a newer hashing algorithm is available
						// or the cost has changed
						if (password_needs_rehash($hash, PASSWORD_DEFAULT, $options)) {
							// If so, create a new hash, and replace the old one
							$newHash = password_hash($password, PASSWORD_DEFAULT, $options);
							$datax = array(
								'password' => $newHash,
							);
							$colname = $table.'ID';
							$this->db->where($colname, $alluserdata->$colname);
							$this->db->update($table, $datax);
						}
						// Log user in
						$userdata = $alluserdata;
						if($table == 'agent'){
							$userdata->name = $userdata->agentName;
						}
						$array['permition'][$i] = 'yes';
						$array['usercolname'] = $table.'ID';
					}else {
						$array['permition'][$i] = 'no';
					}
				}
				$i++;
			}
			if(isset($settings->captcha_status) && $settings->captcha_status == 0) {
				$captchaResponse = $this->recaptcha->verifyResponse($this->input->post('g-recaptcha-response'));
			} else {
				$captchaResponse = array('success' => TRUE);
			}
	
			if($captchaResponse['success'] == TRUE) {
				if(in_array('yes', $array['permition'])) {
					$colname = $array['usercolname'];
					$usertype = $this->usertype_m->get_usertype($userdata->usertypeID);
					if(count($usertype)) {
						if($userdata->active == 1) {
							$checkAccountVerification = $this->emailtoken_m->get_single_emailtoken(array("usertypeID" => $userdata->usertypeID, "userID" => $userdata->$colname, "purpose" => PURPOSE_EMAIL_VERIFY));
							if($checkAccountVerification){
								$this->clearFailures();
								$data = array(
									"userID" => $userdata->$colname,
									"usertypeID" => $userdata->usertypeID,
									"isPendingVerification" => true,
									"email" => $userdata->email,
								);
								$this->session->set_userdata($data);
								return array( 'return' => FALSE, 'message' => 'verify');
							}
							$data = array(
								"userID" => $userdata->$colname,
								"usertypeID" => $userdata->usertypeID,
								"name" => $userdata->name,
								"email" => $userdata->email,
								"phone" => $userdata->phone,
								"address" => $userdata->address,
								"roles" => $this->userWiseRoles($userdata->$colname, $userdata->usertypeID), 
								"username" => $userdata->email,
								"photo" => $userdata->photo,
								"lang" => $lang,
								"loggedin" => TRUE
							);
							$browser = $this->getBrowser();
	
							$getPreviusData = $this->loginlog_m->get_single_loginlog(array('userID' => $userdata->$colname, 'usertypeID' => $userdata->usertypeID, 'ip' => $this->getUserIP(), 'browser' => $browser['name'], 'logout' => NULL));
	
							if(count($getPreviusData)) {
								$lgoinLogUpdateArray = array(
									'logout' => ($getPreviusData->login+(60*5))
								);
								$this->loginlog_m->update_loginlog($lgoinLogUpdateArray, $getPreviusData->loginlogID);
							}
	
							
							$lgoinLog = array(
								'ip' => $this->getUserIP(),
								'browser' => $browser['name'],
								'operatingsystem' => $browser['platform'],
								'login' => strtotime(date('Ymdhis')),
								'usertypeID' => $userdata->usertypeID,
								'userID' => $userdata->$colname
							);
							$this->loginlog_m->insert_loginlog($lgoinLog);
							$this->session->set_userdata($data);
							//die(var_dump($_SESSION));
	
							$returnArray = array( 'return' => TRUE, 'message' => 'Success');
						} else {
							$returnArray = array( 'return' => FALSE, 'message' => 'Incorrect Signin.');
						}
					} else {
						$returnArray = array( 'return' => FALSE, 'message' => 'Incorrect Signin.');
					}
				} else {
					$returnArray = array( 'return' => FALSE, 'message' => 'Incorrect Signin.');
				}
				if($returnArray['return'] == FALSE){
					$tries = 1;
					if($failures){
						$tries = count($failures) + 1;
						if($tries > 2){
							$this->logFailure(true, $tries);
						}else{
							$this->logFailure();
						}
					}else{
						$this->logFailure();
					}
					if($tries == 5){
						$returnArray["message"] .= " You account is temporarily blocked. You would have to wait for 30 minutes to try again. Click reset password if you cannot remember your password";

					}else{
						$returnArray["message"] .= " You have attempted to sign in incorrectly " . $tries . "/5 times within 5 minutes. You would have to wait for 30 minutes after your 5th trial. Click reset password if you cannot remember your password";
					}
				}else{
					$this->clearFailures();
				}
			} else {
				$returnArray = array( 'return' => FALSE, 'message' => $captchaResponse['error-codes'][0]);
			}
		}else{
			$returnArray = array( 'return' => FALSE, 'message' => "You account is temporarily blocked. You would have to wait for 30 minutes to try again. Click reset password if you cannot remember your password");

		}

		return $returnArray;
	}


	function checkFailure(){
		$username = $this->input->post('username');
		$userIP = $this->getUserIP();
		$usernameCheck = $this->db->select('*')
							->from("loginfailure")
							->where(array("login" => $username))
							->order_by('created_at', 'desc')
							->get()->result();
		//die(var_dump($usernameCheck));
		$usernameCheckLast = $this->db->select('*')
							->from("loginfailure")
							->where(array("login" => $username))
							->order_by('created_at', 'desc')
							->get()->row();
		//die($this->db->get_compiled_select());
		return array("check" => $usernameCheck, "last" => $usernameCheckLast);
	}

	function clearFailures(){
		$username = $this->input->post('username');
		$this->db->delete('loginfailure', array("login" => $username));
	}

	function logFailure($persist = false, $tries = null){
		$username = $this->input->post('username');
		$userIP = $this->getUserIP();
		$this->db->insert('loginfailure', array("login" => $username, "ip" => $userIP, "created_at" => date('Y=m-d H:i:s')));
		if($persist){
			$message = "LOGIN FAILURE: login ID: " . $username . " supplied incorrect details from IP :" . $userIP . ".";
			if($tries){
				$message .= " After " . $tries . " trials.";
			}
			log_message("error", $message);
		}
	}

	function getUserIP() {
	    $client  = @$_SERVER['HTTP_CLIENT_IP'];
	    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	    $remote  = $_SERVER['REMOTE_ADDR'];

	    if(filter_var($client, FILTER_VALIDATE_IP))
	    {
	        $ip = $client;
	    }
	    elseif(filter_var($forward, FILTER_VALIDATE_IP))
	    {
	        $ip = $forward;
	    }
	    else
	    {
	        $ip = $remote;
	    }

	    return $ip;
	}

	
	public function getBrowser() {
	    $u_agent = $_SERVER['HTTP_USER_AGENT'];
	    $bname = 'Unknown';
	    $platform = 'Unknown';
	    $version= "";

	    //First get the platform?
	    if (preg_match('/linux/i', $u_agent)) {
	        $platform = 'linux';
	    }
	    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
	        $platform = 'mac';
	    }
	    elseif (preg_match('/windows|win32/i', $u_agent)) {
	        $platform = 'windows';
	    }

	    // Next get the name of the useragent yes seperately and for good reason
	    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
	    {
	        $bname = 'Internet Explorer';
	        $ub = "MSIE";
	    }
	    elseif(preg_match('/Firefox/i',$u_agent))
	    {
	        $bname = 'Mozilla Firefox';
	        $ub = "Firefox";
	    }
	    elseif(preg_match('/Chrome/i',$u_agent))
	    {
	        $bname = 'Google Chrome';
	        $ub = "Chrome";
	    }
	    elseif(preg_match('/Safari/i',$u_agent))
	    {
	        $bname = 'Apple Safari';
	        $ub = "Safari";
	    }
	    elseif(preg_match('/Opera/i',$u_agent))
	    {
	        $bname = 'Opera';
	        $ub = "Opera";
	    }
	    elseif(preg_match('/Netscape/i',$u_agent))
	    {
	        $bname = 'Netscape';
	        $ub = "Netscape";
	    }

	    // finally get the correct version number
	    $known = array('Version', $ub, 'other');
	    $pattern = '#(?<browser>' . join('|', $known) .
	    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
	    if (!preg_match_all($pattern, $u_agent, $matches)) {
	        // we have no matching number just continue
	    }

	    // see how many we have
	    $i = count($matches['browser']);
	    if ($i != 1) {
	        //we will have two since we are not using 'other' argument yet
	        //see if version is before or after the name
	        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
	            $version= $matches['version'][0];
	        }
	        else {
	            $version= $matches['version'][1];
	        }
	    }
	    else {
	        $version= $matches['version'][0];
	    }

	    // check if we have a number
	    if ($version==null || $version=="") {$version="?";}

	    return array(
	        'userAgent' => $u_agent,
	        'name'      => $bname,
	        'version'   => $version,
	        'platform'  => $platform,
	        'pattern'    => $pattern
	    );
	}

	function change_password() {

		$tables = array('student' => 'student', 'parents' => 'parents', 'teacher' => 'teacher', 'user' => 'user', 'systemadmin' => 'systemadmin');

		$username = $this->session->userdata("username");
		$old_password = $this->hash($this->input->post('old_password'));
		$new_password = $this->hash($this->input->post('new_password'));
		$getOrginalData = '';
		$getOrginalTable = '';
		foreach ($tables as $key => $table) {
			$user = $this->db->get_where($table, array("username" => $username, "password" => $old_password));
			$alluserdata = $user->row();
			if(count($alluserdata)) {
				$getOrginalData = $alluserdata;
				$getOrginalTable = $table;	
			} 
		}

		if(isset($getOrginalData->password) && ( $getOrginalData->password == $old_password)) {
			$array = array(
				"password" => $new_password
			);
			$this->db->where(array("username" => $username, "password" => $old_password));
			$this->db->update($getOrginalTable, $array);
			$this->session->set_flashdata('success', $this->lang->line('menu_success'));
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function signout() {
		$browser = $this->getBrowser();
		$getPreviusData = $this->loginlog_m->get_single_loginlog(array('userID' => $this->session->userdata('loginuserID'), 'usertypeID' => $this->session->userdata('usertypeID'), 'ip' => $this->getUserIP(), 'browser' => $browser['name'], 'logout' => NULL));

		if(count($getPreviusData)) {
			$lgoinLogUpdateArray = array(
				'logout' => strtotime(date('Ymdhis'))
			);
			$this->loginlog_m->update_loginlog($lgoinLogUpdateArray, $getPreviusData->loginlogID);
		}

		$this->session->sess_destroy();
	}

	public function loggedin() {
		return (bool) $this->session->userdata("loggedin");
	}

	protected function userWiseRoles($id, $typeID)
    {
        return array_map(function ($item) {
            return $item["role_id"];
        }, $this->db->get_where("roles_users", array("user_id" => $id, "usertypeID" => $typeID))->result_array());
    }
}

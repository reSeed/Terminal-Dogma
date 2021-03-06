<?php

class Users extends CI_Controller {

        const COOKIE_DAYS = 90;
        
        public function __construct()
        {
                parent::__construct();
                
                $this->load->model('users_model');
                $this->load->model('userinfo_model');
                $this->load->model('preferences_model');
                $this->load->model('user_achievements_rewards_model');
                
                $this->load->helper('url');
                $this->load->helper('email');
                
                $this->load->library('experience');
        }
        
        public function add()
        {
        	$disclaimerAccepted = $this->input->post('disclaimerAccepted');
            if($disclaimerAccepted == false)
            {
                echo json_encode(array("error" => true, "description" => "E' obbligatorio dichiarare di aver preso visione dei Termini d'uso del sito.", "errorCode" => "MANDATORY_FIELD", "parameters" => array("disclaimerAccepted")));
                return;
            }
        	
            $userID = $this->input->post('username');
            if($userID == false)
            {
                echo json_encode(array("error" => true, "description" => "Il nome utente è obbligatorio.", "errorCode" => "MANDATORY_FIELD", "parameters" => array("username")));
                return;
            }
            
            if($this->users_model->exists($userID))
            {
                echo json_encode(array("error" => true, "description" => "Nome utente già esistente.", "errorCode" => "USERNAME_TAKEN", "parameters" => array("username")));
                return;
            }
			
            $special_chars_found = array();
            if(preg_match('/[@$&£#"]/', $userID, $special_chars_found) > 0)
            {
            	echo json_encode(array("error" => true, "description" => "Il nome utente non può contenere il seguente carattere speciale: " . implode($special_chars_found), "errorCode" => "INVALID_FIELD", "parameters" => array("username")));
            	return;
            }
            
            $password = $this->input->post('password');
            if($password == false)
            {
                echo json_encode(array("error" => true, "description" => "Specificare una password.", "errorCode" => "MANDATORY_FIELD", "parameters" => array("password")));
                return;
            }
            
            if(strlen($password) <= 0)
            {
                echo json_encode(array("error" => true, "description" => "La password risulta vuota.", "errorCode" => "MANDATORY_FIELD", "parameters" => array("password")));
                return;
            }
            
            if(strlen($password) < 8)
            {
                echo json_encode(array("error" => true, "description" => "La password dev'essere composta da almeno 8 caratteri.", "errorCode" => "SHORT_FIELD", "parameters" => array("password")));
                return;
            }

            $mail = $this->input->post('mail');
            if($mail == false)
            {
                echo json_encode(array("error" => true, "description" => "Indirizzo e-mail obbligatorio.", "errorCode" => "MANDATORY_FIELD", "parameters" => array("mail")));
                return;
            }
            
            $name = $this->input->post('name');
            if($name == false)
            {
                echo json_encode(array("error" => true, "description" => "Il nome è obbligatorio.", "errorCode" => "MANDATORY_FIELD", "parameters" => array("name")));
                return;
            }
            
            $surname = $this->input->post('surname');
            if($surname == false)
            {
                echo json_encode(array("error" => true, "description" => "Il cognome è obbligatorio.", "errorCode" => "MANDATORY_FIELD", "parameters" => array("surname")));
                return;
            }
            
            $registration_timestamp = date("Y-m-d H:i:s");

            $this->db->trans_start();
            
            // Store the user's ID and password
            $this->users_model->add($userID, $password);
            
            // Store the user's additional information
            $this->userinfo_model->add($userID, $mail, $name, $surname, $registration_timestamp);
            
            $this->preferences_model->add($userID, 0, 0, 0, 0, 0, 1);
            
            $this->login();
            
            $this->db->trans_complete();
        }
        
        public function add_private($userID, $password, $mail, $name, $surname)
        {
            $registration_timestamp = date("Y-m-d H:i:s");
         
            $this->users_model->add($userID, $password);
            if($this->addExtraInfo($userID, $mail, $name, $surname, $registration_timestamp) == -1)
                echo json_encode(array("error" => true, "description" => "Errore durante l'aggiunta dell'utente.", "errorCode" => "GENERIC_ERROR"));
        }
        
        private function addExtraInfo($userID, $mail, $name, $surname, $registration_timestamp)
        {
            // Check email
            if(!valid_email($mail)) return -1;
            
            $this->userinfo_model->add($userID, $mail, $name, $surname, $registration_timestamp);
        }
        
        public function match()
        {
            $userID = $this->input->post('username');
            if($userID == false) $userID = null;
            
            $password = $this->input->post('password');
            if($password == false) $password = null;
            
            echo json_encode($this->users_model->match($userID, $password));
        }
        
        public function match_private($userID, $password)
        {
            echo json_encode($this->users_model->match($userID, $password));
        }
        
        public function delete()
        {
            $userID = $this->input->post('username');
            if($userID == false) $userID = null;
            
            $this->db->trans_start();
            
            $this->users_model->delete($userID);
            $this->userinfo_model->delete($userID);
            
            $this->db->trans_complete();
        }
        
        public function exists()
        {
            $userID = $this->input->post('username');
            if($userID == false) $userID = null;
            
            echo json_encode($this->users_model->exists($userID));
        }
        
        public function exists_private($userID)
        {
            echo json_encode($this->users_model->exists($userID));
        }
        
        public function get()
        {
            $userID = $this->input->post('username');
            if($userID == false) $userID = null;
            
            echo json_encode($this->userinfo_model->get($userID));
        }
        
        public function get_all()
        {
            echo json_encode($this->userinfo_model->get_all());
        }
        
        public function login()
        {
            $userID = $this->input->post('username');
            if($userID == false)
            {
                echo json_encode(array("error" => true, "description" => "Lo username è obbligatorio.", "errorCode" => "MANDATORY_FIELD", "parameters" => array("username")));
                return;
            }
            
            $password = $this->input->post('password');
            if($password == false)
            {
                echo json_encode(array("error" => true, "description" => "La password è obbligatoria.", "errorCode" => "MANDATORY_FIELD", "parameters" => array("password")));
                return;
            }
            
            if(!$this->users_model->match($userID, $password))
            {
                echo json_encode(array("error" => true, "description" => "Il nome utente o la password non sono corretti.", "errorCode" => "LOGIN_ERROR", "parameters" => array("username", "password")));
                return;
            }
            
            $token = password_hash($userID.$password, PASSWORD_BCRYPT);
            $this->users_model->addToken($userID, $token);
            
            // Return the token to the user
            echo json_encode(array("error" => false, "description" => "Il login è stato effettuato correttamente.", "username" => $userID, "token" => $token, "expire" => time()+86400*self::COOKIE_DAYS));
        }
        
        public function logout()
        {
            if(!isset($_COOKIE["username"]))
            {
                echo json_encode(array("error" => true, "description" => "Lo username è obbligatorio.", "errorCode" => "MANDATORY_FIELD", "parameters" => array("username")));
                return;
            }
            
            if(!isset($_COOKIE["token"]))
            {
                echo json_encode(array("error" => true, "description" => "Il token è obbligatorio.", "errorCode" => "MANDATORY_FIELD", "parameters" => array("token")));
                return;
            }
            
            if(!$this->users_model->match($_COOKIE["username"], $_COOKIE["token"]))
            {
                echo json_encode(array("error" => true, "description" => "Il nome utente o il token non sono corretti.", "errorCode" => "LOGOUT_ERROR", "parameters" => array("username", "token")));
                return;
            }
            
            echo json_encode(array("error" => false, "description" => "Il logout è stato effettuato correttamente."));
        }
        

        public function im_admin()
        {
        	$userID = $_COOKIE['username'];
        	$token = $_COOKIE['token'];
        	echo json_encode($this->users_model->isAdmin($userID,$token));
        	 
        	return;
        }
        public function im_user()
        {
        	$userID = $_COOKIE['username'];
        	$token = $_COOKIE['token'];
        	echo json_encode($this->users_model->isUser($userID,$token));
        	 
        	return;
        }
        
		public function init_exp_events()
		{
			$this->experience_events_model->init();
		}
		
		public function delete_exp_events()
		{
			$this->experience_events_model->drop();
		}
		
        public function add_exp()
        {
            $userID = $this->input->post('username');
            if($userID == false)
            {
                echo json_encode(array("error" => true, "description" => "Lo username è obbligatorio.", "errorCode" => "MANDATORY_FIELD", "parameters" => array("username")));
                return;
            }
            
            $exp = $this->input->post('exp');
            if($exp == false)
            {
                echo json_encode(array("error" => true, "description" => "Specificare punti esperienza.", "errorCode" => "MANDATORY_FIELD", "parameters" => array("exp")));
                return;
            }
            
            $courseID = $this->input->post('courseID');
            if($courseID == false) $courseID = null;
            
            $description = $this->input->post('description');
            if($description == false) $description = null;
			
            // Add the experience
            $this->add_exp_to_user($userID, $exp, $courseID, $description);
        }
        
        public function add_exp_to_user($userID, $exp, $courseID = null, $description = null)
        {
        	echo json_encode($this->experience->add_exp_to_user($userID, $exp, $courseID, $description));
        }
        
        public function get_exp_info()
        {
        	$userID = $this->input->post('username');
        	if($userID == false)
        	{
        		echo json_encode(array("error" => true, "description" => "Lo username è obbligatorio.", "errorCode" => "MANDATORY_FIELD", "parameters" => array("username")));
        		return;
        	}
        	
        	// Get the current level and the current exp
        	$exp_info = $this->userinfo_model->get_exp_info($userID);
        	$level = $exp_info['level'];
        	$currentExp = $exp_info['currentExp'];
        	
        	$expForThisLevel = $this->experience->expForLevel($level);
        	$missingExpForNextLevel = $this->experience->getMissingExpForNextLevel($level, $currentExp);
        	$expForNextLevel = $this->experience->expForLevel($level+1);
        	$partialExperience = $currentExp - $expForThisLevel;
        	
        	echo json_encode(array(
        			"error" => false,
        			'expInfo' => array(
	        			"level" => $level,
	        			"currentExperience" => $currentExp,
        				"expForThisLevel" => $expForThisLevel,
	        			"expForNextLevel" => $expForNextLevel,
	        			"missingExpForNextLevel" => $missingExpForNextLevel,
        				"experienceDifference" => ($expForNextLevel-$expForThisLevel),
        				"partialExperience" => $partialExperience
        			)
        	));
        }
        
        public function get_total_discount()
        {
        	$userID = $this->input->post('username');
        	if($userID == false)
        	{
        		echo json_encode(array("error" => true, "description" => "Lo username è obbligatorio.", "errorCode" => "MANDATORY_FIELD", "parameters" => array("username")));
        		return;
        	}
        	
        	$total_discount = 0;
        	$rewards = $this->user_achievements_rewards_model->get_achievements_and_rewards_obtained($userID, "REWARD", "DISCOUNT");
        	foreach ($rewards as $reward)
        	{
        		$total_discount = $total_discount + $reward['data'];
        	}
        	
        	echo json_encode(array("error" => false, "discount" => $total_discount));
        }
        
        public function get_all_users_exp()
        {
        	$data = $this->userinfo_model->get_all_exp_info();
        	$visibility = array();
        	foreach($this->preferences_model->get_all() as $preferences)
        	{
        		$visibility[$preferences['userID']] = $preferences['visibleInHighScore'];
        	}
        	
        	$filtered_data = array();
        	
        	foreach ($data as $userData)
        	{
        		$userID = $userData['userID'];
        		
        		// For sake of correctness (a user should always have an entry in the preferences table)
        		if(!array_key_exists($userID, $visibility)) continue;
        		
        		if($visibility[$userID])
        		{
        			$filtered_data[] = $userData;
        		}
        	}
        	
        	echo json_encode(array("error" => false, "users" => $filtered_data));
        }
}
?>
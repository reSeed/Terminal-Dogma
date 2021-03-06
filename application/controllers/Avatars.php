<?php

// $this->db->error(); working?
class Avatars extends CI_Controller {

        public function __construct()
        {
                parent::__construct();
                $this->load->model('userinfo_model');
                
                $this->load->helper('url');
                $this->load->helper('file');
                $this->load->library('experience');
        }
        
        public function load_temporary_avatar()
        {
        	$temp_file = $this->get_temp_avatar();
            if($temp_file == null) return;
            
            echo json_encode(array("error" => false, "description" => $temp_file));
        }
        
        private function get_temp_avatar()
        {
        	$userID = $this->input->post('username');
        	if($userID == false)
        	{
        		echo json_encode(array("error" => true, "description" => "Specificare il nome utente.", "errorCode" => "MANDATORY_FIELD", "parameters" => array("username")));
        		return;
        	}

        	$file = count($_FILES) > 0;
        	$uri = $this->input->post('avatarUri');
        	
        	// No file specified
        	if(!$file && !$uri)
        	{
        		echo json_encode(array("error" => true, "description" => "Errore durante il caricamento del file. Specificare un nome di file o un URI.", "errorCode" => "MISSING_FILE_ERROR", "parameters" => array("file")));
        		return null;
        	}
        	
        	// Location of the temporary file where the avatar file will be stored
        	$temp_file = null;
        	
        	if($file)
        	{
        		if ($_FILES['file']['error'] !== UPLOAD_ERR_OK)
        		{
        			echo json_encode(array("error" => true, "description" => "Errore durante il caricamento del file. Dettagli: " . $_FILES['file']['error'], "errorCode" => "UPLOAD_ERROR", "parameters" => array("file")));
        			return null;
        		}
        		 
        		$temp_file = $_FILES['file']['tmp_name'];
        	}
        	else
        	{
        		if(substr(get_headers($uri)[0], 9, 3) != "200")
        		{
        			echo json_encode(array("error" => true, "description" => get_headers($uri)[0], "errorCode" => "MISSING_FILE_ERROR", "parameters" => array("avatarUri")));
        			return null;
        		}
        		
        		// Create a temporary file
        		$temp_file = tempnam(sys_get_temp_dir(), "profile-");
        		copy($uri, $temp_file);
        	}
        	
        	// Check that the file is OK (real file type check, not based on mime)
        	$is_file_okay = $this->file_OK($temp_file);
        	if(!$is_file_okay)
        	{
        		echo json_encode(array("error" => true, "description" => "Errore durante il caricamento del file. Tipo file non permesso.", "errorCode" => "FORBIDDEN_FILE_TYPE_ERROR"));
        		return null;
        	}
        	
        	$temp_user_dir = "uploads/profiles/tmp/";
        	$files = glob($temp_user_dir . '*', GLOB_MARK);
        	foreach ($files as $file) unlink($file);
        	
        	// DEBUG: temporary avatar file
        	// Get the destination directory
        	$final_file = $temp_user_dir . uniqid("", true);
        	 
        	// Check if directory already exists
        	if(!file_exists($temp_user_dir))
        	{
        		// If it doesn't exist, create it
        		if(!mkdir($temp_user_dir, 0777, true))
        		{
        			echo json_encode(array("error" => true, "description" => "Errore durante il caricamento del file. Non è stato possibile creare la cartella dei profili.", "errorCode" => "DIRECTORY_ERROR", "parameters" => array("file")));
        			return;
        		}
        	}
        	copy($temp_file, $final_file);
        	
        	return $final_file;
        }
        
        public function load_avatar()
        {
        	$userID = $this->input->post('username');
        	if($userID == false)
        	{	
        		echo json_encode(array("error" => true, "description" => "Specificare il nome utente.", "errorCode" => "MANDATORY_FIELD", "parameters" => array("username")));
        		return;
        	}
        	
        	$notifications = array();
        	
        	// Get the temporary file avatar URI
        	$tempURI = $this->input->post('avatarUri');
        	
        	// If the user hasn't previously loaded a file
        	if($tempURI == false)
        	{
        		$tempURI = $this->get_temp_avatar();
        		if($tempURI == null) return;
        	}
        	
        	// Detect the extension of the file, if present
        	$finfo = finfo_open(FILEINFO_MIME_TYPE);
        	$mime = finfo_file($finfo, $tempURI);
        	$extension = substr($mime, strpos($mime, "/")+1);
        	
        	// Define the final avatar destination file URI
        	$fileURI = "uploads/profiles/" . uniqid("", true) . "." . $extension;
        	
        	// Remove the old avatar image
        	$previousAvatar = $this->userinfo_model->get($userID)['profilePicture'];
        	if(file_exists($previousAvatar)) unlink($previousAvatar);
        	
        	// Update the database with the new avatar URI
        	$this->userinfo_model->update($userID, array('profilePicture' => $fileURI));
        	
        	if(empty($previousAvatar))
        	{
        		$exp_notifications = $this->experience->add_exp_to_user($userID, 500, null, " per aver aggiunto per la prima volta un avatar al tuo profilo.");
        		foreach($exp_notifications as $exp_notification)
        			$notifications[] = $exp_notification;
        	}
        	
        	// Get the destination directory
        	$uploadDir = dirname($fileURI);
        	
        	// Check if directory already exists
        	if(!file_exists($uploadDir))
        	{
        		// If it doesn't exist, create it
        		if(!mkdir($uploadDir, 0777, true))
        		{
        			echo json_encode(array("error" => true, "description" => "Errore durante il caricamento del file. Non è stato possibile creare la cartella dei profili.", "errorCode" => "DIRECTORY_ERROR", "parameters" => array("file")));
        			return;
        		}
        	}
        	
        	// Move the temporary avatar file to the destination file
        	copy($tempURI, $fileURI);
        	unlink($tempURI);
        	
        	$temp_user_dir = "uploads/profiles/tmp/";
        	$files = glob($temp_user_dir . '*', GLOB_MARK);
        	foreach ($files as $file) unlink($file);
        	
        	$notifications[] = array("error" => false, "finalAvatarURI" => $fileURI);
        	
        	echo json_encode($notifications);
        }
        
        public function file_OK($file)
        {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file);
            switch ($mime) {
                case 'image/jpeg':
                case 'image/jpg':                    
                case 'image/gif':
                case 'image/png':
                    break;
                default:
                    return false;
            }
            
            return true;
        }
        
        public function get_avatar()
        {
        	$userID = $this->input->post('username');
        	if($userID == false)
        	{
        		echo json_encode(array("error" => true, "description" => "Specificare il nome utente.", "errorCode" => "MANDATORY_FIELD", "parameters" => array("username")));
        		return;
        	}
        	
        	$avatar = $this->userinfo_model->get($userID)['profilePicture'];
        	
        	echo json_encode(array("error" => false, "avatar" => $avatar));
        }
        
        public function crop($centered = true)
        {
        	$uri = "http://www.barnesandnoble.com/blog/barnesy/wp-content/uploads/2013/08/country_western.jpg";
//         	$uri = "http://npg.si.edu/exhibit/feature/images/schoeller_full.jpg";

			$info = getimagesize($uri);
			$image_type = $info['mime'];
        	
        	// Load the image
        	$image = null;
	        switch ($image_type)
			{
			    case 'image/jpeg':
			        $image = imagecreatefromjpeg($uri);
			    break;
			    case 'image/gif':
			        $image = imagecreatefromgif($uri);
			    break;
			    case 'image/png':
			        $image = imagecreatefrompng($uri);
			    break;
			    default:
		    	{
		    		echo json_encode(array("error" => true, "description" => "Errore durante il caricamento del file. Tipo file non permesso.", "errorCode" => "FORBIDDEN_FILE_TYPE_ERROR"));
		    		return;
		    	}
			}
			
			// Create the cropping info
			$width = $info[0];
			$height = $info[1];
			$crop_measure = min($width, $height);
			$x = 0;
			$y = 0;
			if($centered)
			{
				$x = ($width - $crop_measure)/2;
				$y = ($height - $crop_measure)/2;
			}
			
			$to_crop_array = array('x' => $x , 'y' => $y, 'width' => $crop_measure, 'height'=> $crop_measure);
			
			// Crop the image
        	$thumb_im = imagecrop($image, $to_crop_array);
        	
        	// Print it or save it
        	header('Content-Type: ' . $image_type);
        	switch ($image_type)
        	{
        		case 'image/jpeg':
        			$image = imagejpeg($thumb_im);
        			break;
        		case 'image/gif':
        			$image = imagegif($thumb_im);
        			break;
        		case 'image/png':
        			$image = imagepng($thumb_im);
        			break;
        		default:
        			break;
        	}
        }
}
?>
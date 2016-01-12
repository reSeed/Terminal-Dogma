<?php
class UserInfo extends CI_Controller {

        public function __construct()
        {
                parent::__construct();
                $this->load->model('userinfo_model');
                $this->load->helper('url');
                
                $this->load->library('experience');
        }
        
        public function init()
        {
            $this->userinfo_model->init();
        }
        
        public function update()
        {
        	$userID = $this->input->post('username');
        	if($userID == false)
        	{
        		echo json_encode(array("error" => true, "description" => "Lo username è obbligatorio.", "errorCode" => "MANDATORY_FIELD", "parameters" => array("username")));
        		return;
        	}
        	
        	$this->db->trans_start();
        	
        	$pieces = array();
        	if($this->input->post('birthdate')) $pieces['birthdate'] = $this->input->post('birthdate');
        	if($this->input->post('profession')) $pieces['profession'] = $this->input->post('profession');
        	if($this->input->post('schoolName')) $pieces['schoolName'] = $this->input->post('schoolName');
        	if($this->input->post('educationLevel')) $pieces['educationLevel'] = $this->input->post('educationLevel');
        	if($this->input->post('address'))
        	{
        		$address = $this->input->post('address');
        		if(	strpos(strtolower($address), "via") === false &&
        			strpos(strtolower($address), "piazza") === false &&
        			strpos(strtolower($address), "p.le") === false &&
        			strpos(strtolower($address), "largo") === false
				)
        		{
        			echo json_encode(array("error" => true, "description" => "Campo non valido.", "errorCode" => "ILLEGAL_VALUE", "parameters" => array("address")));
        			return;
        		}
        		
        		$pieces['address'] = $address;
        	}
        	if($this->input->post('phoneNumber'))
        	{
        		$phone = $this->input->post('phoneNumber');
        		
        		$prefixes = '004191|010|011|0121|0122|0123|0124|0125|0131|0141|0142|0143|0144|015|0161|0163|0165|0166|0171|0172|0173|0174|0175|0182|0183|0184|0185|0187|019|02|030|031|0321|0322|0323|0324|0331|0332|0341|0342|0343|0344|0345|0346|035|0362|0363|0364|0365|0371|0372|0373|0374|0375|0376|0377|0381|0382|0383|0384|0385|0386|039|040|041|0421|0422|0423|0424|0425|0426|0427|0428|0429|0431|0432|0433|0434|0435|0436|0437|0438|0439|0442|0444|0445|045|0461|0462|0463|0464|0465|0471|0472|0473|0474|0481|049|050|051|0521|0522|0523|0524|0525|0532|0533|0534|0535|0536|0541|0542|0543|0544|0545|0546|0547|055|0564|0565|0566|0571|0572|0573|0574|0575|0577|0578|0583|0584|0585|0586|0587|0588|059|06|070|071|0721|0722|0731|0732|0733|0734|0735|0736|0737|0742|0743|0744|0746|075|0761|0763|0765|0766|0771|0773|0774|0775|0776|0781|0782|0783|0784|0785|0789|079|080|081|0823|0824|0825|0827|0828|0831|0832|0833|0835|0836|085|0861|0862|0863|0864|0865|0871|0872|0873|0874|0875|0881|0882|0883|0884|0885|089|090|091|0921|0922|0923|0924|0925|0931|0932|0933|0934|0935|0941|0942|095|0961|0962|0963|0964|0965|0966|0967|0968|0971|0972|0973|0974|0975|0976|0981|0982|0983|0984|0985|099';
        		if(preg_match('/^((00|\+)39)*('.$prefixes.').*$/', $phone) == 0)
        		{
        			echo json_encode(array("error" => true, "description" => "Campo non valido.", "errorCode" => "ILLEGAL_VALUE", "parameters" => array("phoneNumber")));
        			return;
        		}
        		
        		$pieces['phoneNumber'] = $phone;
        	}
        	if($this->input->post('mobileNumber'))
        	{
        		$phone = $this->input->post('mobileNumber');
        		
        		if(preg_match('/^((00|\+)39)*(33|34|32|39).*$/', $phone) == 0)
        		{
        			echo json_encode(array("error" => true, "description" => "Campo non valido.", "errorCode" => "ILLEGAL_VALUE", "parameters" => array("mobileNumber")));
        			return;
        		}
        		
        		$pieces['mobileNumber'] = $phone;
        	}
        	if($this->input->post('advertisementProvenance'))
        	{
        		$provenance = $this->input->post('advertisementProvenance');
        		
        		if(
        			strpos(strtolower($provenance), "passaparola") === false &&
        			strpos(strtolower($provenance), "ricerca") === false &&
        			strpos(strtolower($provenance), "volantino") === false &&
        			strpos(strtolower($provenance), "facebook_ads") === false &&
        			strpos(strtolower($provenance), "google_ads") === false
        		)
        		{
        			echo json_encode(array("error" => true, "description" => "Campo non valido.", "errorCode" => "ILLEGAL_VALUE", "parameters" => array("advertisementProvenance")));
        			return;
        		}
        		
        		$pieces['advertisementProvenance'] = $provenance;
        	}
        	
        	foreach($pieces as $piece_key => $piece_value)
        	{
        		$this->experience->add_exp_to_user($userID, 500, null, "Hai aggiunto " . $piece_key . " al tuo profilo.");
        	}
        	
        	$this->userinfo_model->update($userID, $pieces);
        	
        	$this->db->trans_complete();
        }
}
?>
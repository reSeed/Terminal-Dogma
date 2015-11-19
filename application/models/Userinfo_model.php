<?php
class Userinfo_model extends CI_Model
{
        const table_name = "InfoUtenti";
        
        public function __construct()
        {
                $this->load->database();
                // $this->load->helper('url');
        }
        
        public function init()
        {
                $this->load->dbforge();
                
                // Email
                // + Data di registrazione
                // +(opt) Nome
                // +(opt) Cognome
                // +(opt) Età
                // +(opt) Foto profilo (url)
                // +(opt) Professione
                // +(opt) Scuola
                // +(opt) Facolta'/Classe
                // +(opt) Indirizzo (indirizzo google “verboso”)
                // +(opt) Telefono
                // +(opt) Telefono cellulare
                // +(opt) Come sei venuto a conoscenza di reSeed
                // Current exp
                // Previous exp
                // Level
                $fields = array(
                        'userID' => array('type' => 'VARCHAR', 'constraint' => 30),
                        'email' => array('type' => 'VARCHAR', 'constraint' => 30),
                        'registrationDate' => array('type' => 'DATETIME'),
                        'name' => array('type' => 'VARCHAR', 'constraint' => 30),
                        'surname' => array('type' => 'VARCHAR', 'constraint' => 30),
                        'age' => array('type' => 'TINYINT', 'unsigned' => TRUE),
                        'profilePicture' => array('type' => 'VARCHAR', 'constraint' => 2083),
                        'profession' => array('type' => 'VARCHAR', 'constraint' => 50),
                        'schoolName' => array('type' => 'VARCHAR', 'constraint' => 100),
                        'educationLevel' => array('type' => 'VARCHAR', 'constraint' => 100),
                        'address' => array('type' => 'VARCHAR', 'constraint' => 256),
                        'phoneNumber' => array('type' => 'VARCHAR', 'constraint' => 20),
                        'mobileNumber' => array('type' => 'VARCHAR', 'constraint' => 20),
                        'advertisementProvenance' => array('type' => 'VARCHAR', 'constraint' => 256),
                        'currentExp' => array('type' => 'INT', 'unsigned' => TRUE),
                        'previousExp' => array('type' => 'INT', 'unsigned' => TRUE),
                        'level' => array('type' => 'SMALLINT', 'unsigned' => TRUE),
                );
                
                $this->dbforge->add_key('userID', TRUE);
                
                $this->dbforge->add_field($fields);
                
                $this->dbforge->create_table(self::table_name);
        }
        
        public function add($userID, $mail, $name, $surname, $registration_timestamp)
        {
                $data = array(
                   'userID' => $userID,
                   'email' => $mail,
                   'name' => $name,
                   'surname' => $surname,
                   'registrationDate' => $registration_timestamp
                );
                
                $this->db->insert(self::table_name, $data);
        }
        
        public function update($data)
        {
                $data = array();
                
                $this->db->where('userID', $data['userID']);
                $this->db->update(self::table_name, $data);
        }
        
        public function get($userID)
        {
                $this->db->where('userID', $userID);
                $query = $this->db->get(self::table_name);
                return $query->result_array();
        }
        
        public function get_all()
        {
                $query = $this->db->get(self::table_name);
                return $query->result_array();
        }
        
        public function delete($userID)
        {
                $this->db->delete(self::table_name, array('userID' => $userID));
        }
        
        public function get_exp_info($userID)
        {
                return $this->db->select('level, currentExp')->where('userID', $userID)->get(self::table_name)->row_array();
        }
        
        public function update_exp_info($userID, $oldExp, $newExp, $level)
        {
                $this->db->where('userID', $userID)->update(self::table_name, array('currentExp' => $newExp, 'previousExp' => $oldExp, 'level' => $level));
        }
        
        // public function get_experience($userID)
        // {
        //         return $this->db->select('currentExp')->where('userID', $userID)->from(self::table_name)->row_array()['currentExp'];
        // }
        
        // public function update_experience($userID, $oldExp, $newExp)
        // {
        //         $this->db->where('userID', $userID)->update(self::table_name, array('currentExp' => $newExp, 'previousExp' => $oldExp));
        // }
        
        // public function get_level($userID)
        // {
        //         return $this->db->select('level')->where('userID', $userID)->from(self::table_name)->row_array()['level'];
        // }        
        
        // public function update_level($userID, $newLevel)
        // {
        //         $this->db->where('userID', $userID)->update(self::table_name, array('level' => $newLevel));
        // }
}
?>
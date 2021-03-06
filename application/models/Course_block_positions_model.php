<?php
class Course_block_positions_model extends CI_Model
{
        const table_name = "course_block_positions";
        
        public function __construct()
        {
			$this->load->database();
        }
        
		public function init()
        {
			$this->load->dbforge();
                
			$fields = array(
            	'username' => array(
                	'type' => 'VARCHAR',
                    'constraint' => 30
					),
				'courseID' => array(
                	'type' => 'VARCHAR',
                    'constraint' => 30,
                    ),
				'panelID' => array(
							'type' => 'VARCHAR',
							'constraint' => 30,
					),
                'panel_measure' => array(
                	'type' => 'VARCHAR',
                	'constraint' => 1024,
                	)
			);

			$this->dbforge->add_key('username', TRUE);
			$this->dbforge->add_key('courseID', TRUE);
			$this->dbforge->add_key('panelID', TRUE);
                
			$this->dbforge->add_field($fields);
			$this->dbforge->create_table(self::table_name);
        }
        
        public function add($userID, $courseID, $panelID, $panel_measure)
        {
			$data = array(
            	'username' => $userID,
                'courseID' => $courseID,
				'panelID' => $panelID,
                'panel_measure' => $panel_measure,
			);

			$this->db->insert(self::table_name, $data);
        }
        
        public function update($userID, $courseID, $panelID, $panel_measure)
        {
			$this->db
				->where('username', $userID)
	            ->where('courseID', $courseID)
	            ->where('panelID', $panelID)
	            ->update(self::table_name, array('panel_measure' => $panel_measure));
        }
        
        public function get($userID, $courseID, $panelID = null)
        {
        	$data = array();
        	$data['username'] = $userID;
        	$data['courseID'] = $courseID;
        	if($panelID != null) $data['panelID'] = $panelID;
        	
			return $this->db->where($data)->get(self::table_name)->result_array();
        }
}
?>
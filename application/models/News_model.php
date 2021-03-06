<?php
class News_model extends CI_Model
{
        const table_name = "news";
        
        public function __construct()
        {
                $this->load->database();
        }
        
        public function init()
        {
                $this->load->dbforge();
                
                $fields = array(
                        'newsID' => array(
                                'type' => 'INT',
                                 'auto_increment' => TRUE
                        ),
                        'title' => array(
                                'type' => 'VARCHAR',
                                'constraint' => 1024
                        ),
                		'description' => array(
                				'type' => 'VARCHAR',
                				'constraint' => 4096
                		),
                		'publishingTimestamp' => array(
                				'type' => 'DATETIME'
                		),
                );
                
                $this->dbforge->add_key('newsID', TRUE);
                
                $this->dbforge->add_field($fields);
                $this->dbforge->create_table(self::table_name);
        }
        
        public function add($title, $description, $publishingTimestamp)
        {
                $data = array(
                   	'title' => $title,
					'description' => $description,
                    'publishingTimestamp' => $publishingTimestamp,
                );
                
                $this->db->insert(self::table_name, $data);
        }
        
        public function delete($newsID)
        {
                $data = array('newsID' => $newsID);

                return $this->db->delete(self::table_name, $data);
        }
        
        public function update($newsID, $title = null, $description = null, $publishingTimestamp = null)
        {
                $data = array();
                if($title != null) $data['title'] = $title;
                if($description != null) $data['description'] = $description;
                if($publishingTimestamp != null) $data['publishingTimestamp'] = $publishingTimestamp;
                if(count($data) == 0) return false;
                
                $this->db->where('newsID', $newsID)->update(self::table_name, $data);
                
                return true;
        }
        
        public function get($newsID)
        {
                return $this->db->where('newsID', $newsID)->get(self::table_name)->row_array();
        }
        
        public function get_latest_news($n)
        {
                $data = array();
                return $this->db->order_by('publishingTimestamp', 'desc')->limit($n)->get(self::table_name)->result_array();
        }
}
?>
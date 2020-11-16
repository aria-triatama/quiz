<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Model {
	
	function __construct() {
		parent::__construct();
	}
	
	public function Question($number) {
		$this->db->select("quiz_session.id,quiz_session.question_id,questions.question_desc")
				->from("quiz_session")
				->join("questions","quiz_session.question_id = questions.question_id")
				->where("quiz_session.id",$number);
		$res = $this->db->get();
		return $res->row();
    }
    
    public function Choices($id) {
        $this->db->select("choices_id,choice_head,choice_desc")
                ->from("choices")
                ->where("question_id",$id);
        $res = $this->db->get();
        return $res->result();
    }

    public function Quiz() {
        $res = $this->db->get("quiz_session");
        return $res->num_rows();
    }

    public function CheckAnswer($id, $answer) {
        $this->db->select("answer")
                ->from("choices")
                ->where(array("question_id"=>$id,"choices_id"=>$answer));
        $res = $this->db->get();
        return $res->row()->answer;
    }
	
}

?>
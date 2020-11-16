<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sesi extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function Process()
	{
        $this->load->model("Data");
        
        $number = $this->input->post("number");
        $answer = $this->input->post("answer");

        if ($number > 0 && $answer != null) {
            if ($this->Data->CheckAnswer($number,$answer)) {
                $right = $this->session->right;
                $right++;
                $this->session->set_userdata("right",$right);
            }
        }
        
        $total = $this->session->total;
        if ($total > 0) {
            if ($number == 0) {
                $number = $this->session->current;
            }
        } else {
            $total = $this->Data->Quiz();
            $this->session->set_userdata("total",$total);
        }

        $number++;

        $question = null;
        $choices = null;
        $result = null;
        if ($number > $total) {
            $right = $this->session->right;
            $result = $right." benar dari ".$total." pertanyaan";
            $this->session->set_userdata("right",0);
            $this->session->set_userdata("total",0);
            $this->session->set_userdata("current",0);
        } else {
            $question = $this->Data->Question($number);
            $choices = $this->Data->Choices($question->question_id);
        }

        $this->session->set_userdata("current",$number);

        $response = array("success"=>1,"message"=>"","question"=>$question,"choices"=>$choices,"result"=>$result);

		$this->output
        ->set_status_header(200)
        ->set_content_type("application/json", "utf-8")
        ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}
}

<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Scores
 */

class Scores extends CI_Controller {

	function __construct(){
		parent::__construct();

		$this->load->helper('url');

		$this->load->library('tank_auth');

		if( $this->tank_auth->is_logged_in() ){
			$this->data['username'] = $this->tank_auth->get_username();
		}
		else{
			$this->data['username'] = false;
		}

		$this->data['jsfiles'] = array(
			base_url('js/fancybox/jquery.fancybox-1.3.4.pack.js')
		);

		$this->data['cssfiles'] = array(
			base_url('css/scores.css'),
			base_url('js/fancybox/jquery.fancybox-1.3.4.css')
		);

	}

	function index(){

		$this->load->model('scores_model');

		$this->data['scores'] = $this->scores_model->get();
		
		$this->load->view('header', $this->data);

		$this->load->view('high-scores', $this->data);

		$this->load->view('footer');
		
	}

}



/* End of file scores.php */
/* Location: ./application/controllers/scores.php */
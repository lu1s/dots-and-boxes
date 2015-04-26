<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Login
 */

class About extends CI_Controller {

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
			base_url('css/about.css'),
			base_url('js/fancybox/jquery.fancybox-1.3.4.css')
		);

	}

	function index(){

		array_push( $this->data['jsfiles'], base_url('js/slides.jquery.js') );

		$this->load->view('header', $this->data);
		$this->load->view('about/main');
		$this->load->view('footer');		

	}

	function us(){

		$this->load->view('header', $this->data);
		$this->load->view('about/us');
		$this->load->view('footer');		

	}

	function instructions(){

		$this->load->view('header', $this->data);
		$this->load->view('about/instructions');
		$this->load->view('footer');

	}

}

/* End of file about.php */
/* Location: ./application/controllers/about.php */
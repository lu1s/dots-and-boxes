<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Login
 */

class Account extends CI_Controller {

	function __construct(){
		parent::__construct();

		$this->load->helper('url');
		$this->load->library('tank_auth');

	}

	function index(){
		if( !$this->tank_auth->is_logged_in() )
			redirect('/');

		$data['username'] = $this->tank_auth->get_username();

		$data['title'] = 'My account';

		$this->load->view('header', $data);
		$this->load->view('account/main', $data);
		$this->load->view('footer', $data);
	}

	function edit(){
		if( !$this->tank_auth->is_logged_in() )
			redirect('/');
	}


}

/* End of file account.php */
/* Location: ./application/controllers/account.php */
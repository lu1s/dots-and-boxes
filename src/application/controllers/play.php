<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Login
 */

class Play extends CI_Controller {

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
			base_url('js/fancybox/jquery.fancybox-1.3.4.pack.js'),
			base_url('js/cookies.js')
		);

		$this->data['cssfiles'] = array(
			base_url('css/about.css'),
			base_url('css/play.css'),
			base_url('js/fancybox/jquery.fancybox-1.3.4.css')
		);

	}

	function index(){

		if( !$this->tank_auth->is_logged_in() ){
			redirect("/play/login");
		}
		else{
			$this->load->view('header', $this->data);
			$this->load->view('play/main');
			$this->load->view('footer');	
		}	

	}

	function login($slug = false){
			if($slug != false)
				$this->data['slug'] = $slug;
			$this->load->view('header', $this->data);
			$this->load->view('play/login');
			$this->load->view('footer');
	}

	function multiplayer($slug = false){

		if( !$this->tank_auth->is_logged_in() ){

			redirect("/play/login/".$slug);
		}

		$this->load->model('game_model');

		if($slug === false)
			$this->load->view('general_message', array(
				'subject'	=>	'Oops! Invalid URL',
				'message'	=>	'The URL you provided does not contain a Game ID. <br/>Ask your friend for the complete and correct URL, or <a href="'.site_url("play").'">start a new game by yourself</a>.'
			),$this->data);

		if( $game_id = $this->game_model->is_available( $slug ) ){

			$joinresult = $this->game_model->attemptToJoin( $slug , $this->tank_auth->get_user_id() );

			if($joinresult === 1)
				$this->data['joinstatus'] = 'Joined!';
			elseif($joinresult === 2)
				$this->data['joinstatus']	= 'Already on the game';
			else
				$this->data['joinstatus']	= 'Could not join the game and user was not there already';

			$gamedata = $this->game_model->get( $game_id );

			$this->data['gamedata'] = $gamedata;

			$this->data['foot_jsvars'] = array(
				array(
					'name'	=>	'me',
					'data'	=>	$this->tank_auth->get_username()
				),
				array(
					'name'	=>	'gamedata',
					'data'	=>	$gamedata
				),
				array(
					'name'			=>	'urls',
					'data'			=>	array(
						'keep_alive'	=>	site_url('play/keep_alive'),
						'start_game'	=>	site_url('play/start_game'),
						'push_move'		=>	site_url('play/push_move'),
						'play_home'		=>	site_url('play')
					)
				),
				array(
					'name'	=>	'autostart',
					'data'	=>	false
				)
			);

			$this->data['foot_jsfiles'] = array(
				base_url('js/ajaxqueue.js'),
				base_url('js/objeq.js'),
				'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js',
				base_url('js/play/multiplayer.js'),
				base_url('js/play/board.js')
			);

			$this->load->view('header', $this->data);
			if( $gamedata['metadata']->User_id === $this->tank_auth->get_user_id() ){
				$this->load->view('play/ownerbar', $this->data);
			}
			$this->load->view('play/joinerbar', $this->data);
			$this->load->view('play/multiplayer', $this->data);
			$this->load->view('footer');

		}
		elseif( $game_id = $this->game_model->is_returning($slug, $this->tank_auth->get_user_id())){
			$gamedata = $this->game_model->get( $game_id );

			$this->data['gamedata'] = $gamedata;

			$this->data['foot_jsvars'] = array(
				array(
					'name'	=>	'me',
					'data'	=>	$this->tank_auth->get_username()
				),
				array(
					'name'	=>	'gamedata',
					'data'	=>	$gamedata
				),
				array(
					'name'			=>	'urls',
					'data'			=>	array(
						'keep_alive'	=>	site_url('play/keep_alive'),
						'start_game'	=>	site_url('play/start_game'),
						'push_move'		=>	site_url('play/push_move')
					)
				),
				array(
					'name'	=>	'autostart',
					'data'	=>	true
				)
			);

			$this->data['foot_jsfiles'] = array(
				base_url('js/ajaxqueue.js'),
				base_url('js/objeq.js'),
				'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js',
				base_url('js/play/multiplayer.js'),
				base_url('js/play/board.js')
			);

			$this->load->view('header', $this->data);
			if( $gamedata['metadata']->User_id === $this->tank_auth->get_user_id() ){
				$this->load->view('play/ownerbar', $this->data);
			}
			$this->load->view('play/joinerbar', $this->data);
			$this->load->view('play/multiplayer', $this->data);
			$this->load->view('footer');
		}
		else{
			$this->load->view('general_message', array(
				'subject'	=>	'Oops! Invalid URL',
				'message'	=>	'The game you are trying to join has already been started or the URL expired, or it\'s not a valid one. <br/>Try again: Ask your friend for the correct URL, or <a href="'.site_url("play").'">start a new game by yourself</a>.',
				'username'	=>	$this->tank_auth->get_username()
			));			
		}

	}


	// ajax functions

	function create_multiplayer(){

		if( !$this->tank_auth->is_logged_in() ){
			$data['response'] = array(
				'success'	=>	false,
				'message'	=>	'User not logged in'
			);
		}
		else{
			$this->load->model('game_model');

			if( $slug = $this->game_model->set( $this->tank_auth->get_user_id() ) ){
				$data['response'] = array(
					'success'	=>	true,
					'slug'		=>	$slug
				);
			}
			else{
				$data['response'] = array(
					'success'	=>	false,
					'message'	=>	'Game could not be created.'
				);
			}
		}

		$this->load->view('throw_json', $data);

	}

	function can_i_join($slug = false){
		if( !$this->tank_auth->is_logged_in() ){
			$data['response'] = array(
				'success'	=>	false,
				'message'	=>	'User not logged in'
			);
		}
		else{
			$this->load->model('game_model');

			if( $slug = $this->game_model->is_available( $slug , $this->tank_auth->get_user_id() ) ){
				$data['response'] = array(
					'success'	=>	true,
					'message'	=>	'Game is available for you to join'
				);
			}
			else{
				$data['response'] = array(
					'success'	=>	false,
					'message'	=>	'The game URL is invalid, or it started already. Try creating a new one or check if the URL is correct.'
				);
			}
		}

		$this->load->view('throw_json', $data);
	}

	function start_game($slug = false){
		if(!$this->tank_auth->is_logged_in())
			die(json_encode(array('success'=>false,'message'=>'user not loggged in')));
		if($slug === false){
			$data['response'] = array(
				'success' => false,
				'message' => 'Invalid slug'
			);
		}
		elseif(!$this->input->post('metadata')){
			$data['response'] = array(
				'success' => false,
				'message' => 'No metadata was passed'
			);
		}
		else{
			$this->load->model('game_model');

			$metadata = $this->input->post('metadata'); // json encoded already

			// testing
			// $metadata = json_encode($metadata);

			if($this->game_model->start_game($slug, $this->tank_auth->get_user_id(), $this->tank_auth->get_username(), $metadata)){
				$data['response'] = array(
					'success' => true,
				);
			}
			else{
				$data['response'] = array(
					'success' => false,
					'message' => 'Invalid user to perform this operation'
				);
			}
		}
		$this->load->view('throw_json',$data);
	}

	function push_move($slug = false){
		if(!$this->tank_auth->is_logged_in())
			die(json_encode(array('success'=>false,'message'=>'user not logged in')));
		if($slug === false){
			$data['response'] = array(
				'success'	=>	false,
				'message'	=>	'Invalid slug'
			);
		}
		elseif( !$this->input->post('coords') ){
			$data['response'] = array(
				'success'	=>	false,
				'message'	=>	'Invalid slug'
			);
		}
		else{

			$this->load->model('game_model');

			// $obj = $this->game_model->push_move($slug, $this->tank_auth->get_user_id() ,$this->tank_auth->get_username(), $this->input->post('coords') );

			// $data['response'] = array(
			// 	'success'	=>	false,
			// 	'message'	=>	'testing',
			// 	'data'		=>	json_encode($obj)
			// );

			if( $this->game_model->push_move($slug, $this->tank_auth->get_user_id() ,$this->tank_auth->get_username(), $this->input->post('coords') ) ) {
				$data['response'] = array(
					'success'=>true
				);
			}
			else{
				$data['response'] = array(
					'success'=>false,
					'message'=>'Not pushed to the db'
				);
			}
		}
		$this->load->view('throw_json',$data);
	}

	function keep_alive($slug = false){
		ob_implicit_flush(true);
		if(!$this->tank_auth->is_logged_in())
			die(json_encode(array('success'=>false,'message'=>'user not logged in')));
		if($slug === false){
			$data['response'] = array(
				'success'	=>	false,
				'message'	=>	'Invalid slug'
			);
		}
		else{

			sleep(1);

			$this->load->model('game_model');

			if($this->input->post('data')){
				$metadata = $this->input->post('data');
			}		
			else{
				$metadata = false;
			}

			if($status = $this->game_model->keep_alive( $slug , $this->tank_auth->get_user_id() ) ){ 
				$data['response'] = array(
					'success'	=>	true,
					'data'		=>	$status
				);
			}
			else{
				$data['response'] = array(
					'success'	=>	false,
					'message'	=>	'Invalid request'
				);
			}
		}
		$this->load->view('throw_json',$data);
	}


}

/* End of file about.php */
/* Location: ./application/controllers/about.php */
<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Alumnos
 */

class Alumnos extends CI_Controller {

	function __construct(){
		parent::__construct();

		$this->load->helper('url');

		$this->load->library('tank_auth');

	}

	function index(){
		
		$this->load->view('alumnos');
		

	}

	// AJAX

	function insert(){

		// if(	! $this->input->post("data") )
		// 	die(json_encode(array("success"=>false,"message"=>"Bad request")));

		$obj = array(
			"nomAlumno"	=>	$this->input->post('nomAlumno'),
			"aPatAlumno"=>	$this->input->post('aPatAlumno'),
			"aMatAlumno"=>	$this->input->post('aMatAlumno'),
			'edadAlumno'=>	$this->input->post('aEdadAlumno'),
			'Campus'	=>	$this->input->post('Campus'),
			'Titulacion'=>	$this->input->post('Titulacion'),
			'Curso'		=>	$this->input->post('Curso')
		);

		$this->load->model('alumnos_model');

		if($this->alumnos_model->insert($obj)){
			$data['response'] = array(
				'success'	=>	true
			);
		}
		else{
			$data['response'] = array(
				'success'	=>	false,
				'message'	=>	'Hubo error al insertar'
			);
		}

		$this->load->view('throw_json',$data);

	}


}

/* End of file alumnos.php */
/* Location: ./application/controllers/alumnos.php */
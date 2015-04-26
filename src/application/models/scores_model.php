<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/**
	* Scores
	*/
	class Scores_model extends CI_Model{
		
		function __construct(){

			$this->load->database();
		}

		function get($limit = 10){

			$query = 	"select users.username, thescores.thesum "
						."from ("
							."select GamePlayers.User_id, sum(GamePlayers.Score) as thesum "
							."from GamePlayers group by (User_id) "
							."order by thesum desc limit ".$limit
						.") as thescores "
						."inner join users on thescores.User_id = users.id";

			$q = $this->db->query($query);

			return $q->result();

		}


	}

/* End of file scores_model.php */
/* Location: ./application/controllers/scores_model.php */
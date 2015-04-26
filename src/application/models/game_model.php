<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	/**
	* Alumnos
	*/
	class Game_model extends CI_Model{
		
		function __construct(){
			$this->load->database();
		}


		function set($user = false){
			if($user === false)
				return false;

			$this->db->select('id, username');
			$this->db->from('users');
			$this->db->where('id', $user);
			$this->db->limit(1);
			$q = $this->db->get();
			if($q->num_rows() < 1)
				return false;
			
			$obj = array(
				'User_id'		=>	$q->first_row()->id,
				'Slug'			=>	$this->generateSlug(),
				'CurrentPlayer'	=>	$q->first_row()->username
			);

			$this->db->trans_start();

			if( $this->db->insert('Games', $obj) ){

				if($this->db->insert('GamePlayers', array(
					'User_id'	=>	$obj['User_id'],
					'Game_id'	=>	$this->db->insert_id(),
					'Metadata'	=>	'created, joined',
					'HexColor'	=>	$this->generateColor($this->db->insert_id())
				))){
					$this->db->trans_complete();
					return $obj['Slug'];
				}

			}

			return false;

		}

		function get($id = false){
			if($id === false)
				return false;

			$this->db->from('Games');
			$this->db->where('GameId',$id);
			$this->db->limit(1);

			$q = $this->db->get();

			if($q->num_rows() > 0){
				$gamedata = array(
					'metadata'	=>	$q->first_row()
				);

				$this->db->select('
					users.username, 
					GamePlayers.Joined, 
					GamePlayers.HexColor, 
					GamePlayers.Score,
					GamePlayers.Ping');
				$this->db->from('GamePlayers');
				$this->db->join('users', 'GamePlayers.User_id = users.id', 'left');
				$this->db->where('GamePlayers.Game_id', $id);
				$this->db->order_by('GamePlayers.Joined', 'asc');
				$q = $this->db->get();

				$gamedata['players'] = $q->result();

				return $gamedata;

			}

			return false;

		}

		function get_from_slug($slug = false){
			if($slug === false)
				return false;

			$this->db->from('Games');
			$this->db->where('Slug',$slug);
			$this->db->limit(1);

			$q = $this->db->get();

			if($q->num_rows() > 0)
				return $q->first_row();

			return false;

		}

		function start_game($slug = false, $user_id = false, $username = false, $metadata = false){
			if($slug === false || $user_id === false)
				return false;

			if( ! $game_id = $this->is_available($slug) )
				return false;

			$this->db->select('User_id');
			$this->db->from('Games');
			$this->db->where('GameId',$game_id);
			$this->db->limit(1);
			$q = $this->db->get();

			if($q->num_rows() < 1)
				return false;

			if($q->first_row()->User_id === $user_id){ // is the owner, can start the game

				$this->db->where('GameId',$game_id);

				if($this->db->update('Games', array(
					'Status'		=>	1,					// game started
					'metadata'		=>	$metadata,			// the metadata
					'StateChanged'	=>	date("Y-m-d H:i:s") // last time metadata updated
				))){
					return true;
				}
				return false;
			}

			return false;
		}

		function push_move( $slug = false, $user_id = false, $username = false, $throw = false ){
			if($slug === false || $user_id === false)
				return false;

			if( $this->is_available($slug) )
				return false;

			$this->db->select('GameId, CurrentPlayer, Metadata');
			$this->db->from('Games');
			$this->db->where('Slug', $slug);
			$this->db->limit(1);
			$q = $this->db->get();

			if( $username === $q->first_row()->CurrentPlayer ){
				
				$game_id = $q->first_row()->GameId;

				$obj = json_decode($q->first_row()->Metadata);

				// return $obj; // testing

				$asumeAllChecked = true;

				$instance_score = 0;

				foreach($obj->boxes as $box){
					if( $box->owner === null ){

						// check boxes

						if($throw === $box->coords->top)
							$box->checks->top = true;
						elseif($throw === $box->coords->right)
							$box->checks->right = true;
						elseif($throw === $box->coords->bottom)
							$box->checks->bottom = true;
						elseif($throw === $box->coords->left)
							$box->checks->left = true;
						
						// check validity

						if(
								$box->checks->top 		=== true
							&& 	$box->checks->right 	=== true
							&&	$box->checks->bottom 	=== true
							&&	$box->checks->left 		=== true
						){
							$box->owner = $username;
							$instance_score++;
						}
						else{
							$asumeAllChecked = false;
						}
					}
				}

				$game_state = 1;

				if($asumeAllChecked === true){
					$game_state = 2;
				}
				elseif($instance_score === 0){
					$current_player = $obj->gameState->player;
					$new_player = "";
					for($i=0;$i<count($obj->players);$i++){
						if($obj->players[$i]->username === $current_player){
							if( ( $i+1 ) === count($obj->players) ){
								$new_player = $obj->players[0]->username;
							}
							else{
								$new_player = $obj->players[$i+1]->username;
							}
						}
					}
					$obj->gameState->player = $new_player;
				}

				$new_metadata = json_encode($obj);

				$this->db->trans_start();

				$this->db->where('Slug',$slug);

				$new_status = array(
					'Status'		=>	$game_state,
					'Metadata'		=>	$new_metadata,
					'CurrentPlayer'	=>	$obj->gameState->player,
					'StateChanged'	=>	date('Y-m-d H:i:s')
				);

				if($game_state === 2){
					$new_status['GameEnd'] = date('Y-m-d H:i:s');
				}

				if($this->db->update('Games',$new_status)){
					if($instance_score > 0){
						$query = "update GamePlayers set Score = Score + ".$instance_score
								." where Game_id = ".$game_id." and User_id = ".$user_id;

						if($this->db->query($query)){
							$this->db->trans_complete();
							return true;
						}
						return false;
					}
					else{
						$this->db->trans_complete();
						return true;
					}

				}

				return false;
			}
			return false;



		}

		function keep_alive($slug = false, $user_id = false ){
			if($slug === false || $user_id === false)
				return false;

			// TODO: Check if is current user, update metadata if so, and change to next user
			// do it server side then client side then test
 
			$this->db->select('*'); // TODO: selectiveness
			$this->db->from('Games');
			$this->db->where('Slug', $slug);
			$this->db->limit(1);

			$q = $this->db->get();

			if($q->num_rows() < 1)
				return false;

			$game_id = $q->first_row()->GameId;
			$data['metadata'] = $q->first_row();

			if(!$this->db->query('update GamePlayers set Ping = now() where Game_id = '.$game_id.' and User_id = '.$user_id))
				return false;

			$this->db->select('
				users.username, 
				GamePlayers.Joined,
				GamePlayers.Ping, 
				GamePlayers.HexColor, 
				GamePlayers.Score');
			$this->db->from('GamePlayers');
			$this->db->join('users', 'GamePlayers.User_id = users.id', 'left');
			$this->db->where('GamePlayers.Game_id', $game_id);
			$this->db->order_by('GamePlayers.Joined', 'asc');

			$q = $this->db->get();

			if($q->num_rows() < 1)
				return false;

			$data['players'] = $q->result();

			$this->db->where('Game_id',$game_id);
			$this->db->where('User_id',$user_id);

			return $data;
		}

		// if game is available, returns game id
		function is_available($slug = false){
			if($slug === false)
				return false;

			$this->db->select('GameId');
			$this->db->from('Games');
			$this->db->where('Slug', $slug);
			$this->db->where('Status', 0); // 0 is available, 1 is started .. TODO: decide if 2 is finished (and/or 3 is truncated)
			$this->db->limit(1);

			$q = $this->db->get();

			if($q->num_rows() > 0)
				return $q->first_row()->GameId;

			return false;

		}

		function is_returning($slug = false, $user_id = false){
			if($slug === false || $user_id === false)
				return false;

			$this->db->select('GameId');
			$this->db->from('Games');
			$this->db->where('Slug', $slug);
			$this->db->where('Status', 1);
			$this->db->limit(1);

			$q = $this->db->get();

			if($q->num_rows() < 1)
				return false;

			$game_id = $q->first_row()->GameId;

			$this->db->select('Game_id');
			$this->db->from('GamePlayers');
			$this->db->where('Game_id', $game_id);
			$this->db->where('User_id', $user_id);
			$this->db->limit(1);

			$q = $this->db->get();

			if($q->num_rows() > 0)
				return $q->first_row()->Game_id;
			return false;

		}

		function attemptToJoin($slug = false, $user_id = false){
			if($slug === false || $user_id === false)
				return false;

			if( ! $game_id = $this->is_available($slug) )
				return false;

			$this->db->select('User_id');
			$this->db->from('GamePlayers');
			$this->db->where('Game_id',$game_id);
			$this->db->where('User_id',$user_id);
			$this->db->limit(1);

			$q = $this->db->get();

			if($q->num_rows() > 0)
				return 2; // already there

			if($this->db->insert('GamePlayers', array(
				'User_id'		=>	$user_id,
				'Game_id'		=>	$game_id,
				'Metadata'		=>	'joined',
				'HexColor'		=>	$this->generateColor($game_id)
			)))
				return 1; // just joined

			return false;

		}

		// private functions:

		private function generateSlug($length = 16){
			
			$arr = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','0','1','2','3','4','5','6','7','8','9');
			
			$st = "";
			
			for($i = 0; $i < $length; $i++)
				$st.= $arr[ array_rand( $arr ) ];

			$this->db->select('slug');
			$this->db->from('Games');
			$this->db->where('slug',$st);
			$this->db->limit(1);
			$q = $this->db->get();

			if($q->num_rows() > 0)
				$this->generateSlug($length);

			return $st;

		}

		private function generateColor($game_id = false){
			if($game_id === false)
				return false;
			$arr = array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f');
			$st = "";
			for($i = 0; $i < 6; $i++)
				$st.= $arr[array_rand($arr)];
			$this->db->select('HexColor');
			$this->db->from('GamePlayers');
			$this->db->where('Game_id',$game_id);
			$this->db->where('HexColor',$st);
			$this->db->limit(1);
			$q = $this->db->get();
			if($q->num_rows() > 0)
				$this->generateColor($game_id);
			return $st;
		}


	}

/* End of file game_model.php */
/* Location: ./application/models/game_model.php */
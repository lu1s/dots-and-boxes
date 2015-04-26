	// HELPERS //

	String.prototype.toArray = function(){
		return this.split("");
	}

	String.prototype.toContextArray = function(){
		var arr = this.split(",");
		for(var i in arr)
			arr[i] = parseInt(arr[i]);
		return arr;
	}


	// END HELPERS //


	function Game(width, height, alldata){

		this.width = 9; // 9-1 = 8
		this.height = 9;
		if(typeof width === "number" && width >= 3){
			this.width = Math.round(width) + 1;
			if(typeof height === "number" && height >= 3)
				this.height = Math.round(height) + 1;
			else
				this.height = Math.round(width) + 1;
		}
		this.boxes = [];
		this.players = [];
		var height = this.height;
		var width = this.width;
		for(var i=1; i<((height*2)-1);i+=2){
			for(var j=0; j<(width-1);j++){
				this.boxes.push({
					coords: {
						top:(i-1)+","+j,
						right:i+","+(j+1),
						bottom:(i+1)+","+j,
						left:i+","+j
					},
					checks: {
						top:false,
						right:false,
						bottom:false,
						left:false
					},
					owner:null
				});
			}
		}

		this.init(alldata);
	}

	Game.prototype = {
		constructor: Game,
		player: {
			
			players: [],

			getColor: function(player){
				for(var i in this.players){
					if(this.players[i].username === player)
						return this.players[i].HexColor;
				}
				return false;
			},

			score: function(player, box_amount){
				for(var i in this.players){
					if(this.players[i].username === player){
						this.players[i].Score+=box_amount;
						return true;
					}
				}
				return false;
			}

		},
		init: function(alldata){

			for(var i in alldata.players){
				this.player.players.push({
					HexColor: alldata.players[i].HexColor,
					username: alldata.players[i].username,
					Score: 0
				});
				this.players.push(alldata.players[i].username)
			}

			this.gameState = {
				player: this.player.players[0].username
			}

		},
		changePlayer: function(){
			for(var i=0; i< this.player.players.length; i++){
				if( this.player.players[i].username === this.gameState.player ){
					if(this.player.players.length === (i+1)){
						this.gameState.player = this.player.players[0].username;
						return;
					}
					else{
						var next = parseInt(i+1);
						this.gameState.player = this.player.players[i+1].username;
						return;
					}

				}
			}
		},
		move: function(coords){
			var created = [];
			for(var i in this.boxes){
				if(this.boxes[i].coords.top === coords)
					this.boxes[i].checks.top = true;
				else if(this.boxes[i].coords.right === coords)
					this.boxes[i].checks.right = true;
				else if(this.boxes[i].coords.bottom === coords)
					this.boxes[i].checks.bottom = true;
				else if(this.boxes[i].coords.left === coords)
					this.boxes[i].checks.left = true;
				if( this.boxes[i].owner === null && this.boxes[i].checks.top && this.boxes[i].checks.right && this.boxes[i].checks.bottom && this.boxes[i].checks.left ){
					this.boxes[i].owner = this.gameState.player;
					created.push(i);
				}
			}
			if(created.length > 0){
				this.player.score(this.gameState.player,created.length);
				return created;
			}
			// this.changePlayer();
			return false;
		}
	}


	function Board(width, height){
		this.width = 8;
		this.height = 8;
		if(typeof width === "number" && width >= 3){
			this.width = Math.round(width);
			if(typeof height === "number" && height >= 3)
				this.height = Math.round(height);
			else
				this.height = Math.round(width);
		}

		this.init();

	}
	Board.prototype = {
		constructor: Board,
		/**
		*	Will build the HTML structure of the board
		*/
		build: function(){
			var width = this.width,
				height = this.height,
				board = $("<div/>").addClass("board"),
				table = "<table><tbody>",
				boxcount = 1;
			for(var i = 0;i<(height*2);i++){

				if(i%2 === 0){
					table+="<tr>";
					for(var j = 0;j<width;j++){
						table+="<td class='dot'></td>";
						j<(width-1) ? table+="<td class='h_line line' coords='"+i+","+j+"'></td>" : null;
					}
					table+="</tr>";					
				}
				else if(i<((height*2)-1)){
					table+="<tr>";
					for(var j = 0;j<width;j++){
						table+="<td class='v_line line' coords='"+i+","+j+"'></td>";	
						j<(width-1) ? table+="<td class='box' top='"+(i-1)+","+j+"' right='"+i+","+(j+1)+"' bottom='"+(i+1)+","+j+"' left='"+i+","+j+"'></td>" : null;
					}
					table+="</tr>";
				}

			}
			table+="</tbody></table>";
			board.html(table);
			$(".canvas").html(board);

			this.updateScores();

			$("#player_"+game.gameState.player+" > span.current_user").fadeIn();
		},
		bind: function(){
			var self = this;
			$(".h_line, .v_line").bind("click",function(){

				if(!$(this).hasClass("selected")){

					$(this).stop().attr("active","active").animate({backgroundColor:"#444"},1000,function(){
						$(this).addClass("selected");
					});

					// logic

					var created = game.move( $(this).attr("coords") );
					if(typeof created === "object"){ // true if completed box
						var color = game.players.getColor(game.gameState.player);
						$.each(created,function(index,value){
							$(".box:eq("+value+")").animate({backgroundColor:"#"+color},500);
						});
						// self.updateScores();
					}
					else{
						$(".player > span.current_user").fadeOut(function(){
							$("#player_"+game.gameState.player+" > span.current_user").fadeIn();
						});
					}

					// endlogic

				}



			}).bind("mouseenter",function(){
				$(this).attr("active") ? null : $(this).stop().animate({backgroundColor:"#aaa"},50).css({cursor:"pointer"});
			}).bind("mouseleave",function(){
				$(this).attr("active") ? null : $(this).stop().animate({backgroundColor:"#fff"},400).css({cursor:""});
			})
		},

		// prepare to handle bulk updates

		updateScores: function(){
			for(var i in game.player.players){
				if($("#player_"+game.player.players[i].username).length < 1){
					$(".users > div.clear").remove();
					$(".users").append(
						"<div id='player_"
							+ game.player.players[i].username + "' "
							+ "style='border-color:#"
							+ game.player.players[i].HexColor + "' "
							+ "color='" + game.player.players[i].HexColor + "' "
							+ "class='player'>"
								+ "<span class='username'>" + game.player.players[i].username + "</span>"
								+ "<br/><span class='score'>" + game.player.players[i].Score + "</span>"
								+ "<br/><span class='current_user'>[c]</span>"
						+ "</div>"
						+ "<div class='clear'></div>"
					);
				}
				$("#player_"+game.player.players[i].username+" > span.score").html(game.player.players[i].Score);
			}
		},

		init: function(){
			this.build();
			this.bind();
		}
	}

	var game,board;

	function initGame(){
		$(".whiteboard_message").html("");

		if($("div.controls").length > 0)
			$("div.controls").remove();

		$(this).parent().fadeOut(function(){$(this).remove()});

		game = new Game(8, 8, gamedata);

		board = new Board( game.width, game.height );

		window.location = "#main";
	}

	$(document).ready(function(){

		if(autostart === true){
			initGame();
		}

		$("button.start").bind("click",function(){
			// handle server make game unavailable .. already started game
			$(".whiteboard").slideUp(function(){
				initGame();

				var game_string = JSON.stringify(game);

				$.ajaxQueue({
					type: "POST",
					url: urls.start_game + '/' + gamedata.metadata.Slug,
					cache: false,
					data: {
						metadata: game_string
					},
					dataType: "json",
					success: function(data){
						if(data.success){
							$(".whiteboard").slideDown();
						}
					}
				});
			});

		});

	})
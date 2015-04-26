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

	function remotePush(coords, attempt){
		$.ajaxQueue({
			type: "POST",
			url: urls.push_move + '/' + gamedata.metadata.Slug,
			cache: false,
			data: {
				coords: coords
			},
			dataType: "json",
			success: function(data){
				if(data.success)
					console.log("pushed [" + coords + "] at attempt " + attempt + " at " + (new Date()).toLocaleTimeString());
				else if(attempt < 6){
					console.log("200 w error: ["+ data.message + "] at attempt" + attempt + " at " + (new Date()).toLocaleTimeString());
					attempt++;
					remotePush(coords,attempt);
				}
				else{
					console.log("final 200 w error: ["+ data.message + "] at attempt" + attempt + " at " + (new Date()).toLocaleTimeString());	
				}
			},
			error: function(data){
				if(attempt < 6){
					console.log("srv error: ["+ data.message + "] at attempt" + attempt + " at " + (new Date()).toLocaleTimeString());
					attempt++;
					remotePush(coords,attempt);
				}
				else{
					console.log("final srv error: ["+ data.message + "] at attempt" + attempt + " at " + (new Date()).toLocaleTimeString());
				}
			}
		});
	}


	// END HELPERS //


	function Game(width, height, alldata){

		this.width = 9; // 9-1 = 8
		this.height = 9;
		if(typeof width === "number" && width >= 2){
			this.width = Math.round(width) + 1;
			if(typeof height === "number" && height >= 2)
				this.height = Math.round(height) + 1;
			else
				this.height = Math.round(width) + 1;
		}
		this.boxes = [];
		this.players = [];
		this.player_string = [];
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
		},

		init: function(alldata){

			for(var i in alldata.players){
				this.players.push({
					HexColor: alldata.players[i].HexColor,
					username: alldata.players[i].username,
					Score: 0
				});
				this.player_string.push(alldata.players[i].username)
			}

			this.gameState = {
				player: gamedata.metadata.CurrentPlayer
			}

		},
		changePlayer: function(player){
			this.gameState.player = player;
		},
		updateGameState: function(st){
			var metadata = JSON.parse(st);
			if(this.gameState.player != metadata.gameState.player){
				this.changePlayer(metadata.gameState.player);
				board.changePlayer(metadata.gameState.player);
			}
			board.updateScores();
			this.boxes = metadata.boxes;
			board.updateBoxesAndLines();
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
				this.score(this.gameState.player,created.length);
				return created;
			}

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

					// remote push

					remotePush($(this).attr("coords"),0);

					// end remote push

					if(typeof created === "object"){ // true if completed box
						var color = game.getColor(game.gameState.player);
						$.each(created,function(index,value){
							$(".box:eq("+value+")").animate({backgroundColor:"#"+color},500);
						});
					}
					else{
						board.disable();
						$(".player > span.current_user").fadeOut();
					}

					// endlogic

				}

			}).bind("mouseenter",function(){
				$(this).attr("active") ? null : $(this).stop().animate({backgroundColor:"#aaa"},50).css({cursor:"pointer"});
			}).bind("mouseleave",function(){
				$(this).attr("active") ? null : $(this).stop().animate({backgroundColor:"#fff"},400).css({cursor:"default"});
			})
		},

		// prepare to handle bulk updates

		updateScores: function(){
			for(var i in game.players){
				if($("#player_"+game.players[i].username).length < 1){
					$(".users > div.clear").remove();
					$(".users").append(
						"<div id='player_"
							+ game.players[i].username + "' "
							+ "style='border-color:#"
							+ game.players[i].HexColor + "' "
							+ "color='" + game.players[i].HexColor + "' "
							+ "class='player'>"
								+ "<span class='username'>" + game.players[i].username + "</span>"
								+ "<br/><span class='score'>" + game.players[i].Score + "</span>"
								+ "<br/><span class='current_user'>[c]</span>"
						+ "</div>"
						+ "<div class='clear'></div>"
					);
				}
				$("#player_"+game.players[i].username+" > span.score").html(game.players[i].Score);
			}
		},

		updateBoxesAndLines: function(){
			for(var i in game.boxes){
				
				// checking edges

				var arr = ["top","right","bottom","left"];

				$.each(arr,function(index, value){
					if(game.boxes[i].checks[value] === true)
						$('.line[coords="'+game.boxes[i].coords[value]+'"]').stop().attr("active","active").animate({backgroundColor:"#444"},1000,function(){
							$(this).addClass("selected");
						});
					else $('.line[coords="'+game.boxes[i].coords[value]+'"]').removeClass("selected").removeAttr("active").css({backgroundColor:"#fff"});
				});



				// checking box owners
				if(game.boxes[i].owner != null){
					$(".box:eq("+i+")").animate({backgroundColor:"#"+game.getColor(game.boxes[i].owner)},500);
				}
				else{
					$(".box:eq("+i+")").css({backgroundColor:"#fff"});
				}
			}
		},

		changePlayer: function(){
			$(".player > span.current_user").fadeOut();
			$("#player_"+game.gameState.player+" > span.current_user").fadeIn();
			if(game.gameState.player === me){
				this.enable();
			}
			else{
				this.disable();
			}
		},

		enable: function(){
			this.bind();
		},

		disable: function(){
			$(".h_line, .v_line").unbind();
		},

		finishGame: function(){
			this.disable();
			var arr = [];
			$.each(game.players,function(index,value){
				arr.push(parseInt(value.Score));
			});
			var index = arr.indexOf(Math.max.apply(null,arr));
			$("#congratulations")
				.css({borderColor:"#"+game.getColor(game.players[index].username)})
				.html('<h1>'+game.players[index].username+' wins with '+game.players[index].Score+' boxes!</h1><br/><a href="'+urls.play_home+'">exit</a>')
				.fadeIn(700);
		},

		init: function(){
			this.build();
			this.changePlayer(); // to bind or not to bind
		}
	}

	var game,board;

	function initGame(){
		$(".whiteboard_message").html("");

		if($("div.controls").length > 0)
			$("div.controls").remove();

		$(this).parent().fadeOut(function(){$(this).remove()});

		game = new Game(8, 8, gamedata);

		// game = new Game(2, 2, gamedata);

		board = new Board( game.width, game.height );

		board.updateScores();

		$("#player_"+game.gameState.player+" > span.current_user").fadeIn();

		window.location = "#main";

		board.updateBoxesAndLines();
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

function stringToDate(string){
	var t = string.split(/[- :]/);
	return new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);
}


var cache=[];

function updatePlayerAliveStatuses(players){

	var tolerance = 3000; // in milliseconds

	for(var i in players){

		if($("#player_"+players[i].username).length === 0){
			$(".users > div.clear").remove();
			$(".users").append('<div class="player" color="'+players[i].HexColor+'" style="border-color:#'+players[i].HexColor+'" id="player_'+players[i].username+'"><span>'+players[i].username+'</span><br/><span class="score">0</span><br/><span class="current_user">[c]</span></div>')
			$(".users").append('<div class="clear"></div>');

		}

		if( players[i].Ping != '0000-00-00 00:00:00' && (new Date()).getTime() - stringToDate(players[i].Ping).getTime() > tolerance){
			if($("#player_"+players[i].username + " > .error").length === 0){
				$("#player_"+players[i].username).css({
					color:"white",
					backgroundColor:"red"
				}).append("<div class='error'><span>waiting...</span></div>");				
			}
		}
		else if($("#player_"+players[i].username + " > .error").length > 0){
			$("#player_"+players[i].username + " > .error").remove();
			$("#player_"+players[i].username).css({
				color:"#333",
				backgroundColor: "white"
			});
		}

	}
}

var datacache;

function processDataCache(original_obj,truncate){
	var obj = original_obj;
	for(var i in obj.players)
		delete obj.players[i].Ping;
	if(truncate)
		return obj;
	datacache = obj;
}

function updateGameState(data){
	if(!objeq(processDataCache(data,true),datacache)){
		if(gameStarted === true){
			game.players = data.players;
			game.updateGameState(data.metadata.Metadata);
		}
		else{
			gamedata.players = data.players;
		}
	}
	if(data.metadata.Status === "2"){
		board.finishGame();
		keepAlive = function(){};
	}
}

var gameStarted = false;

function keepAlive(){

	$.ajaxQueue({
		type: "GET",
		url: urls.keep_alive + '/' + gamedata.metadata.Slug,
		cache: false,
		dataType: "json",
		success: function(data){
			if(data.success){
				updatePlayerAliveStatuses(data.data.players); // notify of offline players
				updateGameState(data.data); // updates the state in case is different from past request
				processDataCache(data.data); // updates the cache to hold current state for the next request
				if(!gameStarted && (data.data.metadata.Status === 1 || data.data.metadata.Status === "1")){
					initGame();
					gameStarted = true;
				}
				keepAlive(); // recursively call this function
			}
			else{
				console.log(data); // log a server-side generated error message
			}
		},
		error: function(){
			keepAlive(); // skip everything for this case and try again
		}
	});

}

$(document).ready(function(){

	$("#game_url").on("click",function(){
		$(this).select();
		$(this).on("mouseup",function(e){
			e.preventDefault();
		})
	})
	
	processDataCache(gamedata);

	keepAlive();

});
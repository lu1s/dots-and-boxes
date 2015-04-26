<div class="main">
	<div class="info">
		<fieldset>
			<legend>Play Now!</legend>

				<div class="playnow">

					<div class="link">
						<a id="multiplayer" href="<?php echo site_url('play/create_multiplayer') ?>">Create a new game</a>
					</div>
					<div class="or">
						or
					</div>
					<div class="link">
						<label>join one: </label>
						<input id="slug" type="text" /><button id="joingame">Join</button><br/>
						<div class="joinerror"></div>
					</div>
					<div class="or">
						or
					</div>
					<div class="link">
						<a id="multiplayer" href="<?php echo base_url('repo/Dots-And-Boxes.exe') ?>">download the desktop version <br/> and play against your computer.<span style="font-size:14px"><br/>Only Microsoft Windows 7 or greater is supported</span></a>
					</div>

				</div>

		</fieldset>
	</div>

	<div class="message"></div>

</div>
<script type="text/javascript">

	function joinerror(msg){
		$(".joinerror").text(msg).slideDown(function(){
			$(this).delay(2000).slideUp();
		})
	}

	function attemptToJoin(){
		var s = $("#slug").val();
		if(s === ""){
			joinerror("Paste the URL or the game code first.");
			return false;
		}
		$("#joingame").text("Joining...").attr("disabled","disabled");
		if("http" === s.substr(0,4)){
			s = s.substr(s.length-16, s.length); // harcoded the slug length, which would always be 16
		}
		$.getJSON("<?php echo site_url('play/can_i_join'); ?>" + "/" + encodeURI(s), function(data){
			if(data.success){
				window.location = "<?php echo site_url('play/multiplayer'); ?>" + "/" + encodeURI(s);
			}
			else{
				joinerror(data.message);
				$("#joingame").text("Join").removeAttr("disabled");
			}
		})
	}

	$(document).ready(function(){

		$(".playnow > div").each(function(index){
			$(this).css({marginLeft: ( ( index * 40 ) + 50 ).toString() + "px" })
		});

		$("#multiplayer").bind("click",function(e){
			e.preventDefault();

			$(".message").text("loading...").slideDown();

			$.getJSON("<?php echo site_url('play/create_multiplayer'); ?>",function(data){
				if(data.success){

					setCookie('session_token', data.slug);

					window.location = "<?php echo site_url('play/multiplayer'); ?>" + "/" + data.slug;

				}
				else{
					console.log(data.message); // debug
					$(".message").slideUp(function(){
						$(this).text("");
					});
				}
			});

		});


		$("#joingame").bind("click",function(){
			attemptToJoin();
		});

		$("#slug").bind("keyup",function(e){
			if(e.keyCode === 13){
				attemptToJoin();
			}
		})

	});

</script>
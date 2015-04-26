

		<div class="title">Login to play!</div><br/><br/>
		<div class="message_login">To play Dots&amp;Boxes you need to be logged into your account.
Click on the upper right box to register for free, or to log in.</div>

<script type="text/javascript">
	$(document).ready(function(){

		<?php if(isset($slug)): ?>
			setCookie('waiting_slug', "<?php echo $slug; ?>");
		<?php endif; ?>

		$(".notsigned > a:first").fancybox({
			type: "iframe",
			width: 400,
			height: 300,
			onClosed: function(){
				$.getJSON(urls.get_username, function(data){
					if(data.success){
						if(getCookie('waiting_slug')){
							var url = "<?php echo site_url('play/multiplayer'); ?>" + "/" + getCookie('waiting_slug');
							deleteCookie("waiting_slug");
							window.location = url;
						}
						else{
							window.location = "<?php echo site_url('play'); ?>"
						}
					}
				});
			}
		}).trigger("click");
	});
</script>
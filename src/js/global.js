function fixFooter(){
	$("#main").css({
		minHeight:(window.innerHeight - ($("#header").height() + $("#footer").height()) - 300 ).toString() + "px"});
}

function getUsername(){
	$.getJSON(urls.get_username, function(data){
		if(data.success){
			$(".account").html('Welcome, <a href="' 
								+ urls.profile 
								+ '">' 
								+ data.username 
								+ '</a>. | <a href="' 
								+ urls.logout 
								+ '">logout</a>');
		}
		else{
			console.log(  (data.message ? data.message : 'Response not successful')  );
		}
	});
}


$(document).ready(function(){
	fixFooter();

	$(".notsigned > a:first").fancybox({
		type: "iframe",
		width: 400,
		height: 300,
		onClosed: function(){
			getUsername();
		}
	});

	$(".notsigned > a:last").fancybox({
		type: "iframe",
		width: 550,
		onClosed: function(){
			getUsername();
		}
	});

});
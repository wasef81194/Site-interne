$(document).ready(function(){
    $('.formCall').ready(function () {
        $('#loadingCall').hide();		
    });
	$(".formCall").submit(function(){
		$('#loadingCall').show();
		$('.cardCallNew').hide();
        $('#hideCall').hide();
    });
    var sucess = document.getElementById("sucessCall");
    if(sucess){
        $('.formCall').hide();
        $('#sucess').hide();
        $('#loadingCall').show();
        setTimeout(redirection, 6000);
    }
});


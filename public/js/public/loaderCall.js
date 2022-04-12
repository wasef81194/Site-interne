$(document).ready(function(){
    $('.formCall').ready(function () {
        console.log('ready');
        $('#loadingCall').hide();
        console.log('loding hide');		
    });
	$(".formCall").submit(function(){
		$('#loadingCall').show();
		$('.cardCallNew').hide();
        $('#hideCall').hide();
    });
    var sucess = document.getElementById("sucessCall");
    if(sucess){
        console.log(sucess);
        $('.formCall').hide();
        $('#sucess').hide();
        $('#loadingCall').show();
        setTimeout(redirection, 6000);
    }
});


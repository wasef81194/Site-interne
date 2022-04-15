$(document).ready(function(){
    $('.formRdv').ready(function () {
        $('#loadingRdv').hide();	
    });
	$(".formRdv").submit(function(){
		$('#loadingRdv').show();
		$('.cardRdvNew').hide();
        $('#hideRdv').hide();
    });
    var sucess = document.getElementById("sucessRdv");
    if(sucess){
        $('.formRdv').hide();
        $('#sucess').hide();
        $('#loadingRdv').show();
        setTimeout(redirection, 6000);
    }
});

console.log('RDVVVV');
$(document).ready(function(){
    $('.formRdv').ready(function () {
        console.log('ready');
        $('#loadingRdv').hide();
        console.log('loding hide');		
    });
	$(".formRdv").submit(function(){
		$('#loadingRdv').show();
		$('.cardRdv').hide();
        $('#hideRdv').hide();
    });
    var sucess = document.getElementById("sucessRdv");
    if(sucess){
        console.log(sucess);
        $('.formRdv').hide();
        $('#sucess').hide();
        $('#loadingRdv').show();
        setTimeout(redirection, 6000);
    }
});

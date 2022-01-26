function redirection() {
    document.location.href="http://azertyfrance.fr"; 
}

$(document).ready(function(){
    $('.form').ready(function () {
        console.log('ready');
        $('#loading').hide();		
    });
	$(".form").submit(function(){
		$('#loading').show();
		$('.card').hide();
    });
    var sucess = document.getElementById("sucess");
    if(sucess){
        console.log(sucess);
        $('.cacher').hide();
        $('#sucess').show();
        setTimeout(redirection, 6000);
    }
    
});
   


function redirection() {
    document.location.href="http://azertyfrance.fr"; 
}
$(document).ready(function(){
    $('.form').ready(function () {
        //console.log('ready');
        $('#loadingDepot').hide();	
    });
	$(".form").submit(function(){
		$('#loadingDepot').show();
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
   


function redirection() {
    document.location.href="http://azertyfrance.fr"; 
}
$(document).ready(function(){
    $('.form').ready(function () {
        $('#loadingDepot').hide();	
    });
	$(".form").submit(function(){
		$('#loadingDepot').show();
		$('.card').hide();
        $('#hide').hide();
    });
    var sucess = document.getElementById("sucess");
    if(sucess){
        $('.cacher').hide();
        $('#sucess').show();
        setTimeout(redirection, 6000);
    }
    
});
   


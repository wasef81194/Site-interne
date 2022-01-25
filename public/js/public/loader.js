$(document).ready(function(){
    $('.form').ready(function () {
        console.log('ready');
        $('#loading').hide();		
    });
	$(".form").submit(function(){
		$('#loading').show();
		$('.card').hide();
    });
});


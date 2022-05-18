
$(document).ready(function(){	
    $('.formCall').ready(function () {
        $('.loaderAllClients').hide();
        $('.allClients').show();	
    });
    //$(document).on('change', '#janvier,#fevrier,#mars,#avril,#mai,#juin,#juillet,#aout,#septembre,#octobre,#novembre,#decembre', function(){
       // document.getElementById('filtreMonth').submit();
       $("#filtreMonth").submit(function(){
            $('.allClients').hide();
            $('.loaderAllClients').show();
        });
    //})
})
$(document).ready(function(){
    $(".retour").mouseover(function(){
        $(this).attr('src', '../../images/retour-hover.png');
    })
    $(".retour").mouseout(function(){
        $(this).attr('src', '../../images/retour.png');
    })
});

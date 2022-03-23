$(document).ready(function(){
    console.log('retourWork');
    $(".retour").mouseover(function(){
        console.log('retour');
        $(this).attr('src', '../../images/retour-hover.png');
    })
    $(".retour").mouseout(function(){
        console.log('retour');
        $(this).attr('src', '../../images/retour.png');
    })
});

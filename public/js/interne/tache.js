$(document).ready(function(){
    var lignes = document.querySelectorAll('.ligne_tache');
    for (ligne of lignes) {
        var thisLigne = ligne.childNodes;
        var doTache = thisLigne[9].lastElementChild.attributes.value.value; 
        if(doTache) {
            ligne.classList.add('completed');
        }
    }

})
$(document).ready(function(){
    var lignes = document.querySelectorAll('.ligne_tache');
    var tables = document.querySelectorAll('.tableau-tache');
    for (ligne of lignes) {
        var thisLigne = ligne.childNodes;
        var doTache = thisLigne[9].lastElementChild.attributes.value.value; 
        if(doTache) {
            ligne.classList.add('completed');
        }
    }
    for (table of tables) {
        let tache = table.querySelector('.tache-faire').value
        if(tache) {
            table.classList.add('completed');
        }
    }


})
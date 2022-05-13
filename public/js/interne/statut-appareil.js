$(document).ready(function(){
    //console.log();
   // var statuts = document.querySelectorAll(".statutAppareil");
    var lignes = document.querySelectorAll(".ligne_client");
    //on récperer toute les lignes du tableau
    for(ligne of lignes){
        //on recuperer le statut de chaque ligne
        statut = ligne.childNodes
        // Pour chaque statut on lui attrbut une classe
        //console.log(statut[17].childNodes[1].innerText);
        if(statut[17].childNodes[1].innerText == 'Livré'){
            ligne.classList.add('completed');
            statut[17].childNodes[1].classList.add('bg-green-dark');
        }
        else if(statut[17].childNodes[1].innerText == 'Prêt à être récupéré') {
            statut[17].childNodes[1].classList.add('bg-green-light');
        }
        else if(statut[17].childNodes[1].innerText == 'Pris en charge'){
            statut[17].childNodes[1].classList.add('bg-yellow-dark');
        }
        else if(statut[17].childNodes[1].innerText == 'En attente de pièce'){
            statut[17].childNodes[1].classList.add('bg-purple-dark');
        }
        else if(statut[17].childNodes[1].innerText == 'Devis envoyée'){
            statut[17].childNodes[1].classList.add('bg-blue-light');
        }
        else if(statut[17].childNodes[1].innerText == 'En cours de réparation'){
            statut[17].childNodes[1].classList.add('bg-red-light');
        }
        
    }
    var modalBadges = document.querySelectorAll(".badge-modal");
    for(badge of modalBadges){
        //on recuperer le statut de chaque badge
        //console.log(badge.innerText);
        if(badge.innerText == 'Livré'){
            badge.classList.add('bg-green-dark');
        }
        else if(badge.innerText == 'Prêt à être récupéré') {
            badge.classList.add('bg-green-light');
        }
        else if(badge.innerText == 'Pris en charge'){
            badge.classList.add('bg-yellow-dark');
        }
        else if(badge.innerText == 'En attente de pièce'){
            badge.classList.add('bg-purple-dark');
        }
        else if(badge.innerText == 'Devis envoyée'){
            badge.classList.add('bg-blue-light');
        }
        else if(badge.innerText == 'En cours de réparation'){
            badge.classList.add('bg-red-light');
        }
        
    }
 
});
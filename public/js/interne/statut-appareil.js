$(document).ready(function(){
    //console.log();
   // var statuts = document.querySelectorAll(".statutAppareil");
    var lignes = document.querySelectorAll(".ligne_client");
    
    for(ligne of lignes){
        //console.log(ligne.childNodes);
        statut = ligne.childNodes

        
      
        //statut.omeParentFindMethod('tr');
       // console.log(statut);
        if(statut[17].childNodes[1].innerText == 'Livré'){
            console.log(statut[17].childNodes[1].innerText);
            //statut[17].childNodes[1].classList.add('visible');
            ligne.classList.add('completed');
            statut[17].childNodes[1].classList.add('bg-green');
            //statut[17].classList.add('alert');
        }
        else{
            statut[17].childNodes[1].classList.add('hidden');
        }
    }

 
});
$(document).ready(function(){
    var lignes = document.querySelectorAll(".ligne_client");
    //on récperer toute les lignes du tableau
    for(ligne of lignes){
        //on recuperer le statut de chaque ligne
        statut = ligne.childNodes
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
    colorStatut();
 
});
function colorStatut() {
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
}
//page all client
$(document).ready(function () {
    var lignes = document.querySelectorAll(".statutAppareilAll");
    //on récperer toute les lignes du tableau
    for (ligne of lignes) {
      //on recuperer le statut de chaque ligne
      var statut = ligne.childNodes;
      // Pour chaque statut on lui attrbut une classe
  
      if (statut[1].innerText == "Livré") {
        ligne.parentNode.classList.add("completed");
        statut[1].classList.add("bg-green-dark");
      } else if (statut[1].innerText == "Prêt à être récupéré") {
        statut[1].classList.add("bg-green-light");
      } else if (statut[1].innerText == "Pris en charge") {
        statut[1].classList.add("bg-yellow-dark");
      } else if (statut[1].innerText == "En attente de pièce") {
        statut[1].classList.add("bg-purple-dark");
      } else if (statut[1].innerText == "Devis envoyée") {
        statut[1].classList.add("bg-blue-light");
      } else if (statut[1].innerText == "En cours de réparation") {
        statut[1].classList.add("bg-red-light");
      }
    }
  });
  
//page index client
$(document).ready(function () {
  //Envoie du formulaire ChangeStatus

  $(document).on("submit", "#editFormStatut", function (e) {
    e.preventDefault();
    ajax_simple(this.action, $(this).serialize());
  });
  function ajax_simple(url, formData) {
    $.ajax({
      method: "POST",
      url: url,
      data: formData,
    })
      .done(function (response) {
          //on recupere l'id du client
         var clientID = formData.split("=")[5];
         console.log(formData);
          //Etat
            //on recupere l'id du statut envoyer
            var statut = formData.split("=")[2].split("&")[0];
            //on recupere le texte liées à l'id du statut
            var statuts = document.querySelector("#etat-select"+clientID)
            //on selectionne toute les endroit changer
            var elements = document.querySelectorAll("#change-statut"+clientID);
            for (element of elements) {
              //on change le statut par le nouveau
              console.log(element.innerText = statuts[statut-1].firstChild.textContent);
            }
            colorStatut()
      })
      .fail(function (jxh, textmsg) {
        console.log(url);
        console.log(textmsg);
        console.log(jxh);
      });
  }
});


$(document).ready(function(){
    //page index
    var lignes = document.querySelectorAll(".ligne_client");
    //on récperer toute les lignes du tableau
    for(ligne of lignes){
        //on recuperer le statut de chaque ligne
        statut = ligne.childNodes;
        if(statut[17].childNodes[1].innerText == 'Livré'){
            ligne.classList.add('completed');
        }
        ColoredStatut(statut[17].childNodes[1]);
    }
 
});
$(document).ready(function(){
  //modal ouvert
  var modalBadges = document.querySelectorAll(".modal-statut");
    for(badge of modalBadges){
      if (badge.innerText == "Pris en charge") {
        badge.classList.add("bg-yellow-dark");
      }
      ColoredStatut(badge);
    }
})
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
      }
      ColoredStatut(statut[1]);
    }
  });
  
//page index client
$(document).ready(function () {
  //Envoie du formulaire ChangeStatus

  $(document).on("submit", "#editFormStatut", function (e) {
    e.preventDefault();
    document.querySelector('.body').classList.add("cursor-loader");
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
          var clientID = formData.split("clientId=")[1];
  
            //Etat
            //on recupere l'id du statut envoyer
            var statut = formData.split("=")[2].split("&")[0];
            //on recupere le texte liées à l'id du statut
            var statuts = document.querySelector("#etat-select"+clientID)
            //on selectionne toute les endroit changer
            var elements = document.querySelectorAll("#change-statut"+clientID);
            for (element of elements) {
              //on change le statut par le nouveau
              document.querySelector('.body').classList.remove("cursor-loader");
              removeColoredStatut(element);
              element.innerText = statuts[statut-1].firstChild.textContent
              ColoredStatut(element);
            }
           // colorStatut()
      })
      .fail(function (jxh, textmsg) {
        document.querySelector('.body').classList.remove("cursor-loader");
        console.log(url);
        console.log(textmsg);
        console.log(jxh);
      });
  }
});

/***Function ****/

// Ajoute une class par rapport au statut
function ColoredStatut(statut){
  if (statut.innerText == "Livré") {
    statut.classList.add("bg-green-dark");
  } else if (statut.innerText == "Prêt à être récupéré") {
    statut.classList.add("bg-green-light");
  } else if (statut.innerText == "Pris en charge") {
    statut.classList.add("bg-yellow-dark");
  } else if (statut.innerText == "En attente de pièce") {
    statut.classList.add("bg-purple-dark");
  } else if (statut.innerText == "Devis envoyée") {
    statut.classList.add("bg-blue-light");
  } else if (statut.innerText == "En cours de réparation") {
    statut.classList.add("bg-red-light");
  }
}
// suprime la class par rapport au statut
function removeColoredStatut(statut){
  if (statut.innerText == "Livré") {
    statut.classList.remove("bg-green-dark");
  } else if (statut.innerText == "Prêt à être récupéré") {
    statut.classList.remove("bg-green-light");
  } else if (statut.innerText == "Pris en charge") {
    statut.classList.remove("bg-yellow-dark");
  } else if (statut.innerText == "En attente de pièce") {
    statut.classList.remove("bg-purple-dark");
  } else if (statut.innerText == "Devis envoyée") {
    statut.classList.remove("bg-blue-light");
  } else if (statut.innerText == "En cours de réparation") {
    statut.classList.remove("bg-red-light");
  }
}
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
              removeColoredStatut(element);
              element.innerText = statuts[statut-1].firstChild.textContent
              ColoredStatut(element);
            }

            //User
            //on recupere l'id de l'editeur selectionner
            var user = formData.split("user=")[1].split("&")[0];
            //on recupere le texte liées à l'id de tout les editeur
            var editeurs = document.querySelector("#editeur-select"+clientID)
            for (editeur of editeurs) {
              // si l'id correspond a celui selectionner on le stock dans une variable
              if(user==editeur.value){
                var edit  = editeur.innerText
              }
            }
            var editeurElements = document.querySelectorAll("#editeur-change"+clientID);
           
            for (element of editeurElements) {
              //on change l'éditeur par le nouveau par le nouveau
              removeColoredStatut(element);
              element.innerText = edit;
            }
            //Date
            //format de la date
            var options = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric'}
            var date = formData.split("date=")[1].split("&")[0];
            date = date.replaceAll('%2F', ' ');
            date = date.replaceAll('%20', ' ');
            date = date.replaceAll('%3A', ':');
            date = new Date(date);
            date = date.toLocaleDateString("fr-FR", options).replaceAll(',', ' à')
            //on selectionne toute les endroit changer
            var dateElements = document.querySelectorAll("#edit-date"+clientID);
            for (element of dateElements) {
              //on change la date par la nouvelle
              element.innerText = date;
            }
            //enleve le loader
           document.querySelector('.body').classList.remove("cursor-loader");
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
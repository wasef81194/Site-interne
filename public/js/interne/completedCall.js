$(document).ready(function(){
    let cards = document.querySelectorAll(".cardCall");
    reloadCard()

    //Boucle lorsqu'on click sur une checkbox d'une carte on fait un appel ajax
    for(card of cards){
      let form = card.querySelector('#formCallCompleted');
      let checkbox = card.querySelector("#checkCompleted");
      let path = card.querySelector("#pathAppel");
      if(checkbox){
        checkbox.addEventListener("click",function() {
         this.card = this.parentNode.parentNode.parentNode;
         // location.reload();
         ajax_cardCompleted(path.value,this,this.card);
        })
      }
    }
});

function ajax_cardCompleted(url,checkbox,card) {
  $.ajax({
    method: 'POST',
    url: url,
    dataType: "HTML",
  }).done( function(response) {
    
    //on efface la card de la div
    if(checkbox.checked) {
      // va dans historique
      // Si la case est cochée, on fait des traitements
      $(".historyCall").append(card.outerHTML);
    }
    else if(!checkbox.checked) {
      // Si la case n'est pas cochée
      $(".all-call").append(card.outerHTML);
    }
    $(card).fadeOut( "slow", function() {
      $( card ).remove();
    });
    reloadCard()
  }).fail(function(jxh,textmsg,errorThrown){
    console.log(textmsg);
    console.log(errorThrown);
  });
}

function reloadCard() {
  let history = document.querySelector(".historyCall");
    let allCall = document.querySelector(".all-call");

    if(history!== null){
    //toute les case de la div de droite sont cocher
      for(historyCall of history.children){
        let checkboxHistory = historyCall.querySelector("#checkCompleted");
        historyCall.classList.add('completed');
        if(checkboxHistory !== null){
          checkboxHistory.checked = true;
        }
      }
    }

    if(allCall!== null){
      //toute les case de la div de gauche sont décocher
      for( call of allCall.children){
        let checkboxCall = call.querySelector("#checkCompleted");
          if(checkboxCall !== null){
            checkboxCall.checked = false;
          }
      }
    }  
}
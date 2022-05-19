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
    ///on efface la card de la div
    $(card).fadeOut( "slow", function() {
      $( card ).remove();
    });
    console.log(card.outerHTML)
    
    if(checkbox.checked) {
      // va dans historique
      // Si la case est cochée, la carte va dans historique
      $(".historyCall").append('<div class="zoom">'+card.outerHTML+"</div>");
    }
    else if(!checkbox.checked) {
      // Si la case n'est pas cochée, la carte va dans la liste d'appel00
      $(".all-call").append('<div class="zoom">'+card.outerHTML+"</div>");
    }
    
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
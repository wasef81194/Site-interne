$(document).ready(function(){
    let cards = document.querySelectorAll(".cardCall");
    let history = document.querySelector(".historyCall");
    let allCall = document.querySelector(".all-call");

    //toute les case de la div de droite sont cocher
    for(historyCall of history.children){
      let checkboxHistory = historyCall.querySelector("#checkCompleted");
      historyCall.classList.add('completed');
      checkboxHistory.checked = true;
    }

    //toute les case de la div de gauche sont décocher
    for( call of allCall.children){
      let checkboxCall = call.querySelector("#checkCompleted");
      checkboxCall.checked = false;
    }

    //Boucle lorsqu'on click sur une checkbox d'une carte on fait un appel ajax
    for(card of cards){
      let form = card.querySelector('#formCallCompleted');
      let checkbox = card.querySelector("#checkCompleted");
      let path = card.querySelector("#pathAppel");
      if(checkbox){
        checkbox.addEventListener("click",function() {
         this.card = this.parentNode.parentNode.parentNode;
         // location.reload();
         console.log(this);
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
    $(card).fadeOut( "slow", function() {
      $( card ).remove();
    });
    if(checkbox.checked) {
      // Si la case est cochée, on fait des traitements
      $(".historyCall").append(card.outerHTML);
      console.log('check');
      card.classList.add('completed');
      console.log(card);
      
      
    }
    else if(!checkbox.checked) {
      // Si la case  n'est pas cochée
      console.log('not check');
      card.classList.remove('completed');
    }
    checkbox.checked = false;
   
  }).fail(function(jxh,textmsg,errorThrown){
    console.log(textmsg);
    console.log(errorThrown);
  });
}
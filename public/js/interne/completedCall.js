$(document).ready(function(){
    reloadCardCall()
    listenerCheckboxCall()
});

function ajax_cardCompleted(url,checkbox,card) {
  $.ajax({
    method: 'POST',
    url: url,
    dataType: "HTML",
  }).done( function(response) {
    ///on efface la card de la div
   
    
    if(checkbox.checked) {
      // va dans historique
      // Si la case est cochée, la carte va dans historique
      $(".historyCall").append('<div class="zoom">'+card.outerHTML+"</div>");
    }
    else if(!checkbox.checked) {
      // Si la case n'est pas cochée, la carte va dans la liste d'appel00
      $(".all-call").append('<div class="zoom">'+card.outerHTML+"</div>");
    }
    $(card).fadeOut( "slow", function() {
      $( card ).remove();
    });
    reloadCardCall()
    listenerCheckboxCall()
    document.querySelector('.body').classList.remove("cursor-loader");
  }).fail(function(jxh,textmsg,errorThrown){
    console.log(textmsg);
    console.log(errorThrown);
  });
}
function ajax_noReply(url,form,card) {
  $.ajax({
    method: "POST",
    url: url,
    data: $(form).serialize(),
  }).done( function(response) {
    ///on efface la card de la liste 
    $(card).fadeOut( "slow", function() {
      $( card ).remove();
    });
    $(".historyCall").append('<div class="zoom">'+card.outerHTML+"</div>");
    $(form).remove();
    
    reloadCardCall()
    listenerCheckboxCall()
    document.querySelector('.body').classList.remove("cursor-loader");
  }).fail(function(jxh,textmsg,errorThrown){
    console.log(textmsg);
    console.log(errorThrown);
  });
}
function listenerCheckboxCall(){
  let cards = document.querySelectorAll(".cardCall");
  
    //Boucle lorsqu'on click sur une checkbox d'une carte on fait un appel ajax
    for(card of cards){
      let form = card.querySelector('#formCallCompleted');
      let checkbox = card.querySelector("#checkCompleted");
      let path = card.querySelector("#pathAppel");
      let formNoReply = card.querySelector(".form-noreply");
      if(formNoReply){
        formNoReply.addEventListener("submit",function(e) {
         this.card = this.parentNode.parentNode.parentNode;
         e.preventDefault();
         document.querySelector('.body').classList.add("cursor-loader");
         ajax_noReply(this.action, this,this.card);
        })
      }
      if(checkbox){
        checkbox.addEventListener("click",function() {
         this.card = this.parentNode.parentNode.parentNode;
         // location.reload();
         document.querySelector('.body').classList.add("cursor-loader");
         ajax_cardCompleted(path.value,this,this.card);
        })
      }
    }
}
function reloadCardCall() {
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
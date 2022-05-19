$(document).ready(function(){
    let cards = document.querySelectorAll(".cardRdv");
    for(card of cards){
      let form = card.querySelector('#formRdvCompleted');
      let checkbox = card.querySelector("#checkCompleted");
      if(checkbox){
        if(checkbox.checked) {
          // Si la case est cochée, on fait des traitements
          card.classList.add('completed');
        }
        checkbox.addEventListener("click",function() {
          form.submit();
          if(checkbox.checked) {
            // Si la case est cochée, on fait des traitements
            this.classList.add('completed');
          }else if(!checkbox.checked) {
            // Si la case  n'est pas cochée
            this.classList.remove('completed');
          }
        })
      }
    }
});

function ajax_rdvCompleted(url,form,card) {
  $.ajax({
    method: "POST",
    url: url,
    data: $(form).serialize(),
  }).done( function(response) {
    ///on efface la card de la liste 
   
    document.querySelector('.body').classList.remove("cursor-loader");
  }).fail(function(jxh,textmsg,errorThrown){
    console.log(textmsg);
    console.log(errorThrown);
  });
}
function reloadCardRdv() {
  let history = document.querySelector(".historyCall");
  let allCall = document.querySelector(".all-call");
  if(history!== null){
    //toute les case de la div de droite sont cocher
      for(historyRdv of history.children){
        let checkboxHistory = historyRdv.querySelector("#checkCompleted");
        historyRdv.classList.add('completed');
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
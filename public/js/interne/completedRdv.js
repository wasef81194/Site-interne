$(document).ready(function(){
    listenerCheckboxRdv();
    reloadCardRdv();
});
// function
function ajax_rdvCompleted(url,form,card,checkbox) {
  $.ajax({
    method: "POST",
    url: url,
    data: $(form).serialize(),
  }).done( function(response) { 
    //var btnDelete = '<form method="post" action="/rdv/interne/'++'" onsubmit="return confirm(`Voulez-vous vraiment supprimer ce rendez-vous ?`);"> <input type="hidden" name="_token" value="5264386c0d1c099.bn2iqKbVeMI2lojlRfEqKOTRGEGqKLdHZciatNYtKfQ.Jx7xz9azF6pk2uaVAclIW5OgbHKaQfQFV_jNwr5LGpo7E8jJ6o098Wnd3Q"> <button class="btn btn-danger mr-2 "><img class="delete" src="/./images/icon-delete.png" width="20px"></button> </form>' 
    var badgeDate = card.querySelector(".badge-date");
    if(checkbox.checked) {
      // va dans historique
      
      if( badgeDate ){
        badgeDate.classList.add("collapse")
      }
      card.querySelector(".button-hidden").classList.add("collapse")
      
      // Si la case est cochée, la carte va dans historique
      $(".historyRdv").append('<div class="zoom">'+card.outerHTML+"</div>");
    }
    else if(!checkbox.checked) {
      if( badgeDate ){
        badgeDate.classList.remove("collapse")
      }
      card.querySelector(".button-hidden").classList.remove("collapse")
      // Si la case n'est pas cochée, la carte va dans la liste d'appel
      $(".all-rdv").append('<div class="zoom">'+card.outerHTML+"</div>");
    }
    ///on efface la card de la liste 
    $(card).fadeOut( "slow", function() {
      $( card ).remove();
    });
    reloadCardRdv()
    listenerCheckboxRdv()
    document.querySelector('.body').classList.remove("cursor-loader");
  }).fail(function(jxh,textmsg,errorThrown){
    console.log(textmsg);
    console.log(errorThrown);
  });
}

function listenerCheckboxRdv(){
  let cards = document.querySelectorAll(".cardRdv");
  for(card of cards){
    let checkbox = card.querySelector("#checkCompleted");
    let path = card.querySelector("#pathRdv");
    if(checkbox){
      checkbox.addEventListener("click",function() {
        this.card = this.parentNode.parentNode.parentNode.parentNode;
        // location.reload();
        document.querySelector('.body').classList.add("cursor-loader");
        ajax_rdvCompleted(path.value,this,this.card,checkbox);
      })
    }
  }
}

function reloadCardRdv() {
  let history = document.querySelector(".historyRdv");
  let allRdv = document.querySelector(".all-rdv");
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
  if(allRdv!== null){
    //toute les case de la div de gauche sont décocher
      for( rdv of allRdv.children){
        let checkboxRdv = rdv.querySelector("#checkCompleted");
          if(checkboxRdv !== null){
            checkboxRdv.checked = false;
          }
      }
  }  
}
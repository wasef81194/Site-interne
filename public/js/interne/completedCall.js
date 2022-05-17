$(document).ready(function(){
    let cards = document.querySelectorAll(".cardCall");
    for(card of cards){
      let form = card.querySelector('#formCallCompleted');
      let checkbox = card.querySelector("#checkCompleted");
      let path = card.querySelector("#pathAppel");
      if(checkbox){
        if(checkbox.checked) {
          // Si la case est cochée, on fait des traitements
          card.classList.add('completed');
        }
        checkbox.addEventListener("click",function() {
          console.log(path.value);
          if(checkbox.checked) {
            // Si la case est cochée, on fait des traitements
            console.log('check');
            this.classList.add('completed');
          }else if(!checkbox.checked) {
            // Si la case  n'est pas cochée
            console.log('not check');
            this.classList.remove('completed');
          }
          ajax_cardCompleted(path.value);
        })
      }
    }
});

function ajax_cardCompleted(url) {
  $.ajax({
    method: 'POST',
    url: url,
    dataType: "HTML",
  }).done( function(response) {
    location.reload();
  }).fail(function(jxh,textmsg,errorThrown){
    console.log(textmsg);
    console.log(errorThrown);
  });
}
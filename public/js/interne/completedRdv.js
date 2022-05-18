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

$(document).ready(function(){
    let cards = document.querySelectorAll(".cardCall");
    for(card of cards){
      let form = card.querySelector('#formCallCompleted');
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
            console.log('check');
            this.classList.add('completed');
          }else if(!checkbox.checked) {
            // Si la case  n'est pas cochée
            console.log('not check');
            this.classList.remove('completed');
          }
        })
      }
    }
});

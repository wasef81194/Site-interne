function close_alert() {
    $(".close_alert").alert('close')
}
$(document).ready(function(){
    setTimeout(close_alert, 5000);
});
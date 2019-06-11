document.onkeypress = enter;
function enter(){
  if( window.event.keyCode == 13 ){
    return false;
  }
}
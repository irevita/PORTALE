function toggleMenu() {
    var menu = document.querySelector('#menu')
    menu.classList.toggle('unvisible')
    menu.classList.toggle('visible')
}

// assegna al click sulle entry del menu, l'attivazione/disattivazione del relativo sottomenu, attraverso classi css
document.querySelectorAll('.list-item').forEach(element => {
    element.addEventListener("click", function(){
        element.classList.toggle('visible')
    })
})

//scegli tema 

var b = document.getElementById('color');
b.onclick = function(){
  let colorpicker = document.getElementById('colorpicker');
  setInterval(()=>{
    let color = colorpicker.value;
    document.body.style.backgroundColor = color;
}, 200);

}; 

var box = document.getElementsById("mieiblog");
function post(){
  //  var display=document.getElementById('nuovopost').style.display;
    //alert(display);
    //if (display=="none"){
    //    display="inline";
    //}else{
    //    display="none";
    //} 
    document.getElementById('nuovopost').style.display="inline";
}
document.getElementById("mieiblog").addEventListener("click", post); 
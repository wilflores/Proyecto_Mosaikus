/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


var p=0.01,t;
var p2=-70,t2;
var estado='visible';
var w_t;
var w=240;
function om(obj){
if(estado=='visible'){
estado='oculto';
p=0.01;
ocultar(obj,w);
}else{
estado='visible';
p2=w*-1;
mostrar(obj,w);
}
//alert(p);
}

/*
function ocultar(obj){
w_t=w;
if(p<(w*-1)){
clearTimeout(t);
return;
}
p-=5;
obj.style.left=p+'px';
//t=setTimeout( function(){ ocultar(obj,w_t);minimiza(); },30 );
t=setTimeout( function(){ ocultar(obj,w_t);},30 );

}

function mostrar(obj){
w_t=w;
//maximiza();
if(p2>-5){
clearTimeout(t2);
return;
}
p2=p2+5;
obj.style.left=p2+'px';
t2=setTimeout( function(){ mostrar(obj,w_t) },30 ); }

*/

function ocultar(obj,w){
    w_t=w;
    if(p<(w*-1)){
        clearTimeout(t);
        
        return;
    }
    p-=5;
    obj.style.left=p+'px';
    t=setTimeout( function(){ ocultar(obj,w_t) },30 );
}



function mostrar(obj,w){
    w_t=w;
    if(p2>-10){
        clearTimeout(t2);
        return;
    }
    p2=p2+5;
    obj.style.left=p2+'px';
    t2=setTimeout( function(){ mostrar(obj,w_t) },30 );
}
function minimiza(){

 if(p=='-244.99'){
    document.getElementById('MenuVertical').height='567px';
    document.getElementById('MenuVertical').width='5px';
    document.getElementById('Contenido').width=(1000)+'px';
    document.getElementById('DivMenuVertical').style.width='1px';
    document.getElementById('min_menu').style.display='none';
    document.getElementById('max_menu').style.display='';
    calcHeight();
}
}
function maximiza(){
    document.getElementById('MenuVertical').height='567px';
    document.getElementById('MenuVertical').width='245px';
    document.getElementById('Contenido').width=(980-140)+'px';
    document.getElementById('DivMenuVertical').style.width='245px';
    document.getElementById('max_menu').style.display='none';
    document.getElementById('min_menu').style.display='';
    calcHeight();
}
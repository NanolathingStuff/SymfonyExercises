import '../css/MovingBall.css';


function helloWorld(name) {
    console.log("hello " + name);
}
function alertWorld(name) {
    alert("hello " + name);
}
function myFunction(parent){
    var p = document.createElement('p');
    p.innerHTML = 'some text here';

    document.getElementById(parent).appendChild(p);
}
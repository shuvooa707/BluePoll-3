
function $$(inden) {
    return [...document.querySelectorAll(inden)];
}


function $(inden) {
    return document.querySelector(inden);
}


function toggleNot() {
    
    var not = document.querySelector('#not');
    if (window.event.target != not) {
        not.classList.toggle('show');
    }
}
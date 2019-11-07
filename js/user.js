function main(params) {
    
}


$$(".tab-switcher").forEach( e => {
    e.addEventListener('click',switchTab.bind(e));
});

function switchTab() {
    $(".active").classList.remove("active");
    $(".show").classList.remove("show");
    this.classList.toggle("active");
    $(this.getAttribute("data-target")).classList.toggle("show");
}
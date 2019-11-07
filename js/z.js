function z() {
    var options = $$(".option");
    options.forEach(e => {
        var votePercentage = parseInt(e.getAttribute("data-option-vote"));
        for (var i = 0; i < 1; i++) {
            e.querySelector(".option-name").style.backgroundSize = 0 + "%";
            e.querySelector(".vote-percentage").innerText = 0 + "%";
        }
    });
}

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



function removeSavedPoll(node) {
    let poll = node.parentElement;
    poll.style.opacity = 0;
    poll_id = poll.getAttribute("data-poll-id");

    var req = new XMLHttpRequest();
    req.open("POST", "backend.php", true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.onreadystatechange = () => {
        if (req.status == 200 && req.readyState == 4) {
            if (req.responseText.trim() == "Poll Deleted") {
                poll.remove();
            } else {

            }
        }
    }
    req.send(`operation=removeSavedPoll&pollid=${poll_id}`);
}

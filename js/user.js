function main(params) {
    
}


$$(".tab-switcher").forEach( e => {
    e.addEventListener('click',switchTab.bind(e));
});

function switchTab() {
    $$(".active").forEach( e=>{ 
        e.classList.remove("active");
    });
    $$(".show").forEach((e)=>{
        e.classList.remove("show");
    });
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
            if ( req.responseText.trim() == "Poll Deleted" ) {
                poll.remove();
            } else {

            }
        }
    }
    req.send(`operation=removeSavedPoll&pollid=${poll_id}`);
}



function unHidePoll(node) {
    let poll = node.parentElement;
    // adding loader overlay
    poll.classList.add("poll-overlay");

    let poll_id = poll.getAttribute("data-poll-id");
    var req = new XMLHttpRequest();
    req.open("POST", "backend.php", true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.onreadystatechange = () => {
        if (req.status == 200 && req.readyState == 4) {
            if (req.responseText.trim() == "Poll unhidded") {
                console.log(poll);
                
                poll.remove();
            } else {
                alert(req.responseText);
            }
        }
    }
    req.send(`operation=unhidePoll&pollid=${poll_id}`);
}


function removeAllSavedPoll() {
    let all_saved_polls = $$(".saved_poll");
    all_saved_polls.forEach(e => {
        e.style.opacity = 0;
    });
    var req = new XMLHttpRequest();
    req.open("POST", "backend.php", true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.onreadystatechange = () => {
        if (req.status == 200 && req.readyState == 4) {
            if (req.responseText.trim() == "All Saved Poll Removed") {
                all_saved_polls.forEach(e => {
                    e.remove();
                });
            } else {
                alert(req.responseText);
            }
        }
    }
    req.send(`operation=removeAllSavedPoll`);
}

function unHideAll() {
    let all_hidden_polls =  $$(".hidden_poll");
    all_hidden_polls.forEach(e => {
        e.style.opacity = 0;
    });
    var req = new XMLHttpRequest();
    req.open("POST", "backend.php", true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.onreadystatechange = () => {
        if (req.status == 200 && req.readyState == 4) {
            if (req.responseText.trim() == "All hidden Poll Removed") {
                all_hidden_polls.forEach(e => {
                    e.remove();
                });
            } else {
                alert(req.responseText);
            }
        }
    }
    req.send(`operation=unHideAll`);
}
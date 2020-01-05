
function deletePollConf( node ) {
    $(".delete-con-modal-back").classList.add("show");
    node.classList.add("pollToBeDeleted");
}

function editConf(pollid) {
    $(".edit-warning-modal-back").classList.add("show");

}



function okEditPoll() {    
    var pollid = $(".poll").getAttribute("data-poll-id");
    console.log( pollid );
    if (pollid) {
        window.location = "editpoll.php?pollid=" + pollid;
    }
}

$(".close-delete-con-container").addEventListener("click", function (params) {
    if( p = $(".pollToBeDeleted") ){
        p.classList.remove("pollToBeDeleted");
    }
});

$(".cancel").addEventListener("click", function (params) {
    $(".pollToBeDeleted").classList.remove("pollToBeDeleted");
});

function okDeletePoll(node) {
    $(".delete-con-modal-back").classList.remove("show");
    // window.location = "dashboard.php";
    var poll = $(".pollToBeDeleted");
    
    poll.classList.remove("pollToBeDeleted");
    poll.parentElement.parentElement.parentElement.parentElement.classList.add("fade-overlay");
    

    pollid = poll.getAttribute("data-poll-id");
    
    var req = new XMLHttpRequest();
    req.open("GET", "backend.php?operation=deletePoll&pollid=" + pollid, true);
    
    req.onreadystatechange = function () {    
        if( this.readyState == 4 && this.status == 200 ) {  
            // console.log(this.responseText);         
            var is_poll_deleted = (JSON.parse(this.responseText)).is_poll_deleted;
            if ( is_poll_deleted == 1 ) {
                alert("Poll Deleted");
                poll.parentElement.parentElement.parentElement.parentElement.classList.remove("fade-overlay");
                poll.remove();
            } else {
                // window.location.reload();
                console.log( this.responseText );
                alert("Something Went Worng!");
            }
        }
    }

    req.send();
}


function changeVisibility(node,button) {
    node.classList.add("fade-overlay");   
    var pollid = node.getAttribute("data-poll-id");
    var req = new XMLHttpRequest();
    req.open("POST", "backend.php", true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send("operation=changeVisibility&poll_id="+pollid);

    req.onreadystatechange = function () {
        if(this.readyState == 4 && this.status == 200) {
            var resObj = JSON.parse(req.responseText);
            if( resObj.status == "visibilityChanged" ) {
                node.classList.remove("fade-overlay");   
                if ( parseInt(resObj.poll_is) == 0 ) {
                    button.style.background = "lightcoral";
                    button.innerHTML = `Private<i class='flaticon-padlock'></i>`;
                } else {
                    button.style.background = "green";
                    button.innerHTML = "Public";
                }
            } else {
                console.log(this.responseText);
                
                // window.location.reload();
                // alert("Something Went Worng!");
            }
        }
    }
}


function analyze(params) {
    
}

function mmVottedList(node) {
    if (node.style.transform == "rotate(0deg)"  ) {
        node.style.transform = "rotate(180deg)";
        node.parentElement.parentElement.style.maxHeight = "72px";
    } else {
        node.parentElement.parentElement.style.maxHeight = "500px";
        node.style.transform = "rotate(0deg)";
    }
    
}



function allowOption(node, poptionid) {
    console.log(node);
    node.classList.add("face-overlay");
    // var poptionid = node.parentElement.getAttribute("data-poption-id");
    var req = new XMLHttpRequest();
    req.open("POST", "backend.php", true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send("operation=changeAllowOption&poptionid_id=" + poptionid);

    req.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == "option added") {
                node.remove();
            }
        }
    }
}

function deleteOption(node, poptionid) {    
    console.log(node);
    node.classList.add("face-overlay");
    // var poptionid = node.getAttribute("data-poption-id");
    var req = new XMLHttpRequest();
    req.open("POST", "backend.php", true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send("operation=deleteRequestedOption&poptionid_id=" + poptionid);

    req.onreadystatechange = function () {
        if(this.readyState == 4 && this.status == 200) {
            if (this.responseText.trim() == "option deleted" ) {
                console.log(this.responseText);   
                node.remove();
            }
        }
    }
}



const allTabs = $$(".tab");
function changeTab( tab ) {
    $(".active").classList.remove("active");
    tab.classList.add("active");
    var tabName = tab.getAttribute("data-tab-name");
    $(".show").classList.remove("show");
    allTabs.find( t => {
        if ( t.classList.contains(tabName)  ) {
            return true;
        }
    }).classList.add("show");
}



function deleteComment(node) {
    node.classList.add("commentToBeDeleted");
    $(".delete-comment-modal-back").classList.add("show");
}


function okDeleteComment(node) {
    var ctbd = $$(".commentToBeDeleted");
    console.log(ctbd);
    
    if (ctbd.length == 1) {
        $(".delete-comment-modal-back").classList.remove("show");
        ctbd = ctbd[0];
        ctbd.classList.remove("commentToBeDeleted");
        commentID = ctbd.getAttribute("data-comment-id");
        ctbd.classList.add("comment-delete-fade-overlay");

        var req = new XMLHttpRequest();

        req.open("GET", "backend.php?operation=deleteComment&commentid=" + commentID, true);

        req.onreadystatechange = function () {
            if (this.status == 200 && this.readyState == 4) {
                if (this.responseText == "commentDeleted") {
                    ctbd.remove();
                } else {
                    console.log(this.responseText);
                }
            }
        }
        req.send();
    } else {
        ctbd.forEach(e => { e.classList.remove("commentToBeDeleted") });
        alert("Something Went Worng! Please try Again");
    }
}





function removeSavedPoll(node) {
    node.style.opacity = 0;
    poll_id = node.getAttribute("data-poll-id");

    var req = new XMLHttpRequest();
    req.open("POST", "backend.php", true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.onreadystatechange = () => {
        if (req.status == 200 && req.readyState == 4) {
            if (req.responseText.trim() == "Poll Deleted") {
                node.remove();
            } else {

            }
        }
    }
    req.send(`operation=removeSavedPoll&pollid=${poll_id}`);
}





function unHidePoll( poll ) {
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

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
    poll.classList.add("fade-overlay");
    

    pollid = poll.getAttribute("data-poll-id");
    
    var req = new XMLHttpRequest();
    req.open("GET", "backend.php?operation=deletePoll&pollid=" + pollid, true);
    
    req.onreadystatechange = function () {    
        if( this.readyState == 4 && this.status == 200 ) {  
            // console.log(this.responseText);         
            var is_poll_deleted = (JSON.parse(this.responseText)).is_poll_deleted;
            if ( is_poll_deleted == 1 ) {
                alert("Poll Deleted");
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
                    button.innerText = "Private";
                } else {
                    button.style.background = "green";
                    button.innerText = "Public";
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
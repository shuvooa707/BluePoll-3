window.onload = function () {
    setTimeout( showVotePercentages, 1000);
    showVotePercentages();
    $$(".option-checkbox").forEach(e => {
        e.addEventListener("click", vote);
    });
    checkVoted();
}

function showVotePercentages() {
    var options = $$(".option");
    options.forEach(e => {
        var votePercentage = parseInt(e.getAttribute("data-option-vote"));
        var totalVotes = options.map(e => parseInt(e.getAttribute("data-option-vote"))).reduce((t, v) => t + v);
        e.querySelector(".option-name").style.backgroundSize = Math.round((votePercentage / totalVotes) * 100) + "%";
        e.querySelector(".vote-percentage").innerText = Math.round((votePercentage / totalVotes) * 100) + "%";
    
    });
}


function shootComment( node ) {
    var newCommentContent = node.querySelector(".comment-box").value; if (newCommentContent.length < 1 ) return;
    node.querySelector(".comment-box").value = "";
    var poll_id = document.querySelector(".poll").getAttribute("data-poll-id");
    var newComment = new FormData();
    newComment.append(
        "operation",
        "saveNewComment"
    );
    newComment.append(
        "comment_content",
        newCommentContent
    );
    newComment.append(
        "poll_id",
        poll_id
    );
    var req = new XMLHttpRequest();
    req.open("POST", "backend.php", false);
    // req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");                
    req.send(newComment);
            
    if ( req.responseText != "commentNotSaved" ) {
        var newComment = `<div class="comment" data-comment-id='${req.responseText}'>
        <div class="commentor-avatar">
            <img width="60px" height="60px" src="img/profile/${$('.user').getAttribute('data-user-id')}.jpg" alt="">
        </div>
        <div class="comment-body">
            <a href="#" style="" class="user-link">${$('.user').getAttribute('data-user-name')}</a>
            ${newCommentContent}
        </div>
    </div>`;

    var comments = document.querySelector(".comments");
    comments.innerHTML += newComment;   

    } else {
        alert("Something Went Worng!");
    }
}



function vote(option) {
    console.log(option);
    
    var optionID = option.target.parentElement.parentElement.getAttribute("data-option-id");
    var allPotion = [...option.target.parentElement.parentElement.parentElement.querySelectorAll("input")];
    var currentClicked = option.target;

    allPotion.forEach(e => {
        if (e == currentClicked && e.checked == true) {
            updateVoteOnline(e, optionID, 1);
            e.checked = 1;
        } else if (e == currentClicked && e.checked == false) {
            e.parentElement.parentElement.setAttribute("data-option-vote", e.parentElement.parentElement.getAttribute("data-option-vote") - 2);
            updateVoteOnline(e, optionID, 0);
            e.checked = 0;
        } else if (e != currentClicked && e.checked == true) {
            updateVoteOnline(e, e.parentElement.parentElement.getAttribute("data-option-id"), 0);
            e.parentElement.parentElement.setAttribute("data-option-vote", e.parentElement.parentElement.getAttribute("data-option-vote") - 2);
            e.checked = 0;
        }
    });
}

function updateVoteOnline(option, optionID, checked) {
    var req = new XMLHttpRequest();
    req.open("POST", "backend.php", true);
    var data = new FormData();
    data.append(
        "operation",
        "updateOptionVote"
    );
    data.append(
        "optionID",
        optionID
    );
    data.append(
        "checked",
        checked
    );

    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status == 200) {
            if (req.responseText != 0) {
                option.parentElement.parentElement.setAttribute("data-option-vote", parseInt(option.parentElement.parentElement.getAttribute("data-option-vote")) + 1);
                options = [...option.parentElement.parentElement.parentElement.querySelectorAll(".option")];
                var totalVotes = options.map(e => parseInt(e.getAttribute("data-option-vote"))).reduce((t, v) => t + v);

                console.log(totalVotes);
                options.forEach(e => {
                    var votePercentage = parseInt(e.getAttribute("data-option-vote"));
                    console.log("vote percentage : " + votePercentage);
                    if(totalVotes != 0) {
                        e.querySelector(".option-name").style.backgroundSize = Math.round((votePercentage / totalVotes) * 100) + "%";
                        e.querySelector(".vote-percentage").innerText = Math.round((votePercentage / totalVotes) * 100) + "%";
                    } else {
                        e.querySelector(".option-name").style.backgroundSize = 0 + "%";
                        e.querySelector(".vote-percentage").innerText = 0 + "%";
                    }
                });
            }
        }
    }
    req.send(data);
}



function checkVoted() {
    var req = new XMLHttpRequest();

    req.open("GET", "backend.php?operation=getVotedOptions", false);
    req.send();
    console.log(req.responseText);

    if (req.responseText && req.responseText != "not found" && req.responseText != "not votted yet") {
        var votedOptions = JSON.parse(req.responseText).map(e => parseInt(e));
        $$(".option").forEach(e => {
            var vid = parseInt(e.getAttribute("data-option-id"));
            if (votedOptions.includes(vid)) {
                e.querySelector("input[type='checkbox']").checked = true;
            }
            e.querySelector("input[type='checkbox']").disabled = false;

        });
    } else if (req.responseText == "not found") {
        $$(".option").forEach(e => {
            e.querySelector("input[type='checkbox']").checked = false;
            e.querySelector("input[type='checkbox']").disabled = false;
        });
    } else if (req.responseText == "not votted yet") {
        $$(".option").forEach(e => {
            this.querySelector("input[type='checkbox']").checked = false;
            e.querySelector("input[type='checkbox']").disabled = false;
        });
    } else if (req.responseText == "not logged in") {
        console.log("sdfgsdjkl");

        $$(".option").forEach(e => {
            e.addEventListener("click", function () {
                alert("Please Login to Vote");
                $(".loginButton").click();
                this.querySelector("input[type='checkbox']").checked = false;
            });
            e.querySelector("input[type='checkbox']").disabled = false;
        });
    }
}


function deletePollConf(params) {
    $(".delete-con-modal-back").style.display = "block";
}


function okDeletePoll( node ) {
    // window.location = "dashboard.php";
    var pollid = $(".poll").getAttribute("data-poll-id");
    node.parentElement.parentElement.style.display = "none";

    var req = new XMLHttpRequest();
    req.open("GET", "backend.php?operation=deletePoll&pollid="+pollid, false);
    req.send();

    console.log(req.responseText);
    
    if( req.responseText == "pollDeleted" ) {
        alert("Poll Deleted");         
        window.location = "dashboard.php";
    } else {
        window.location.reload();
        alert("Something Went Worng!");
    }
}


function editConf( pollid ) {
    $(".edit-warning-modal-back").classList.add("show");
    if (c) {
        window.location = "editpoll.php?pollid="+pollid;
    }
}

function okEditPoll() {
    
}
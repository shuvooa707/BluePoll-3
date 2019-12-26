window.onload = function () {
    setTimeout( showVotePercentages, 1000);
    showVotePercentages();
    $$(".option-checkbox").forEach(e => {
        e.addEventListener("click", vote);
    });
    checkVoted();
}
window.addEventListener("keydown", () => {
    // 	console.log(window.event);
    if (window.event.key == "Escape") {
        closeVotterList();
    }
});


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
    if (!checkIsLoggedIn()) {
        showLogin();
        return;
    }
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
            <a href="#" style="" class="user-link">${$('.user').getAttribute('data-user-fullname')}</a>
            <span class='comment-excerpt'>
                ${newCommentContent}
                <span onclick='deleteComment(this.parentElement.parentElement.parentElement)' class='delete-comment' title='Delete This Comment'><span style='' class='flaticon-garbage'></span></span>
            </span>
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

    // console.log(option.target.parentElement.parentElement.parentElement.parentElement);
    
    option.target.parentElement.parentElement.parentElement.parentElement.classList.add("poll-overlay");

    var pollID = option.target.parentElement.parentElement.parentElement.parentElement.getAttribute("data-poll-id");
    var optionID = option.target.parentElement.parentElement.getAttribute("data-option-id");
    var allPotion = [...option.target.parentElement.parentElement.parentElement.querySelectorAll("input")];
    var currentClicked = option.target;

    allPotion.forEach(e => {
        if (e == currentClicked && e.checked == true) {
            updateVoteOnline(e, pollID,optionID, 1);
            e.checked = 1;
        } else if (e == currentClicked && e.checked == false) {
            e.parentElement.parentElement.setAttribute("data-option-vote", e.parentElement.parentElement.getAttribute("data-option-vote") - 2);
            updateVoteOnline(e, pollID,optionID, 0);
            e.checked = 0;
        } else if (e != currentClicked && e.checked == true) {
            updateVoteOnline(e, pollID,e.parentElement.parentElement.getAttribute("data-option-id"), 0);
            e.parentElement.parentElement.setAttribute("data-option-vote", e.parentElement.parentElement.getAttribute("data-option-vote") - 2);
            e.checked = 0;
        }
    });
}

function updateVoteOnline(option, pollID,optionID, checked) {
    var req = new XMLHttpRequest();
    req.open("POST", "backend.php", true);
    var data = new FormData();
    data.append(
        "operation",
        "updateOptionVote"
    );

    data.append(
        "pollID",
        pollID
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
            } else {
                console.error( req.responseText );
                
            }
            if( po = $(".poll-overlay") ) {
                setTimeout(() => {
                    po.classList.remove("poll-overlay")
                }, 1000);
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
    $(".delete-con-modal-back").classList.add("show");
}


function okDeletePoll( node ) {
    // window.location = "dashboard.php";
    var pollid = $(".poll").getAttribute("data-poll-id");
    node.parentElement.parentElement.style.display = "none";

    var req = new XMLHttpRequest();
    req.open("GET", "backend.php?operation=deletePoll&pollid="+pollid, false);
    req.send();

    console.log(req.responseText);
    var is_poll_deleted = (JSON.parse(req.responseText)).is_poll_deleted;    
    if( is_poll_deleted == 1 ) {
        alert("Poll Deleted");         
        window.location = "dashboard.php";
    } else {
        window.location.reload();
        alert("Something Went Worng!");
    }
}


function editConf( pollid ) {
    $(".edit-warning-modal-back").classList.add("show");
    
}

function okEditPoll() {
    var pollid = $(".poll").getAttribute("data-poll-id");
    if (pollid) {
        window.location = "editpoll.php?pollid=" + pollid;
    }
}



function showVotersList(node) {
    // if (!checkIsLoggedIn()) {
    //     // showLogin();
    //     return;
    // }
    var aVoter = "";
    var wo = $(".whoVotted-overlay");

    // get the votters
    var optionid = node.getAttribute("data-option-id");

    var req = new XMLHttpRequest();
    req.open("GET", "backend.php?operation=getVoterList&optionid=" + optionid, true);
    req.onreadystatechange = function (params) {
        if (this.status == 200 && this.readyState == 4) {
            var data = this.responseText[0] == "[" ? JSON.parse(this.responseText) : [];
            data.forEach(e => {
                aVoter += `<div class="voter">
                                <a href="user.php?userid=${e.user_id}">
                                    <img width="40px" height="40px" src="img\\profile\\${e.user_id}.jpg" alt="">
                                </a>
                                <strong><a href="user.php?userid=${e.user_id}">${e.user_name}</a></strong>
                            </div>`;
            });
            console.log(data);
            
            if ( data.length == 0 ) {
                $(".voters").innerHTML = "<h4 style='text-align:center'>No Vote Yet</h4>";
            } else {
                $(".voters").innerHTML = aVoter;
            }
                
        }
    }
    req.send();


    // push inside $(".votters")
    wo.classList.add("show");
}


function closeVotterList(params) {
    var wo = $(".whoVotted-overlay");
    wo.querySelector(".voters").innerHTML = "";
    wo.classList.remove("show");
}

function deleteComment(node) {
    node.classList.add("commentToBeDeleted");
    $(".delete-comment-modal-back").classList.add("show");
}


function okDeleteComment(node) {
    var ctbd = $$(".commentToBeDeleted");
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


function showLogin() {
    document.querySelector(".login-modal-back").style.display = "block";
}

function closeLogin(node) {
    node.parentElement.parentElement.parentElement.style.display = "none";
    document.querySelector(".login-modal-back #username").value = "";
    document.querySelector(".login-modal-back #password").value = "";
}


function login(node) {
    var username = node.querySelector("#username").value;
    var password = node.querySelector("#password").value;
    var req = new XMLHttpRequest();
    req.open("POST", "backend.php", false);
    var data = new FormData();
    data.append(
        "operation",
        "login"
    );
    data.append(
        "username",
        username
    );
    data.append(
        "password",
        password
    );
    req.send(data);

    if (req.responseText == "login success") {
        window.location.reload();
    } else {
        alert(req.responseText);
    }
}


window.addEventListener("keydown", () => {
    	console.log(window.event);
    if (window.event.key == "Escape") {
        if (tmp = $(".edit-warning-modal-back") ) {
            tmp.classList.remove("show");
        }
        if (tmp = $(".delete-con-modal-back")) {
            tmp.classList.remove("show");
        }
        if (tmp = $(".delete-comment-modal-back")) {
            tmp.classList.remove("show");
        }
    }
});



function likeDislike(which, pollid, pollline, btn) {
    var req = new XMLHttpRequest();

    if (which == "like") {
        req.open("GET", "backend.php?operation=likePoll&pollid=" + pollid, true);
    } else {
        req.open("GET", "backend.php?operation=dislikePoll&pollid=" + pollid, true);
    }

    req.onreadystatechange = function () {
        if (this.status == 200 && this.readyState == 4) {
            var tmp = JSON.parse(this.responseText);
            console.log(tmp);
            var likePercentage = ( parseInt(tmp.likes) / (parseInt(tmp.likes) + parseInt(tmp.dislikes) ) * 180) + "px";
            var dislikePercentage = ( parseInt(tmp.dislikes) / ( parseInt(tmp.likes) + parseInt(tmp.dislikes) ) * 180) + "px";
            
            if ( tmp.status == "liked" ) {
                btn.classList.add("liked");
                if (other_btn = btn.parentElement.querySelector(".disliked")) {
                    other_btn.classList.remove("disliked");
                    $(".line").querySelector(".left").style.width = likePercentage;
                    $(".line").querySelector(".right").style.width = dislikePercentage;
                }
            }
            if ( tmp.status == "disliked") {
                btn.classList.add("disliked");
                if (other_btn = btn.parentElement.querySelector(".liked")) {
                    other_btn.classList.remove("liked");
                    $(".line").querySelector(".left").style.width = likePercentage;
                    $(".line").querySelector(".right").style.width = dislikePercentage;
                }
            }
        }
    }
    req.send();
}


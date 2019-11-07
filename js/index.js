
window.onload = function () {
    showVotePercentages();
    $$(".option-checkbox-element").forEach(e => {
        e.addEventListener("click", vote);
    });
    checkVoted();
    setTimeout(centerifyCheckBox,1000);
    centerifyCheckBox();
    setTimeout(function(){
        $$(".option-name").forEach( e => {  
            e.style.transition = "1s linear"; 
        });
    },1001);
}


function bringCheckboxAtMiddle() {
    options = [...document.querySelectorAll(".option")];
    options.forEach(option => {
        
        var toTop = (option.clientHeight - option.querySelector(".option-checkbox").clientHeight) / 2;
        console.log(toTop);

        option.querySelector(".option-checkbox").style.transform = `translateY( ${toTop}px)`;
    });
}

function shootComment(nc) {

    if (!checkIsLoggedIn()) {
        showLogin();
        return;
    }

    var newCommentContent = nc.querySelector("#comment-box").value; if (newCommentContent.length < 1) return;
    nc.querySelector("#comment-box").value = "";
    var poll_id = nc.parentElement.parentElement.parentElement.getAttribute("data-poll-id");
    console.log(poll_id);
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

    if (req.responseText != "commentNotSaved") {
        var newComment = `<div class="comment" data-comment-id='${req.responseText}'>
        <div class="commentor-avatar">
            <a href="user.php?userid=${$('.user').getAttribute('data-user-id')}">
                <img width="60px" height="60px" src="img/profile/${$('.user').getAttribute('data-user-id')}.jpg" alt="">
            </a>
        </div>
        <div class="comment-body">
            <a href="user.php?userid=${$('.user').getAttribute('data-user-id')}" style="" class="user-link">${$('.user').getAttribute('data-user-name')}</a>
            ${newCommentContent}
        </div>
    </div>`;

    var comments = nc.parentElement.parentElement.parentElement.querySelector(".comments");
    comments.innerHTML += newComment;

    } else {
        alert("Something Went Worng!");
    } 
}


function commentShowMore ( node ) {
    console.log(node.nextElementSibling);
    node.nextElementSibling.style.display = "inline";
    node.style.display = "none";
}


function closeLogin( node ) {
    node.parentElement.parentElement.parentElement.style.display = "none";
    document.querySelector(".login-modal-back #username").value = "";
    document.querySelector(".login-modal-back #password").value = "";
}

function showLogin() {
    document.querySelector(".login-modal-back").style.display = "block";
}



function showVotePercentages( iden=".poll" ) {
    var polls = $$(iden);
    polls.forEach( poll => {
        options = [...poll.querySelectorAll(".option")];
        var totalVotes = options.map(e => parseInt(e.getAttribute("data-option-vote"))).reduce((t, v) => t + v);
        console.log(totalVotes);        
        options.forEach( e => {
            var votePercentage = parseInt(e.getAttribute("data-option-vote"));
            e.querySelector(".option-name").style.backgroundSize = Math.round( (votePercentage / totalVotes) * 100) + "%";
            e.querySelector(".vote-percentage").innerText        = Math.round( (votePercentage / totalVotes) * 100) + "%";
            
        });
    });
}



function vote(option) {
    if ( !checkIsLoggedIn() ) {
        // showLogin();
        return;
    }
    option.target.parentElement.parentElement.classList.add("poll-overlay");
    var optionID = option.target.parentElement.parentElement.getAttribute("data-option-id");
    var allPotion = [...option.target.parentElement.parentElement.parentElement.querySelectorAll("input")];
    var currentClicked = option.target;
    
    allPotion.forEach( e => {
        if( e == currentClicked && e.checked == true ) {
            updateVoteOnline( e, optionID, 1 );
            e.checked = 1;
        } else if (e == currentClicked && e.checked == false) {
            e.parentElement.parentElement.setAttribute("data-option-vote", e.parentElement.parentElement.getAttribute("data-option-vote") - 2);
            updateVoteOnline(e, optionID, 0);
            e.checked = 0;
        } else if (e != currentClicked && e.checked == true) {
            updateVoteOnline(e, e.parentElement.parentElement.getAttribute("data-option-id"), 0);
            e.parentElement.parentElement.setAttribute("data-option-vote", e.parentElement.parentElement.getAttribute("data-option-vote")-2);
            e.checked = 0;
        }
    });
}

function updateVoteOnline( option,optionID, checked ) {
    var po =  $(".poll-overlay");
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
            var singleOption = option.parentElement.parentElement;
            if ( req.responseText != 0 ) {           
                singleOption.setAttribute("data-option-vote", parseInt(singleOption.getAttribute("data-option-vote")) + 1);            
                options = [...singleOption.parentElement.querySelectorAll(".option")];
                var totalVotes = options.map(e => parseInt(e.getAttribute("data-option-vote"))).reduce((t, v) => t + v);
                
                console.log(totalVotes);
                options.forEach(e => {
                    var votePercentage = parseInt(e.getAttribute("data-option-vote"));
                    console.log("vote percentage : "+votePercentage);
                    if( totalVotes != 0) {
                        e.querySelector(".option-name").style.backgroundSize = Math.round((votePercentage / totalVotes) * 100) + "%";
                        e.querySelector(".vote-percentage").innerText = Math.round((votePercentage / totalVotes) * 100) + "%";
                    } else {
                        e.querySelector(".option-name").style.backgroundSize = 0 + "%";
                        e.querySelector(".vote-percentage").innerText = 0 + "%";
                    }
                });
            }
            if (po) {
                setTimeout( ()=>{
                    po.classList.remove("poll-overlay")},1000);
            }
        }
    }
    req.send(data);
}



function checkVoted() {
    var req =  new XMLHttpRequest();

    req.open("GET","backend.php?operation=getVotedOptions",false);
    req.send();
    console.log(req.responseText);
    
    if (req.responseText && req.responseText != "not found" && req.responseText != "not votted yet" && req.responseText != "not logged in") {
        var votedOptions = JSON.parse(req.responseText).map( e=> parseInt(e) );
        $$(".option").forEach( e=>{
            var vid = parseInt(e.getAttribute("data-option-id"));
            if( votedOptions.includes(vid) ) {
                e.querySelector("input[type='checkbox']").checked = true;
            } 
            e.querySelector("input[type='checkbox']").disabled = false;
            
        });
    } else if (req.responseText == "not found") {
        $$(".option").forEach(e => {
            e.querySelector("input[type='checkbox']").checked = false;
            e.querySelector("input[type='checkbox']").disabled = false;
        });
    } else if ( req.responseText == "not votted yet" ) {
        $$(".option").forEach(e => {
            this.querySelector("input[type='checkbox']").checked = false;
            e.querySelector("input[type='checkbox']").disabled = false;
        });
    } else if (req.responseText == "not logged in" ) {
        console.log("sdfgsdjkl");
        
        $$(".option").forEach(e => {            
            e.querySelector("input[type='checkbox']").addEventListener("click",function() {
                alert("Please Login to Vote");
                $(".loginButton").click();
                this.checked = false;
            });
            e.querySelector("input[type='checkbox']").disabled = false;
        });
    }
}


function login( node ) {
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
    
    if (req.responseText == "login success" ) {
        window.location.reload();
    } else {
        alert(req.responseText);
    }
}


function alignPolls() {
    var poll = $$(".poll");
    console.log(poll[0].parentElement.clientHeight);
    
} alignPolls();


function centerifyCheckBox() {
    [...document.querySelectorAll(".option-checkbox")].forEach(e => {
        // 	console.log(e);
        var h = e.parentElement.clientHeight;
        e.style.height = h + "px";
    });
}


function showVotersList(node) {
    if (!checkIsLoggedIn()) {
        // showLogin();
        return;
    }
    var aVoter = "";
    // var aVoter = `<div class="voter">
    //                     <a href="user.php?userid=4">
    //                         <img width="40px" height="40px" src="img\\profile\\3.jpg" alt="">
    //                     </a>
    //                     <strong><a href="user.php?userid=4">Shuvo Sarker</a></strong>
    //                 </div>`;
    var wo = $(".whoVotted-overlay");
    wo.addEventListener("click",function(){
        if( window.event.target != $(".whoVotted-container") ) {
            // wo.classList.remove("show");
        }
    });
    // get the votters
    var optionid = node.getAttribute("data-option-id");

    var req = new XMLHttpRequest();
    req.open("GET","backend.php?operation=getVoterList&optionid="+optionid,true);

    req.onreadystatechange = function (params) {
        if( this.status == 200 && this.readyState == 4 ) {
            if( data != "no votes" ) {
                var data = JSON.parse(this.responseText);
                console.log(data);
                data.forEach( e => {
                    aVoter += `<div class="voter">
                                    <a href="user.php?userid=${e.user_id}">
                                        <img width="40px" height="40px" src="img\\profile\\${e.user_id}.jpg" alt="">
                                    </a>
                                    <strong><a href="user.php?userid=${e.user_id}">${e.user_name}</a></strong>
                                </div>`;
                });              
                $(".voters").innerHTML =  aVoter;  
            }
        }
    }
    req.send();
    
    
    // push inside $(".votters")
    wo.classList.add("show");
}

function likeDislike( which,pollid,pollline ) {
    var req = new XMLHttpRequest();
    
    if( which == "like" ) {
        req.open("GET", "backend.php?operation=likePoll&pollid=" + pollid, true);
    } else {
        req.open("GET", "backend.php?operation=dislikePoll&pollid=" + pollid, true);        
    }
    
    req.onreadystatechange = function () {
        if( this.status == 200 && this.readyState == 4 ) {
            if( this.responseText == "liked" ) {
                console.log(pollline);
                var cs = window.getComputedStyle(pollline);
                leftWidth = cs.getPropertyValue("border-left-width");
                rightWidth = cs.getPropertyValue("border-right-width");
                pollline.style.BorderLeftWidth = (parseInt(leftWidth) + 1) + "px";
                pollline.style.BorderRightWidth = (parseInt(rightWidth) - 1) + "px";
                console.log(parseInt(leftWidth) + 1);
            }
        }
    }
    req.send();       
}



function closeVotterList(params) {
    var wo = $(".whoVotted-overlay");
    wo.querySelector(".voters").innerHTML = "";
    wo.classList.remove("show");
}


function deleteComment( node ) {
    node.classList.add("commentToBeDeleted");
    $(".delete-comment-modal-back").classList.add("show");
}


function okDeleteComment( node ) {
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
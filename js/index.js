
function delay(seconds=5) {
    i = (1000000 * 335) * seconds;
    while (i--) {

    }
}

window.onload = function () {
    showVotePercentages();
    // $$(".option-checkbox-element").forEach(e => {
    //     e.addEventListener("click", vote);
    // });
    checkVoted();
    setTimeout(centerifyCheckBox,1000);
    centerifyCheckBox();
    setTimeout(function(){
        $$(".option-name").forEach( e => {  
            e.style.transition = "1s linear"; 
        });
    },1001);
    alignPolls();


    window.navbar = document.querySelector(".navbar");
    window.addEventListener("scroll", function (event, navbar) {
        if (window.scrollY <= 0) {
            window.navbar.style.borderColor = "rgba(240, 128, 128, 0.52)";
            window.navbar.style.boxShadow = "none";
        } else {
            window.navbar.style.borderColor = "#1e90ff85";
            window.navbar.style.boxShadow = "rgb(166, 166, 166) 0px 5px 10px";
        }
    });
}

window.addEventListener("keydown", () => {
    // 	console.log(window.event);
    if (window.event.key == "Escape") {
        closeVotterList();
    }
});


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
                                <a href="user.php?userid=${$('.user').getAttribute('data-user-id')}" style="" class="user-link">${$('.user').getAttribute('data-user-fullname')}</a>
                                <span class='comment-excerpt'>
                                    ${newCommentContent}
                                    <span onclick='deleteComment(this.parentElement.parentElement.parentElement)' class='delete-comment' title='Delete This Comment'>
                                        <span style='' class='flaticon-garbage'></span>
                                    </span>
                                </span>
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
    option = window.event;
    if ( !checkIsLoggedIn() ) {
        // showLogin();
        return;
    }
    console.log(option);
    
    option.target.parentElement.parentElement.classList.add("poll-overlay");
    var pollID = option.target.parentElement.parentElement.parentElement.parentElement.getAttribute("data-poll-id");
    console.log(pollID);
    
    var optionID = option.target.parentElement.parentElement.getAttribute("data-option-id");
    var allPotion = [...option.target.parentElement.parentElement.parentElement.querySelectorAll("input")];
    var currentClicked = option.target;
    
    allPotion.forEach( e => {
        // checking the voted option
        if( e == currentClicked && e.checked == true ) {
            updateVoteOnline( e, pollID,optionID, 1 );
            e.checked = 1;
        // unchecking the voted option
        } else if (e == currentClicked && e.checked == false) {
            e.parentElement.parentElement.setAttribute("data-option-vote", e.parentElement.parentElement.getAttribute("data-option-vote") - 2);
            updateVoteOnline(e,  pollID,optionID, 0);
            e.checked = 0;
        } else if (e != currentClicked && e.checked == true) {
            updateVoteOnline(e,  pollID,e.parentElement.parentElement.getAttribute("data-option-id"), 0);
            e.parentElement.parentElement.setAttribute("data-option-vote", e.parentElement.parentElement.getAttribute("data-option-vote")-2);
            e.checked = 0;
        }
    });
}

function updateVoteOnline(option, pollID,optionID, checked ) {
    // var po =  $(".poll-overlay");
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
            console.log(req);
            
            var singleOption = option.parentElement.parentElement;
            if ( req.responseText != 0 ) {
                singleOption.setAttribute("data-option-vote", parseInt(singleOption.getAttribute("data-option-vote")) + 1);            
                options = [...singleOption.parentElement.querySelectorAll(".option")];
                var requested_options = [...singleOption.parentElement.querySelectorAll(".requested-option")];

                var totalVotes = options.map(e => parseInt(e.getAttribute("data-option-vote"))).reduce((t, v) => t + v);
                
                console.log(totalVotes);
                options.forEach(e => {
                    var votePercentage = parseInt(e.getAttribute("data-option-vote"));
                    console.log("vote percentage : " + votePercentage);
                    if( totalVotes != 0) {
                        e.querySelector(".option-name").style.backgroundSize = Math.round((votePercentage / totalVotes) * 100) + "%";
                        e.querySelector(".vote-percentage").innerText = Math.round((votePercentage / totalVotes) * 100) + "%";
                    } else {
                        e.querySelector(".option-name").style.backgroundSize = 0 + "%";
                        e.querySelector(".vote-percentage").innerText = 0 + "%";
                    }
                });
                
                var poll_body = options[0].parentElement;
                // sort the options after every vote
                setTimeout(() => {
                    var poll_addnew_option_box = poll_body.querySelector(".poll-addnew-option-box");
                    var poll_info_box = poll_body.querySelector(".poll-info-box");
                    var poll_tag_date = poll_body.querySelector(".poll-tag-date");

                    poll_body.innerHTML = "";
                    options.sort(function (a, b) {
                        //     console.log(a.getAttribute("data-option-vote"));
                        return parseInt(b.getAttribute("data-option-vote")) - parseInt(a.getAttribute("data-option-vote"));
                    }).forEach(e => {
                        poll_body.append(e);
                    });
                    requested_options.forEach( e=>{
                        poll_body.append(e);
                    });

                    poll_body.append(poll_addnew_option_box);
                    poll_body.append(poll_info_box);
                    poll_body.append( poll_tag_date );
                }, 1300, poll_body, requested_options);



            }
            if ( po = $(".poll-overlay") ) {
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
    
    node.classList.add("poll-overlay");
    
    var username = node.querySelector("#username").value;
    var password = node.querySelector("#password").value;
    var req = new XMLHttpRequest();
    req.open("POST", "backend.php", true);
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
    
    req.onreadystatechange = function() {
        if ( this.readyState == 4 && this.status == 200 ) {
            if (req.responseText == "login success") {
                node.classList.remove("poll-overlay");
                node.innerHTML = "<h2>Login Successfull </h2>"
                window.location.reload();
            } else {
                node.classList.remove("poll-overlay");
                alert("Login Failed Please Try Again");
            }
        } else {
            
        }
    }

    
}


function alignPolls() {
    if( poll = $$(".poll") && $$(".poll").length ) {
        var poll = $$(".poll");
        console.log(poll[0].parentElement.clientHeight);
    } else {
        return;
    }
    
}


function centerifyCheckBox() {
    // delay();
    [...document.querySelectorAll(".option-checkbox")].forEach(e => {
        // 	console.log(e);
        var h = e.parentElement.clientHeight;
        e.style.height = h + "px";
    });
    if (loadOverlay = $("#onload-overlay")) {
        loadOverlay.classList.add("loadOverlayFadeAni");
        setTimeout((loadOverlay) => {
            loadOverlay.classList.remove("loadOverlayFadeAni");
            loadOverlay.style.display = "none";
        }, 1000, loadOverlay);        
    }
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
    // wo.addEventListener("click",function(){
    //     if( window.event.target != $(".whoVotted-container") ) {
    //         // wo.classList.remove("show");
    //     }
    // });
    // get the votters
    var optionid = node.getAttribute("data-option-id");

    var req = new XMLHttpRequest();
    req.open("GET","backend.php?operation=getVoterList&optionid="+optionid,true);

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
            $(".whoVotted-footer").innerText = data.length + " Vote" + (data.length == 1 ? "" : "s");
            console.log(data);

            if (data.length == 0) {
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

function likeDislike( which,pollid,pollline,btn ) {
    var req = new XMLHttpRequest();
    
    if( which == "like" ) {
        req.open("GET", "backend.php?operation=likePoll&pollid=" + pollid, true);
    } else {
        req.open("GET", "backend.php?operation=dislikePoll&pollid=" + pollid, true);        
    }
    
    req.onreadystatechange = function () {
        if( this.status == 200 && this.readyState == 4 ) {
            var tmp = JSON.parse(this.responseText);
            
            
            // computing likes and dislikes percentages
            var likePercentage = (parseInt(tmp.likes) / (parseInt(tmp.likes) + parseInt(tmp.dislikes)) * 150);
            likePercentage = isNaN(likePercentage) ? "50%" : likePercentage + "px";
            var dislikePercentage = (parseInt(tmp.dislikes) / (parseInt(tmp.likes) + parseInt(tmp.dislikes)) * 150);
            dislikePercentage = isNaN(dislikePercentage) ? "50%" : dislikePercentage + "px";

            console.log(likePercentage);
            console.log(dislikePercentage);

            if (tmp.status == "unliked") {
                other_btn = btn.parentElement.querySelector(".liked");
                other_btn.classList.remove("liked");                
            }
            if (tmp.status == "undisliked") {
                other_btn = btn.parentElement.querySelector(".disliked");
                // console.log(other_btn);
                
                other_btn.classList.remove("disliked");
            }
            

            if (tmp.status == "liked") {
                btn.classList.add("liked");
                if (other_btn = btn.parentElement.querySelector(".disliked")) {
                    other_btn.classList.remove("disliked");
                }
            }
            if (tmp.status == "disliked") {
                btn.classList.add("disliked");
                if (other_btn = btn.parentElement.querySelector(".liked")) {
                    other_btn.classList.remove("liked");
                }
            }

            
            pollline.querySelector(".left").style.width = likePercentage;
            pollline.querySelector(".right").style.width = dislikePercentage;
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


function showPollControlOptions(poll,node) {
    
    
    var cont = poll.querySelector(".poll-tool-container");
    if ( poll.querySelector(".poll-tool-container").classList.contains("show") ) {

        cont.style.overflow = "hidden";
        cont.style.height = "0px";
        poll.querySelector(".close-poll-tool-container").style.display = "none";
        setTimeout(function(){
            cont.style.height = "100px";
            cont.classList.remove("show");
        },290);
    } else {
        setTimeout(() => {
            poll.querySelector(".poll-tool-container").style.overflow = "unset";
            poll.querySelector(".close-poll-tool-container").style.display = "block";
        }, 300);
        poll.querySelector(".close-poll-tool-container").style.display = "none";
        cont.style.overflow = "hidden";
        poll.querySelector(".poll-tool-container").classList.toggle("show");
    }
}

// $$(".add-new-option-button").forEach(e => {
//     // e.onclick = ToggleAddNewOptionInput;
// });

// $$(".cancel-new-option-button").forEach(e => {
//     e.onclick = ToggleAddNewOptionInput;
// });

function ToggleAddNewOptionInput( node ) {
    var addNewOptionButton = node.parentElement.querySelector(".add-new-option-button");
    if (addNewOptionButton.innerText.trim() == "✚" ) {
        node.innerText = "Add";
        node.parentElement.classList.toggle("showAddNewOption");
        node.onclick = addNewOptionOnline.bind(node);
    }
    else if (addNewOptionButton.innerText.trim() == "Add" ) {
        node.parentElement.classList.toggle("showAddNewOption");
        addNewOptionButton.innerText = "✚"
        addNewOptionButton.onclick = ToggleAddNewOptionInput;
        addNewOptionButton.previousElementSibling.value = "";
    }
}

function addNewOptionOnline( node ) {
    
    if ( node.previousElementSibling.value.length > 1 ) { 
        var poll = node.parentElement.parentElement.parentElement;
        var pollid = poll.getAttribute("data-poll-id"); 
        // console.log(pollid);
        var optionname = node.previousElementSibling.value;       
        node.parentElement.classList.add("comment-delete-fade-overlay");
        var req = new XMLHttpRequest();
        req.open("POST", "backend.php", true );
        req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        req.onreadystatechange = () => {
            if ( req.status == 200 && req.readyState == 4 ) {
                var response = JSON.parse(req.responseText);                
                if (response.msg == 'option Added') {
                    var newOption = `
                        <div class='option' data-option-id='${response.optionid}' data-option-vote='-1' >
                            <div class='option-checkbox'>
                                <input onClick='vote(this.parentElement.parentElement)' type='checkbox' name='option-checkbox' class='option-checkbox-element'>
                            </div>
                            <div class='option-name' style='background-size:0%;'>
                                <div class='name' style='display:inline-block; width:65%;'>${optionname}</div>
                                <div class='vote' style='display:inline-block;width:30%;position: absolute;top: 13px;'>
                                    
                                    <strong title='Click to See Who Votted' style='cursor:pointer;color:#1e90ffc2;position:absolute;right:5px;' class='vote-percentage' onclick='showVotersList(this.parentElement.parentElement.parentElement)'>
                                    </strong>
                                </div>
                            </div>
                        </div>
                    `;
                    var newOption = ((new DOMParser()).parseFromString(newOption, "text/html")).querySelector(".option");
                    // console.log(newOption);
                    poll.querySelector(".poll-body").prepend(newOption);
                    centerifyCheckBox();
                    sortPollOptions(poll);
                    node.parentElement.classList.remove("comment-delete-fade-overlay");
                    node.previousElementSibling.value = "";
                } else if (response.msg == 'option requested') {
                    node.parentElement.classList.remove("comment-delete-fade-overlay");
                    node.previousElementSibling.value = "";
                    alert("option requested");
                }
            }
        }
        req.send(`operation=requestNewOption&pollid=${pollid}&optionname=${optionname}`);
    } else {
        var inputField = node.previousElementSibling;
        inputField.style.border = "1px solid red";
        setTimeout( inputField => {
            inputField.style.border = "0px";
        }, 2000, inputField);
    }
}

function savePoll(node) {
    let poll = node.parentElement.parentElement;
    // adding loader overlay
    poll.classList.add("poll-overlay");

    let poll_id = poll.getAttribute("data-poll-id");
    var req = new XMLHttpRequest();
    req.open("POST", "backend.php", true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.onreadystatechange = () => {
        if (req.status == 200 && req.readyState == 4) {
            if (req.responseText.trim() == "Poll Saved") {
                poll.classList.remove("poll-overlay");
                node.classList.add("saved-poll");
                node.innerText = "Saved";
                poll.style.boxShadow = "#F44336 0px 0px 2px";
                node.removeAttribute("onclick");
            } else {

            }
        }
    }
    req.send(`operation=savePoll&pollid=${poll_id}`);
}

function HidePoll(node) {
    let poll = node.parentElement.parentElement;
    // adding loader overlay
    poll.classList.add("poll-overlay");

    let poll_id = poll.getAttribute("data-poll-id");
    var req = new XMLHttpRequest();
    req.open("POST", "backend.php", true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.onreadystatechange = () => {
        if (req.status == 200 && req.readyState == 4) {
            if (req.responseText.trim() == "Poll Concealed") {
                $("#snackbar").classList.add("show");
                setTimeout(() => {
                    if( sn = $("#snackbar") )
                        sn.classList.remove("show");
                }, 5000);
                // $("#snackbar").classList.add("show");
                poll.style.transform = "rotateY(102deg)";
                poll.classList.add("hideAni");
                pW = poll.clientWidth;
                setTimeout(() => {
                    var susp = ((new DOMParser()).parseFromString(`<div id="suspension" style='width:${pW}px'></div>`, "text/html")).querySelector("#suspension")                    
                    poll.replaceWith(
                        susp
                    );
                    setTimeout(() => {
                        $("#suspension").style.width = "0px";
                        setTimeout(() => {
                            $("#suspension").remove();
                        }, 1000);
                    }, 100);


                }, 998);
                setTimeout((poll) => {
                    poll.remove();                
                }, 1500,poll);
            } else {
                alert(req.responseText);
            }
        }
    }
    req.send(`operation=hidePoll&pollid=${poll_id}`);
}


function loadMorePolls() {
    var onload_overlay = $("#onload-overlay");
    onload_overlay.style.display = "block";
    var poll_from = $$(".poll").length;
    var req = new XMLHttpRequest();
    req.open("GET", `polls.php?poll_count=yes&poll_from=${poll_from}`, true);
    // req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.onreadystatechange = () => {
        if (req.status == 200 && req.readyState == 4) {
            if ( req.responseText.split("-----")[0] == "no" ) {
                $(".load-more-button").remove();
            } 
            var main_content = $(".main-content");
            [...(new DOMParser()).parseFromString(req.responseText,"text/html").querySelectorAll(".poll")].forEach(element => {
                main_content.append(element);
            });;
            // console.log( newly_loaded_polls);
            onload_overlay.style.display = "none";
            centerifyCheckBox();
            checkVoted();
            showVotePercentages();
        }
    }
    req.send();
}




function allowOption(node) {
    console.log(node);
    node.classList.add("face-overlay");
    var poptionid = node.getAttribute("data-poption-id");
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


function deleteOption(node) {
    console.log(node);
    node.classList.add("face-overlay");
    var poptionid = node.getAttribute("data-poption-id");
    var req = new XMLHttpRequest();
    req.open("POST", "backend.php", true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send("operation=deleteRequestedOption&poptionid_id=" + poptionid);

    req.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText.trim() == "option deleted") {
                console.log(this.responseText);
                node.remove();
            }
        }
    }
}


function sortPollOptions( poll ) {
    
    var poll_body = poll.querySelector(".poll-body");;
    var poll_addnew_option_box = poll_body.querySelector(".poll-addnew-option-box");
    var poll_info_box = poll_body.querySelector(".poll-info-box");
    var poll_tag_date = poll_body.querySelector(".poll-tag-date");
    var options = [...poll_body.querySelectorAll(".option")];
    var requested_options = [...poll_body.querySelectorAll(".requested-option")];
    console.log(options);
    poll_body.innerHTML = "";
    options.sort(function (a, b) {
        //     console.log(a.getAttribute("data-option-vote"));
        return parseInt(b.getAttribute("data-option-vote")) - parseInt(a.getAttribute("data-option-vote"));
    }).forEach(e => {
        poll_body.append(e);
    });
    requested_options.forEach( e=>{
        poll_body.append(e);
    });
    poll_body.append(poll_addnew_option_box);
    poll_body.append(poll_info_box);
    poll_body.append(poll_tag_date);
}


function acceptOption( node ) {
    var poll = node.parentElement.parentElement.parentElement.parentElement;
    var requested_option = node.parentElement.parentElement;
    requested_option_id = requested_option.getAttribute("data-option-id");
    var optionname = requested_option.querySelector(".requested-option-name").innerText;
    node.classList.add("face-overlay");
    // var poptionid = node.parentElement.getAttribute("data-poption-id");
    var req = new XMLHttpRequest();
    req.open("POST", "backend.php", true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    
    req.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText == "option added") {
                var newOption = `
                        <div class='option' data-option-id='' data-option-vote='-1' >
                            <div class='option-checkbox'>
                                <input onClick='vote(this.parentElement.parentElement)' type='checkbox' name='option-checkbox' class='option-checkbox-element'>
                            </div>
                            <div class='option-name' style='background-size:0%;'>
                                <div class='name' style='display:inline-block; width:65%;'>${optionname}</div>
                                <div class='vote' style='display:inline-block;width:30%;position: absolute;top: 13px;'>
                                    
                                    <strong title='Click to See Who Votted' style='cursor:pointer;color:#1e90ffc2;position:absolute;right:5px;' class='vote-percentage' onclick='showVotersList(this.parentElement.parentElement.parentElement)'>
                                    </strong>
                                </div>
                            </div>
                        </div>
                    `;
                var newOption = ((new DOMParser()).parseFromString(newOption, "text/html")).querySelector(".option");

                requested_option.replaceWith( newOption );
                centerifyCheckBox();
                // console.log(poll);
                sortPollOptions( poll );

            }
        }
    }

    req.send("operation=changeAllowOption&poptionid_id=" + requested_option_id);
}



function rejectOption(node) {
    var requested_option = node.parentElement.parentElement;
    node.classList.add("face-overlay");
    var poptionid = requested_option.getAttribute("data-option-id");
    var req = new XMLHttpRequest();
    req.open("POST", "backend.php", true);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send("operation=deleteRequestedOption&poptionid_id=" + poptionid);

    req.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText.trim() == "option deleted") {
                console.log(this.responseText);
                requested_option.remove();
            }
        }
    }
}





function editConf( node ) {
    
    var poll = node.parentElement.parentElement;
    if ( pollToBeEdited = document.querySelector(".pollToBeEdited") ) {
        pollToBeEdited.classList.remove("pollToBeEdited");
    }
    poll.classList.add("pollToBeEdited");
    $(".edit-warning-modal-back").classList.add("show");

}

function okEditPoll() {
    var pollid = $(".pollToBeEdited").getAttribute("data-poll-id");
    if (pollid) {
        window.location = "editpoll.php?pollid=" + pollid;
    }
}
function addnewoption() {
    var optionCount = $$(".new-option-name").length + 1;
    var newOption = `
                    <div class='new-option-name'>
                        <input type="text" class='options' name="newoption${optionCount}" id="newoption${optionCount}" placeholder="Option ${optionCount}...">
                        <span class="new-option-cancel"  onclick="this.parentElement.remove()">x</span>
                    </div>`;

    document.querySelector("#slate").innerHTML = newOption;
    newOption = document.querySelector("#slate").querySelector(".new-option-name");
    document.querySelector("#slate").innerHTML = "";

    document.querySelector(".options-list").appendChild(newOption);
}



function updatePoll(updateButton) {
    updateButton.disabled = true;
    var pollId = $(".poll").getAttribute("data-poll-id");    
    var pollTile = $("#newpollname").value;
    var options = $$(".options");
    var category = $("#poll-category").value;
    var newPoll = new FormData();
    var tmp = "";
    options.forEach((e, i) => {
        if ( e.value.trim().length > 0 ) {
            tmp = tmp + e.value.trim() + "|";            
        }
    });

    if (pollTile.length < 1 || options[0].value.length < 1) {
        alert("Enter Data First");
        updateButton.disabled = false;
        return;
    }

    newPoll.append(
        "operation",
        "updatePoll"
    );
    newPoll.append(
        "pollTile",
        pollTile
    );
    newPoll.append(
        "pollId",
        pollId
    );
    newPoll.append(
        "pollOptions",
        tmp.substr(0, tmp.length - 1)
    );
    newPoll.append(
        "pollCategory",
        category
    );
        // alert(category);    
    var req = new XMLHttpRequest();
    req.open("POST", "backend.php", false);
    req.send(newPoll);
    console.log(req.responseText);
    
    if( req.responseText != "pollNOTUpdated" ) {
        window.location = "poll.php?pollid="+req.responseText;
    } else {
        alert("Something Went Worng!");
        window.location.reload();
    }
}


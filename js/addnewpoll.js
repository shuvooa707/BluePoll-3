function addnewoption() {    
    var optionCount = $$(".new-option-name").length + 1;
    var newOption = `
                    <div class='new-option-name'>
                        <input type="text" class='options' name="newoption${optionCount}" id="newoption${optionCount}" placeholder="Option ${optionCount}...">
                        <span class="new-option-cancel"  onclick="this.parentElement.remove()">x</span>
                    </div>`;
    
    document.querySelector("#slate").innerHTML =  newOption;
    newOption = document.querySelector("#slate").querySelector(".new-option-name");
    document.querySelector("#slate").innerHTML = "";

    document.querySelector(".options-list").appendChild(newOption);
}


function saveNewPoll( saveButton) {
    saveButton.disabled = true;
    var pollTile = $("#newpollname").value;
    var options = $$(".options");
    var category = $("#poll-category").value;
    var newPoll = new FormData();
    var tmp = "";
    options.forEach( (e,i) => {
        tmp = tmp + e.value.trim() + "|";
    });
    // alert(category.length);
    if (pollTile.length < 1 || options[0].value.length < 1 || category.length < 1 ) {
       alert("Enter Data First");
       saveButton.disabled = false;
       return; 
    }
    
    newPoll.append(
        "operation",
        "saveNewPoll"
    );
    newPoll.append(
        "pollTile",
        pollTile
    );
    newPoll.append(
        "pollOptions",
        tmp.substr(0, tmp.length - 1)
    );
    newPoll.append(
        "pollCategory",
        category
    );

    var req = new XMLHttpRequest();
    req.open("POST","backend.php",true);

    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status == 200) {
            saveButton.disabled = false;
            if( req.responseText != 0 ) {
                alert("Poll Added");
                console.log(window.location.origin + `/poll.php?pollid=${req.responseText}` );
                var a = window.location.href.split("/");
                a.splice(a.length - 1, 1);
                window.location = a.join("/") + `/poll.php?pollid=${req.responseText}`;
            }
        }
    }
    req.send(newPoll);
}



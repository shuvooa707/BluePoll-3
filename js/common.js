
function $$(inden) {
    return [...document.querySelectorAll(inden)];
}


function $(inden) {
    return document.querySelector(inden);
}

if( notification = $(".notification") ) {
    notification.addEventListener("click",toggleNot);
}

function toggleNot() {    
    var not = document.querySelector('#not');
    if (window.event.target == $(".notification") || window.event.target == $(".notification-close") || window.event.target == $(".not_count") || window.event.target == $(".notification").querySelector("span:first-child") ) {
        not.classList.toggle('show');
        // console.log($(".notification"));
        
        $(".notification").classList.toggle("high");
    }
}



window.addEventListener("keydown", () => {
    console.log(window.event);
    if (window.event.key == "Escape") {
        if (tmp = $(".notification-list")) {
            tmp.classList.remove("show");
        }
        if (tmp = $(".notification")) {
            tmp.classList.remove("high");
        }
        if (tmp = $(".edit-warning-modal-back")) {
            tmp.classList.remove("show");
        }
        if (tmp = $(".delete-con-modal-back")) {
            tmp.classList.remove("show");
        }
    }
});



function deleteNotification(node,notid) {    
    node.parentElement.classList.add("fade-overlay");
    console.log(node);
    
    var req = new XMLHttpRequest();
    req.open("POST", "backend.php", true);
    var data = new FormData();
    data.append(
        "operation",
        "deleteNotification"
    );
    data.append(
        "notificationid",
        notid
    );
    req.send(data);
    
    req.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200 ) {
            if (req.responseText == "n deleted") {
                node.parentElement.remove();
                if ($$(".single-notification").length == 0) {
                    $(".not_count").style.visibility = "hidden";
                    $(".notification").querySelector("path").style.fill = "black";
                } else {
                    $(".not_count").innerText = parseInt($(".not_count").innerText) - 1;
                }
            } else {
                alert(req.responseText);
            }

        }
    }
}

window.addEventListener("load",()=>{
    if ( !document.querySelector(".tab-switcher.tab-switcher-bio") ) {
        if (profile = $(".profile") ) {
            profile.classList.remove("profile");
        }
    }
    // injectSettingsDiv();
});



function injectSettingsDiv() {
    let settingsDiv = `
        <div class="settings-div">
            <i class="flaticon-settings"></i>
        </div>
    `;
    settingsDiv = ((new DOMParser()).parseFromString(settingsDiv, "text/html")).querySelector(".settings-div");
    console.log( settingsDiv );

    document.body.append(settingsDiv);
}
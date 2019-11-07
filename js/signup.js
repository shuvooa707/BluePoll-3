function register() {
    var input = document.querySelectorAll("input");
    // check if any field is empty or not 
    var flag = 1;
    for (let i = 0; i < input.length; i++) {
        // if empty then make the border red and shake it
        if (!input[i].value.length) {
            input[i].style.border = "1.5px solid red";
            input[i].classList.add("fieldempty");
            if (i == 13) {
                var chooseThumbImageCap = document.querySelector("label[for='imageCap']");
                chooseThumbImageCap.classList.add("fieldempty");
                input[i].offsetTop = chooseThumbImageCap.offsetTop;
            }
            window.scrollBy(0, input[i].offsetTop - window.pageYOffset - 200);
            console.log(input[i].offsetTop - window.pageYOffset - 200);
            setTimeout(() => {
                input[i].classList.remove("fieldempty");
            }, 1000);
            input[i].addEventListener("keypress", () => {
                input[i].style.border = "1.5px solid black";
            });
            flag = 0;
            break;
        }
    }
    var select = document.querySelector("select");
    if (select.value && flag) { // if everything is ok, click the submit button
        document.querySelector("#register").click();
    } else if (!select.value && flag) {
        select.style.border = "1.5px solid #c20a0a";
        select.classList.add("fieldempty");
        setTimeout(() => {
            select.classList.remove("fieldempty");
        }, 1000);
    }
}

function repasswordcheck(node) {
    var pass = node.previousElementSibling.previousElementSibling.value;
    var repass = node.value;
    // password and re-password doesn't match it will show an alert massger
    if (pass != repass) {
        node.nextElementSibling.style.color = "red";
        node.nextElementSibling.innerText = "***Password Doesn't match";
    } else {
        node.nextElementSibling.style.color = "green";
        node.nextElementSibling.innerText = "  Matched ";
        setTimeout(() => {
            node.nextElementSibling.style.color = "white";
            node.nextElementSibling.innerText = "";
        }, 1000);
    }
}

function showSelectedThumb(thumbImg) {
    console.log("Thumb Loaded !! ");
    let fr = new FileReader();
    fr.readAsDataURL(thumbImg.files[0]);
    fr.onload = function () {
        var st = document.querySelector("#signup-selected-thumb");
        st.src = fr.result;
        st.style.boxShadow = "0px 0px 5px #aaa";
        var chooseThumbImageCap = document.querySelector("label[for='imageCap']");
        chooseThumbImageCap.style.color = "#04d604";
    }
}
// user Name Availability Check
document.querySelector("#username").addEventListener("change", function () {
    const req = new XMLHttpRequest();
    req.open("POST", "dashboardOperationBackEnd.php", false);
    req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    req.send("usernameCheck=" + this.value);
    if (req.responseText == "user available") {
        document.querySelector("#uniqueuser").style.display = "block";
        this.value = "";
    } else {
        document.querySelector("#uniqueuser").style.display = "none";
    }
});

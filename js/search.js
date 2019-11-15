


function mark(params) {
    let searchKey = window.location.search.split("=")[1].toLocaleLowerCase();
    console.log(searchKey);
    
    Array.from(document.querySelectorAll(".result")).forEach(function (elem) {
        elem.querySelector(".poll-link").innerHTML = elem.querySelector(".poll-link").innerText.split(" ").map(function (elem) {
            if (elem.toLocaleLowerCase() == searchKey || elem.toLocaleLowerCase().includes(searchKey)) {
                if (elem.toLocaleLowerCase() == searchKey) {
                    return `<span style='background:orange;'>${elem}</span>`;
                } else {
                    return markHelper(elem, searchKey);
                }
            }
            else
                return elem;

        }).join(" "); 
        
        elem.querySelector(".poll-link-cat").innerHTML = elem.querySelector(".poll-link-cat").innerText.split(" ").map(function (elem) {
            if (elem.toLocaleLowerCase() == searchKey || elem.toLocaleLowerCase().includes(searchKey)) {
                if (elem.toLocaleLowerCase() == searchKey) {
                    return `<span style='background:orange;'>${elem}</span>`;
                } else {
                    return markHelper(elem, searchKey);
                }
            }
            else
                return elem;

        }).join(" ");

        // elem.querySelector(".post-content").innerHTML = elem.querySelector(".post-content").innerText.split(" ").map(function (word) {
        //     if (word.toLocaleLowerCase() == searchKey || word.toLocaleLowerCase().includes(searchKey)) {
        //         if (word.toLocaleLowerCase() == searchKey) {
        //             return `<span style='background:yellow;'>${word}</span>`;
        //         } else {
        //             return markHelper(word, searchKey);
        //         }
        //     }
        //     else {
        //         return word;
        //     }
        // }).join(" ");

    });

    function markHelper(text, word) {
        var acc = [];
        var arr = text.split(" ");
        arr.forEach((elem) => {
            if (elem.toLowerCase().includes(word)) {
                // 		acc.push(`<span style='background:yellow'>${elem}</span>`);
                var s = elem.toLowerCase().indexOf(word);
                var e = word.length;
                var butter = elem.substring(0, s) + `<span style='background:#ffc1079e;'>${word}</span>` + elem.substring(s + e);
                acc.push(butter);
            } else {
                acc.push(elem);
            }
        });
        return acc.join(" ");
    }
} mark();

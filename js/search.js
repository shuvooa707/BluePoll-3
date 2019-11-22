


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


function sortSearchResult(node) {
    var result = $$(".result");
    console.log(node.value);
    // sort by name
    if( node.value == "name" ) {
        result = result.sort((a, b) => {
            console.log(a.querySelector(".poll-link").innerText[0]);
            return a.querySelector(".poll-link").innerText[0].charCodeAt(0) - b.querySelector(".poll-link").innerText[0].charCodeAt(0);
        });
        // console.log(result);
        var parent = result[0].parentElement;
        parent.innerHTML = ` <div class="search-result-header" >
                                <select name="" id="" class='sort-order' onchange='sortSearchResult(this)'>
                                    <option value="date">Date</option>
                                    <option value="name" selected disabled>Name</option>
                                    <option value="view">Views</option>
                                    <option value="vote">Votes</option>
                                </select>
                            </div >`;
        result.forEach(r => {
            parent.appendChild(r);
        });
    }
    //  sort by date
    if (node.value == "date") {
        result = result.sort((a, b) => {
            var time1 = a.querySelector(".poll-link-date").innerText.split(":")[1].trim();
            time1 = new Date(time1.split("-")[0] + ":" + time1.split("-")[1] + ":20" + time1.split("-")[2]).getTime();
            var time2 = b.querySelector(".poll-link-date").innerText.split(":")[1].trim();
            time2 = new Date(time2.split("-")[0] + ":" + time2.split("-")[1] + ":20" + time2.split("-")[2]).getTime();

            return time1 - time2;
        });
        // console.log(result);
        var parent = result[0].parentElement;
        parent.innerHTML = ` <div class="search-result-header" >
                                <select name="" id="" class='sort-order' onchange='sortSearchResult(this)'>
                                    <option value="date"  selected disabled>Date</option>
                                    <option value="name">Name</option>
                                    <option value="view">Views</option>
                                    <option value="vote">Votes</option>
                                </select>
                            </div >`;
        result.forEach(r => {
            parent.appendChild(r);
        });
    }
    //  sort by votes
    if (node.value == "vote") {
        result = result.sort((a, b) => {
            return parseInt(a.querySelector(".poll-link-vote").innerText.split("Votes : ")[1]) - parseInt(b.querySelector(".poll-link-vote").innerText.split("Votes : ")[1]);
        });
        console.log(result);
        var parent = result[0].parentElement;
        parent.innerHTML = ` <div class="search-result-header" >
                                <select name="" id="" class='sort-order' onchange='sortSearchResult(this)'>
                                    <option value="date">Date</option>
                                    <option value="name">Name</option>
                                    <option value="view">Views</option>
                                    <option value="vote selected disabled">Votes</option>
                                </select>
                            </div >`;
        result.forEach(r => {
            parent.appendChild(r);
        });
    }
}
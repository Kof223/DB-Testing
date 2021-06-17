function httpGet() {
    let http = new XMLHttpRequest();
    url = 'index.php/data';
  
    http.open("GET", url, true);
    http.responseType = 'text';
    http.onload = function () {
        if (http.readyState === http.DONE) {
            return document.getElementById("result").innerHTML = http.responseText;
        }
    }
    http.send();
}

function httpFind(name) {
    let http = new XMLHttpRequest();
    url = 'index.php/data/';
  
    http.open("GET", url.concat(name), true);
    http.responseType = 'text';
    http.onload = function () {
        if (http.readyState === http.DONE) {
            return document.getElementById("result").innerHTML = http.responseText;
        }
    }
    http.send();
}

function httpDelete(name) {
    let http = new XMLHttpRequest();
    url = 'index.php/data/';

    http.open("DELETE", url.concat(name), true);
    http.responseType = 'text';
    http.onload = function () {
        if (http.readyState === http.DONE) {
            return document.getElementById("result").innerHTML = http.responseText;
        }
    }
    http.send();
}

function httpCreate(name, age) {
    let input = {}
    input["name"] = name;
    input["age"] = age;

    let http = new XMLHttpRequest();
    url = 'index.php/data/';
  
    http.open("POST", url, true);
    http.responseType = 'text';
    http.onload = function () {
        if (http.readyState === http.DONE) {
            return document.getElementById("result").innerHTML = http.responseText;
        }
    }
    http.send(JSON.stringify(input));
}

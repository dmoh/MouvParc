var form = new FormData();
form.append("toTop", "true");
var cellules = [{"toTop":true,"cells": [{"columnId": 8733849107621764, "value": "Test depuis mouvparc"},{"columnId": 8733849107621764, "value": "Panne numero 438743"}]}];


form.append("cells", "");

var settings = {
    "async": true,
    "crossDomain": true,
    "url": "https://api.smartsheet.com/2.0/sheets/5332176532203396/rows",
    "method": "POST",
    "headers": {
        "Content-Type": "application/json",
        "Cache-Control": "no-cache",
    },
    "processData": false,
    "contentType": false,
    "mimeType": "multipart/form-data",
    "data": cellules
}

$.ajax(settings).done(function (response) {
    console.log(response);
});

var xhr = new XMLHttpRequest();
xhr.open("POST","https://api.smartsheet.com/2.0/sheets/5332176532203396/rows", true);
xhr.setRequestHeader(
   "Content-Type","application/json"
);
xhr.send(JSON.stringify(cellules));
xhr.onload = function (response) {
    console.log('DONE', xhr.readyState); // readyState will be 4
};
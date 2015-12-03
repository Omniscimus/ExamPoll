// JavaScript script die de ASP.NET_SessionId uit een Itslearning cookie haalt.

var cookie = process.argv[2];
var pattern = /[A-Za-z]*\d\w+/;

var results = pattern.exec(cookie);
var id;
if (results !== null) {
    id = results[0];
} else {
    id = "";
}

console.log(id);

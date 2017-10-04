var m = require("mithril");
var Header = require("./header");
var Stream = require("./stream");
var Register = require("./register");
var Profile = require("./profile");
var Footer = require("./footer");

m.mount(document.getElementById("header"), Header);

var content = document.getElementById("content");
m.route(content, "stream", {
    "stream": Stream,
    "register": Register,
    "profile": Profile
});

m.mount(document.getElementById("footer"), Footer);

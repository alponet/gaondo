var m = require("mithril");
var Header = require("./header");
var Feed = require("./feed");
var Register = require("./register");
var Footer = require("./footer");

m.mount(document.getElementById("header"), Header);

var content = document.getElementById("content");
m.route(content, "/feed", {
    "/feed": Feed,
    "/register": Register
});

m.mount(document.getElementById("footer"), Footer);

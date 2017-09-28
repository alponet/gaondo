var m = require("mithril");
var Header = require("./header");
var Register = require("./register");
var Footer = require("./footer");

m.mount(document.getElementById("header"), Header);
m.mount(document.getElementById("content"), Register);
m.mount(document.getElementById("footer"), Footer);

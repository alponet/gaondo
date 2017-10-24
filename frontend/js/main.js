var m = require("mithril");
var Header = require("./header");
var Stream = require("./stream");
var Register = require("./register");
var Profile = require("./profile");
var MakeMeme = require("./makeMeme");
var Footer = require("./footer");

m.request("/i18n/").then(function (msgs) {
    window.t = msgs;

    m.mount(document.getElementById("header"), Header);

    var content = document.getElementById("content");
    m.route(content, "stream", {
        "stream": Stream,
        "register": Register,
        "profile": Profile,
        "newMeme": MakeMeme
    });

    m.mount(document.getElementById("footer"), Footer);
});


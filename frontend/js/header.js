var m = require("mithril");

var LoggedInView = {
    view: function () {
        return [
            m("a[href=/#!newMeme]", "Upload"),
            m("a[href=/#!profile]", Header.username),
            m("a[href=/logout]", "Logout")
        ];
    }
};

var LoggedOutView = {
    view: function () {
        return [
            m("a[href=#!register]", "Register"),
            m("a[href=/login]", "Login")
        ];
    }
};

var Header = {
    username: '',
    oninit: function () {
        m.request("/u")
            .then(function (user) {
                Header.username = user.name;
            })
    },
    view: function () {
        return m(".header", [
            m("h1.logo", m("a[href=/]", "Gaondo")),
            m(".nav", Header.username ? m(LoggedInView) : m(LoggedOutView))
        ]);
    }
};

module.exports = Header;
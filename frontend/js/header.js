var m = require("mithril");

var LoggedInView = {
    view: function () {
        return [
            m("a[href=/#!newMeme]", t.main.upload),
            m("a[href=/#!profile]", Header.username),
            m("a[href=/logout]", t.main.logout)
        ];
    }
};

var LoggedOutView = {
    view: function () {
        return [
            m("a[href=#!register]", t.main.register),
            m("a[href=/login]", t.main.login)
        ];
    }
};

var Header = {
    username: '',
    oninit: function () {
        m.request("/u/")
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
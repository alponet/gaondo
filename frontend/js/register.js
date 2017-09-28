var m = require("mithril");

module.exports = {
    view: function () {
        return m(".register", [
            m("h1", "register"),
            m("form", [
                m("input", "name"),
                m("input", "email"),
                m("button", "register")
            ])
        ])
    }
}
var m = require("mithril");

module.exports = {
    view: function () {
        return m(".header", [
            m("h1", "Gaonda"),
            m("button", "Login")
        ]);
    }
}
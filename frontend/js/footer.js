var m = require("mithril");

module.exports = {
    view: function () {
        return m(".copyright", m.trust("&copy; 2017"));
    }
}
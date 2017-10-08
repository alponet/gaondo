var m = require("mithril");

var MakeMeme = {
    view: function () {
        return m(".newMeme",
            m("h2", "New Meme"),
            m("p", "Coming soon.")
        );
    }
};

module.exports = MakeMeme;
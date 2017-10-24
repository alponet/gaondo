var m = require("mithril");

var Stream = {
    memes: [],
    oninit: function () {
        m.request("/m/").then(function (response) {
            Stream.memes = response;
        });
    },
    view: function () {
        return m(".stream", Stream.memes.map(function (meme) {
            return m(".meme#m" + meme.id,
                m("h3", meme.title),
                m("img[src=" + meme.file + "]"),
                m(".description", meme.description),
                m(".date", meme.date.date),
                m(".author", meme.author)
            );
        }));
    }
};

module.exports = Stream;
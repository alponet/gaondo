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
                m("h3", m("a[href=m/" + meme.id + "]", meme.title)),
                m("a[href=m/" + meme.id + "]", m("img[src=" + meme.file + "]")),
                m(".description", meme.description),
                m(".date", meme.date.date),
                m(".author", meme.author)
            );
        }));
    }
};

module.exports = Stream;
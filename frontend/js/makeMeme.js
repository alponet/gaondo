var m = require("mithril");

var MakeMeme = {
    title: '',
    file: '',
    description: '',
    status: '',
    upload: function (e) {
        e.preventDefault();
        MakeMeme.status = 'uploading...';

        var formData = new FormData();
        formData.set("title", MakeMeme.title);
        formData.set("file", MakeMeme.file);
        formData.set("description", MakeMeme.description);

        m.request({
            method: "POST",
            url: "/m/",
            data: formData
        }).then(function (response) {
            MakeMeme.status = response;
        })
    },
    view: function () {
        return m(".newMeme",
            m("h2", "New Meme"),
            m("form",
                m("label", "title"),
                m("input[type=text][placeholder=title]", {
                    value: MakeMeme.title,
                    onchange: function(e) {
                        MakeMeme.title = e.currentTarget.value;
                    }}),
                m("label", "file"),
                m("input[type=file][accept=image/*]", {
                    onchange: function(e) {
                        MakeMeme.file = e.target.files[0];
                    }}),
                m("label", "description"),
                m("textarea", {
                    value: MakeMeme.description,
                    onchange: function(e) {
                        MakeMeme.description = e.currentTarget.value;
                    }}),
                m(".status", MakeMeme.status.hasOwnProperty("errors") ?
                    MakeMeme.status.errors.map(function (t) {
                        return m("label.error", t.detail);
                    }) :
                    MakeMeme.status.hasOwnProperty('success') ?
                        m("label.success", MakeMeme.status.success) :
                        MakeMeme.status),
                m("button[type=submit]", { onclick: MakeMeme.upload }, "upload")
            )
        );
    }
};

module.exports = MakeMeme;
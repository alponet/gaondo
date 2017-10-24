var m = require("mithril");

var Register = {
    name: '',
    email: '',
    status: {},
    submit: function (e) {
        e.preventDefault();

        var formData = new FormData();
        formData.set("name", Register.name);
        formData.set("email", Register.email);

        m.request({
            method: "POST",
            url: "/u/",
            data: formData
        }).then(function (response) {
            console.log(response);
            Register.status = response;
        });
    },
    view: function (vnode) {
        return [
            m("h2", t.register.register),
            m("form",
                m("label", t.register.name),
                m("input[type=text][placeholder=" + t.register.name + "]", {
                    value: Register.name,
                    onchange: function(e) {
                        Register.name = e.currentTarget.value;
                    }
                }),
                m("label", t.register.email),
                m("input[placeholder=" + t.register.email + "]", {
                    value: Register.email,
                    onchange: function(e) {
                        Register.email = e.currentTarget.value;
                    }
                }),
                m(".status", Register.status.hasOwnProperty("errors") ? Register.status.errors.map(function (t) {
                        return m("label.error", t.detail);
                    }) : Register.status.hasOwnProperty('success') ? m("label.success", Register.status.success) : ''),
                m("button", { onclick: Register.submit }, t.register.register)
            )
        ];
    }
};

module.exports = Register;
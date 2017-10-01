var m = require("mithril");

var Register = {
    name: '',
    email: '',
    status: {},
    submit: function (e) {
        e.preventDefault();
        console.log(Register);

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
        return m(".register",
            m("h1", "register"),
            m("form",
                m("label", "name"),
                m("input[type=text][placeholder=name]", {
                    value: Register.name,
                    onchange: function(e) {
                        Register.name = e.currentTarget.value;
                    }
                }),
                m("label", "email"),
                m("input[placeholder=email]", {
                    value: Register.email,
                    onchange: function(e) {
                        Register.email = e.currentTarget.value;
                    }
                }),
                m(".status", Register.status.hasOwnProperty("errors") ? Register.status.errors.map(function (t) {
                        return m("label.error", t.detail);
                    }) : Register.status.hasOwnProperty('success') ? m("label.success", Register.status.success) : ''),
                m("button", { onclick: Register.submit }, "register")
            )
        )
    }
}

module.exports = Register;
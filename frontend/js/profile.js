var m = require("mithril");

var PasswordChangeView = {
    oldPassword: '',
    newPassword: '',
    verification: '',
    status: '',
    view: function () {
        return m("form.border",
            m("h3", "Change password"),
            m("label", "old password"),
            m("input[type=password]", {
                value: PasswordChangeView.oldPassword,
                onchange: function(e) {
                    PasswordChangeView.oldPassword = e.currentTarget.value;
                }}
            ),
            m("label", "new password"),
            m("input[type=password]", {
                value: PasswordChangeView.newPassword,
                onchange: function(e) {
                    PasswordChangeView.newPassword = e.currentTarget.value;
                }}),
            m("label", "verify new password"),
            m("input[type=password]", {
                value: PasswordChangeView.verification,
                onchange: function(e) {
                    PasswordChangeView.verification = e.currentTarget.value;
                }}),
            m(".status", PasswordChangeView.status.hasOwnProperty("errors") ? PasswordChangeView.status.errors.map(function (t) {
                return m("label.error", t.detail);
            }) : PasswordChangeView.status.success ? m("label.success", 'your password has been changed') : ''),
            m("button[type=reset]",
                { onclick: function() {
                    Profile.passwordChange = false;
                }},
                "cancel"
            ),
            m("button[type=submit]",
                { onclick: function () {
                    m.request({
                        url: "/u/" + Profile.user.id,
                        method: "PUT",
                        data: {
                            "oldPassword": PasswordChangeView.oldPassword,
                            "newPassword": PasswordChangeView.newPassword
                        }
                    }).then(function (response) {
                        PasswordChangeView.status = response;
                        if (response.success) {
                            PasswordChangeView.oldPassword = '';
                            PasswordChangeView.newPassword = '';
                            PasswordChangeView.verification = '';
                        }
                    });
                }}
                , "submit")
        );
    }
};

var DeleteAccountView = {
    password: '',
    status: '',
    view: function () {
        return m("form.border",
            m("h3", "Delete account"),
            m("label.error", "Are you sure you want to delete your account?"),
            m("label.error", "This is not reversible!"),
            m("label.error", "Enter your password to confirm"),
            m("label", "password"),
            m("input[type=password]", {
                value: DeleteAccountView.password,
                onchange: function(e) {
                    DeleteAccountView.password = e.currentTarget.value;
                }}
            ),
            m(".status", DeleteAccountView.status.hasOwnProperty("errors") ? DeleteAccountView.status.errors.map(function (t) {
                return m("label.error", t.detail);
            }) : DeleteAccountView.status.success ? m("label.success", 'your account has been deleted') : ''),
            m("button[type=reset]",
                { onclick: function() {
                    Profile.deleteAccount = false;
                }},
                "cancel"
            ),
            m("button[type=submit]",
                { onclick: function () {
                    m.request({
                        url: "/u/" + Profile.user.id,
                        method: "DELETE",
                        data: {
                            "password": DeleteAccountView.password,
                        }
                    }).then(function (response) {
                        DeleteAccountView.status = response;
                        if (response.success) {
                            setTimeout(function () {
                                window.location.href = "/logout";
                            }, 2000);
                        }
                    });
                }}
                , "submit")
        );
    }
};

var Profile = {
    user: {},
    passwordChange: false,
    deleteAccount: false,
    oninit: function () {
        m.request("/u").then(function(user) {
            Profile.user = user;
            if (!user.hasOwnProperty("name")) {
                window.location.href = "/login";
            }
        });
    },
    view: function() {
        return m(".profile",
            Profile.user.hasOwnProperty("name") ?
                [
                    m("h2", Profile.user.name),
                    m("p", Profile.user.email),
                    Profile.passwordChange ?
                        m(PasswordChangeView) :
                        m("a[href=#]",
                            { onclick: function(e) {
                                e.preventDefault();
                                Profile.passwordChange = true;
                            } },
                            "change password"),
                    Profile.deleteAccount ?
                        m(DeleteAccountView) :
                        m("a[href=#]",
                            { onclick: function(e) {
                                e.preventDefault();
                                Profile.deleteAccount = true;
                            } },
                            "delete account")
                ] :
                "loading..."
        );
    }
};

module.exports = Profile;
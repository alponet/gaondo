var m = require("mithril");

var PasswordChangeView = {
    oldPassword: '',
    newPassword: '',
    verification: '',
    status: '',
    view: function () {
        return m("form.border",
            m("h3", t.profile.changePassword),
            m("label", t.profile.oldPassword),
            m("input[type=password]", {
                value: PasswordChangeView.oldPassword,
                onchange: function(e) {
                    PasswordChangeView.oldPassword = e.currentTarget.value;
                }}
            ),
            m("label", t.profile.newPassword),
            m("input[type=password]", {
                value: PasswordChangeView.newPassword,
                onchange: function(e) {
                    PasswordChangeView.newPassword = e.currentTarget.value;
                }}),
            m("label", t.profile.verifyNewPassword),
            m("input[type=password]", {
                value: PasswordChangeView.verification,
                onchange: function(e) {
                    PasswordChangeView.verification = e.currentTarget.value;
                }}),
            m(".status", PasswordChangeView.status.hasOwnProperty("errors") ?
                PasswordChangeView.status.errors.map(function (err) {
                    return m("label.error", err.detail);
                }) :
                PasswordChangeView.status.success ? m("label.success", t.profile.passwordChanged) : ''),
            m("button[type=reset]",
                { onclick: function() {
                    Profile.passwordChange = false;
                }},
                t.main.cancel
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
                , t.main.submit)
        );
    }
};

var DeleteAccountView = {
    password: '',
    status: '',
    view: function () {
        return m("form.border",
            m("h3", t.profile.deleteAccount),
            m("label.error", t.profile.reallyDeleteAcc),
            m("label.error", t.profile.notReversible),
            m("label.error", t.profile.passwordToConfirm),
            m("label", t.main.password),
            m("input[type=password]", {
                value: DeleteAccountView.password,
                onchange: function(e) {
                    DeleteAccountView.password = e.currentTarget.value;
                }}
            ),
            m(".status", DeleteAccountView.status.hasOwnProperty("errors") ?
                DeleteAccountView.status.errors.map(function (err) {
                    return m("label.error", err.detail);
                }) :
                DeleteAccountView.status.success ? m("label.success", t.profile.accDeleted) : ''),
            m("button[type=reset]",
                { onclick: function() {
                    Profile.deleteAccount = false;
                }},
                t.main.cancel
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
                                window.location.replace("/");
                            }, 2000);
                        }
                    });
                }}
                , t.main.submit)
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
                            t.profile.changePassword),
                    Profile.deleteAccount ?
                        m(DeleteAccountView) :
                        m("a[href=#]",
                            { onclick: function(e) {
                                e.preventDefault();
                                Profile.deleteAccount = true;
                            } },
                            t.profile.deleteAccount)
                ] :
                t.status.loading
        );
    }
};

module.exports = Profile;
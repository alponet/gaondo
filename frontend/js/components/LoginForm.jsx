import React from "react";

export default class LoginForm extends React.Component {

    constructor() {
        super();
        this.state = {
            _username: '',
            _password: '',
            status: ''
        };

        this.handleNameChange = this.handleNameChange.bind(this);
        this.handlePasswordChange = this.handlePasswordChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleNameChange(e) {
        this.setState({
            status: '',
            _username: e.target.value
        });
    }

    handlePasswordChange(e) {
        this.setState({
            status: '',
            _password: e.target.value
        });
    }

    handleSubmit(e) {
        e.preventDefault();
        let self = this;

        this.setState({ status: "..." });

        jQuery.post("/login_check", this.state).done(function(re) {
            if (re.hasOwnProperty("errors")) {
                self.setState({ status: re["errors"][0].detail });
            } else if(re.hasOwnProperty("success") && re.success === true) {
                self.setState({
                    status: "¡Bienvenido!",
                    name: '',
                    password: ''
                });

                if (window.location.pathname.includes("/login")) {
                    window.location.replace("/");
                }
            } else {
                self.setState({
                    status: "Acceso fallido."
                });
            }
        });
    }

    render() {
        return (
            <div className = "login rose-box">
                <div className = "title">
                    <h2>Pa dentro</h2>
                </div>
                <div className="content">
                    <form onSubmit={this.handleSubmit}>
                        <label htmlFor="username">Nombre de usuario</label>
                        <input id="username" name="_username" value={this.state._username} onChange={this.handleNameChange} required="required" type="text" />
                        <label htmlFor="password">Contraseña</label>
                        <input id="password" name="_password" value={this.state._password} onChange={this.handlePasswordChange} required="required" type="password" />
                        <p>{this.state.status}</p>
                        <input id="_submit" name="_submit" className="button-red" value="Entrar" type="submit" />
                    </form>
                    <p>
                        <a href="/resetting/request">¿Olvidaste el password?</a>
                    </p>
                </div>
                <div className="register">
                    <hr />
                    <a href="/register" className="button-red">Crear cuenta</a>
                </div>
            </div>

        );
    }
}

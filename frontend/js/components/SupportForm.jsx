import React from "react";

export default class SupportForm extends React.Component {

    constructor() {
        super();
        this.state = {
            name: '',
            email: '',
            subject: '',
            message: '',
            status: ''
        };

        this.handleNameChange = this.handleNameChange.bind(this);
        this.handleEmailChange = this.handleEmailChange.bind(this);
        this.handleSubjectChange = this.handleSubjectChange.bind(this);
        this.handleMessageChange = this.handleMessageChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleNameChange(e) {
        this.setState({
            status: '',
            name: e.target.value
        });
    }

    handleEmailChange(e) {
        this.setState({
            status: '',
            email: e.target.value
        });
    }

    handleSubjectChange(e) {
        this.setState({
            status: '',
            subject: e.target.value
        });
    }

    handleMessageChange(e) {
        this.setState({
            status: '',
            message: e.target.value
        });
    }

    handleSubmit(e) {
        e.preventDefault();
        let self = this;

        this.setState({ status: "sending" });

        jQuery.post("/support/", this.state).done(function(re) {
            if (re.hasOwnProperty("errors")) {
                self.setState({ status: re["errors"][0].detail });
            } else if(re.hasOwnProperty("success")) {
                self.setState({
                    status: "Mensaje enviado, gracias.",
                    name: '',
                    email: '',
                    subject: '',
                    message: ''
                });
            } else {
                self.setState({
                    status: "something went wrong"
                });
            }
        });
    }

    render() {
        return (
            <div className="support-form rose-box">
                <div className="title">
                    <h2>Contacto</h2>
                </div>
                <div className="content">
                    <form onSubmit={this.handleSubmit}>
                        <label htmlFor="name">Nombre</label>
                        <input name="name" type="text" placeholder="Nombre" value={this.state.name} onChange={this.handleNameChange} />
                        <label htmlFor="email">Email *</label>
                        <input name="email" type="text" placeholder="Email" value={this.state.email} onChange={this.handleEmailChange} />
                        <label htmlFor="subject">Asunto</label>
                        <input name="subject" type="text" placeholder="Asunto" value={this.state.subject} onChange={this.handleSubjectChange} />
                        <label htmlFor="message">Mensaje *</label>
                        <textarea name="message" rows="8" placeholder="Mensaje" value={this.state.message} onChange={this.handleMessageChange} />
                        <p>{this.state.status}</p>
                        <button type="submit" className="button-red">Enviar</button>
                    </form>
                </div>
            </div>
        );
    }
}
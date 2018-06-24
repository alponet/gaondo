import React from "react";

export default class About extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            showText: false
        };
    }


    toggleText() {
        this.setState({
            showText: !this.state.showText
        }, function() {
            _paq.push(['trackEvent', 'toggleAboutText']);
        });
    }


    render() {
        return (
            <div className="rose-box">
                <div className = "title">
                    <h2>¡Bienvenid@!</h2>
                </div>
                <div className="content" onClick={() => this.toggleText()}>
                    <h3>¿Que es Gaondo?</h3>
                    <div style={{ display: this.state.showText ? "block" : "none" }}>
                        <p>Gaondo es una comunidad online dedicada a los memes y el humor argentino, hecho por y para nosotr@s. Particularmente nos interesa el humor político, social y futbolístico, pero reír es reír, así que otras temáticas también nos divierten.</p>
                        <p>Crea una cuenta para poder postear, comentar y votar. ¿Sabías que tenemos nuestro propio Creador de Memes? Una vez hecho el posteo, podés compartirlo directamente en tu cuenta de Twitter y Facebook clickeando en los iconos de abajo.</p>
                    </div>
                </div>
            </div>
        );
    }
}
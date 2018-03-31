import React from "react";
import ReactDOM from "react-dom";
import Greetings from "./greetings";
import Administration from "./components/Administration";

ReactDOM.render(
    <Greetings />,
    document.getElementById("react")
);

if ($('#admin-page').length) {
    ReactDOM.render(
        <Administration />,
        document.getElementById("admin-page")
    );
}
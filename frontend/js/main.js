import React from "react";
import ReactDOM from "react-dom";
import Administration from "./components/Administration";

if ($('#admin-page').length) {
    ReactDOM.render(
        <Administration />,
        document.getElementById("admin-page")
    );
}
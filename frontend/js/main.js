import React from "react";
import ReactDOM from "react-dom";
import Administration from "./components/Administration";
import SupportForm from "./components/SupportForm";

if ($('#admin-page').length) {
    ReactDOM.render(
        <Administration />,
        document.getElementById("admin-page")
    );
}

if ($('#support-form').length) {
    ReactDOM.render(
        <SupportForm/>,
        document.getElementById("support-form")
    );
}
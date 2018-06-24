import React from "react";
import ReactDOM from "react-dom";
import Administration from "./components/Administration";
import SupportForm from "./components/SupportForm";
import LoginForm from "./components/LoginForm";
import About from "./components/About";


window.gaondo = {};

gaondo.isLoggedIn = false;

/**
 * reply to comments
 */
gaondo.replyForm = function(subjectId) {
    let cf = $('.commentForm').first().clone();
    $('.commentForm:not(:first)').remove();
    cf.attr('id', 'commentForm'+subjectId).appendTo('#comment'+subjectId+" .text").hide().fadeIn();
    cf.find('#comment_replyTo').val(subjectId);
    cf.find('input[type="text"]').attr('id', 'comment_text'+subjectId).focus();
    cf.find('label[for="comment_text"]').attr('for', 'comment_text'+subjectId);
    $('.close-commentForm').click(function () {
        $('.commentForm:not(:first)').fadeOut();
    });
};


gaondo.deleteComment = function(commentId) {

    // ToDo: translation: comments.delete.confirm

    let confirmString = "¿Querés fletar el comentario?";
    if ( window.confirm(confirmString) ) {
        $.ajax({
            url: "/c/" + commentId + "/",
            type: 'DELETE'
        }).done((response) => {
            console.log(response);
            window.location.reload();
        });
    }
};


gaondo.voteUp = function(subjectId) {
    vote( subjectId, 1 );
};

gaondo.voteDown = function(subjectId) {
    vote( subjectId, -1 );
};

let vote = function(subjectId, value) {
    if(!gaondo.isLoggedIn) {
        gaondo.showLoginOverlay();
    } else {
        let formData = new FormData();
        formData.append("value", value);

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "/m/" + subjectId + "/vote", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                document.getElementById("score_" + subjectId).innerText = xhr.response;
            }
        };
        xhr.send(formData);
    }
};


gaondo.deleteMeme = function(id) {

    // ToDo: translation: meme.delete.confirm

    let confirmString = '¿Posta queres borrar el meme?';
    if ( window.confirm(confirmString) ) {
        $.ajax({
            url: "/m/" + id + "/",
            type: 'DELETE'
        }).done((response) => {
            window.location.replace("/");
        });
    }
};


gaondo.showLoginOverlay = function() {
    ReactDOM.render(<LoginForm/>, document.getElementById("overlay"));

    let overlay = document.getElementById("overlay");
    overlay.style.display = "block";
    overlay.addEventListener("click", function( e ){
        e = window.event || e;
        if(this === e.target) {
            overlay.style.display = "none";
            _paq.push(['trackEvent', 'LoginOverlay', 'hide']);
        }
    });

    _paq.push(['trackEvent', 'LoginOverlay', 'show']);
};


$('document').ready(function(){
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

    if ($('#login-form').length) {
        ReactDOM.render(
            <LoginForm/>,
            document.getElementById("login-form")
        );
    }

    if ($('#about').length && !gaondo.isLoggedIn) {
        ReactDOM.render(
            <About/>,
            document.getElementById("about")
        );
    }
});

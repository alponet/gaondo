import React from "react";
import ReactDOM from "react-dom";
import Administration from "./components/Administration";


window.gaondo = {};

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
    let formData = new FormData();
    formData.set("value", value);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "/m/" + subjectId + "/vote", true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            document.getElementById("score_" + subjectId).innerText = xhr.response;
        }
    };
    xhr.send(formData);
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


if ($('#admin-page').length) {
    ReactDOM.render(
        <Administration />,
        document.getElementById("admin-page")
    );
}
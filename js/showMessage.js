document.addEventListener("DOMContentLoaded", function () {

    var messageBox = document.createElement("div");
    messageBox.className = "message-box";

    var messageContent = document.createElement("div");
    messageContent.className = "message-content";

    var messageText = document.createElement("p");
    messageText.textContent = jsMessageText; 

    var closeButton = document.createElement("button");
    closeButton.textContent = "Close";
    closeButton.className = "close-button";
    closeButton.addEventListener("click", function () {
        closeMessageBox();
    });

    messageContent.appendChild(messageText);
    messageContent.appendChild(closeButton);
    messageBox.appendChild(messageContent);
    document.body.appendChild(messageBox);


    function closeMessageBox() {
        document.body.removeChild(messageBox);
    }


    document.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            closeMessageBox();
        }
    });
});

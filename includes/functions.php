<?php

function displaySessionMessage()
{
    if (isset($_SESSION['sessionMessage'])) {
        $messageText = $_SESSION['sessionMessage'];

        echo '<script>var jsMessageText = "' . $messageText . '";</script>';


        unset($_SESSION['sessionMessage']);
    }
}

function setSessionMessage($messageText, $messageType = 'info')
{
    $_SESSION['sessionMessage'] = $messageText;
}
?>
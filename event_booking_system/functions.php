<?php
function sanitizeInput($input) {
    return htmlspecialchars(stripslashes(trim($input)));
}
?>

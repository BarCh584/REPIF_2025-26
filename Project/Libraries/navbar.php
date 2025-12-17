<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
function createnavbarelement($elementname, $link, $isselected)
{
    if ($isselected) {
        echo '<a href="' . $link . '" class="nav-child selected">' . $elementname . '</a>';
        return;
    } else {
        echo '<a href="' . $link . '" class="nav-child">' . $elementname . '</a>';
    }
}
?>
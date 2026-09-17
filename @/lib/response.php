<?php

function exitWithRedirect(string $url) {
    header("Location: " . urlOf($url));
    exit();
}

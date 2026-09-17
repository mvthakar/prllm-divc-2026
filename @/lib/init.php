<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

$dir = "/c";
$baseDir = $_SERVER['DOCUMENT_ROOT'] . $dir;
$baseUrl = $dir;

function pathOf(string $path): string {
    global $baseDir;
    return $baseDir . $path;
}

function urlOf(string $url): string {
    global $baseUrl;
    return $baseUrl . $url;
}


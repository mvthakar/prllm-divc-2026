<?php

function get(string $key): string {
    return $_GET[$key] ?? null;
}

function post(string $key): string | null {
    return $_POST[$key] ?? null;
}
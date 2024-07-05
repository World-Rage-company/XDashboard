<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_unset();

    session_destroy();

    http_response_code(200);
    exit();
} else {
    http_response_code(405);
    exit();
}

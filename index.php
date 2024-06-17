<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */

session_start();

require "config/autoload.php";
if(!isset($_SESSION['csrf_token'])) {
    $tokenCSRF = new CSRFTokenManager();
    $token = $tokenCSRF->generateCSRFToken();
    $_SESSION['csrf_token'] = $token;
}

if(!isset($_SESSION["lang"])) {
    $_SESSION["lang"] = "fr";
}

$router = new Router();

$router->handleRequest($_GET);

print_r($_SESSION);
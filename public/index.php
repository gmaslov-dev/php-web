<?php

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($path) {
  case '/':
    echo <<<EOT
    <a href="/welcome">welcome</a>
    <br>
    <a href="/not-found">not-found</a>
    EOT;
    break;
  case '/welcome':
    echo '<a href="/">main</a>';
    break;
  default:
    header("HTTP/1.1 404");
    echo 'Page not found. <a href="/">main</a>';
}



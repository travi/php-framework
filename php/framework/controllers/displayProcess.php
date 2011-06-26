<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    content();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    list($status, $msg, $redirectTo) = process();
    $page->redirect($status, $msg, $redirectTo);
}

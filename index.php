?<?php

require_once 'model.php';

$uri = $_SERVER['REQUEST_URI'];
if ($uri == '/index.php' || $uri == '/' || isset($_GET['state'])) {
    list_friends();
} elseif (isset($_GET['dest_id'])) {
    http_to_friends($_GET['src_id'], $_GET['dest_id']);
} else {
    header('Status: 404 Not Found');
    echo '<html><body><h1>ページが見つかりません</h1></body></html>';
}
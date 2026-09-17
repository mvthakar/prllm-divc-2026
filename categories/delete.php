<?php

require_once '../@/lib/init.php';
require_once pathOf('/@/lib/database.php');
require_once pathOf('/@/lib/request.php');
require_once pathOf('/@/lib/response.php');

$id = get('id');
if ($id == null) {
    exitWithRedirect('/categories');
}

$db = Database::getInstance();
$db->execute(
    "UPDATE `categories` SET `deleted_at` = ? WHERE `id` = ?",
    [date('Y-m-d H:i:s'), $id]
);

exitWithRedirect('/categories');

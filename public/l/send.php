<?php
session_start();
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once __DIR__.'/RetailCRM.php'; //1. Подключить данный файл

// Retrieve the hidden fields
$source = isset($_POST['source']) ? $_POST['source'] : '';
$store = isset($_POST['store']) ? $_POST['store'] : '';
$article = isset($_POST['article']) ? $_POST['article'] : '';
$pixel_id = isset($_POST['pixel_id']) ? $_POST['pixel_id'] : '';

$site_code = $store; //2. Выбрать необходимый магазин https://mgoods.retailcrm.ru/admin/sites и указать его символьный код
$item_id = $article; // 3. Указать "Внешний код" торгового предложения из карточки нужного товара

$integration = new RetailCRM($site_code, $item_id);
$order = $integration->getOrderFromPost();
if ($integration->isDuplicate($order)) {
    header("Location: dubl.php?pixel_id=" . urlencode($pixel_id)); //5. Указать нужный адрес для перенаправления в случае дубля
    die();
}

$integration->sendToCrm($order); //5. Отправление данных в срм

// Redirect to the thank you page with pixel_id as a query parameter
header("Location: thanks.php?pixel_id=" . urlencode($pixel_id));
exit();
?>

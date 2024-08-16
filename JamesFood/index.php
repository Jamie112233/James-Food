<?php
session_start();

require_once 'admin/connect.php';

$root_path = 'http://localhost:31337/webdev2/ProjectJames/JamesFood';

$predefined_routes = [
    'sign-up',
    'sign-in',
    'sign-out'
];

$page = '';

$route = explode('/', $_GET['slugurl']);
$slug_url = $route[0];
if (isset($route[1])) {
    $page = $route[1];
    $param = $route[2];
}

$query = 'SELECT * FROM categories';
$stmt = $db->prepare($query);
$stmt->execute();
$rows = $stmt->fetchAll();

$categories = [];
foreach ($rows as $row) {
    $categories[] = [
        'category_name' => $row->category_name,
        'slug_url' => $row->slug_url
    ];
}

if (!in_array($_GET['slugurl'], $predefined_routes)) {
    if (!isset($_GET['slugurl']) && count($categories) > 0) {
        header('Location: ./' . $categories[0]['slug_url']);
        die;
    }
} else {
    $page = $_GET['slugurl'];
}

$page_to_show = $page != '' ? $page . '.php' : 'main.php';

include $page_to_show;
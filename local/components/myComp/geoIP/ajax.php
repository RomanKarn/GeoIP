<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/components/myComp/geoIP/class.php';


$result = array(
    'data'  => '',
    'error' => false
);

if (empty($_POST['IP'])) {
    $result["data"] = "Пустое поле";
    $result['error'] = true;
    echo json_encode($result);
    die();
}

$geoIPComponent = new GeoIPComponent(
    idHlBloc: $_POST['ID_HL_BLOC'] // нужно чтобы сохранять параметры при работе через ajax 
);

$data = $geoIPComponent->getDataIp($_POST['IP']);
if (is_array($data)) {
    $result["data"] = $data;
} else {
    $result["data"] = $data;
    $result['error'] = true;
}
echo json_encode($result);
die();

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php';

<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->addExternalJS('/local/components/myComp/geoIP/templates/.default/script.js');
?>
<div class="container">
    <div  id="error" class="alert alert-danger d-none" role="alert" ></div>
    <form id="formSend" class="row  text-center">
        <div class="col-10">
            <input type="hidden" id="id_hl_bloc" value="<?= $arParams["HL_BLOCK_TYPE"] ?>"> <!-- нужно чтобы сохранять параметры при работе через ajax -->
            <input id="ip" type="text" class="form-control" placeholder="Ваш IP">
        </div>
        <div class="col-2">
            <input class="btn btn-primary" type="submit">
        </div>
    </form>

    <div id="tabl" class="row d-none">
        <div class="col-2">Страна:</div>
        <div id="contry" class="col-10"></div>
        <div class="col-2 ">Город:</div>
        <div id="suty" class="col-10"></div>
        <div class="col-2 ">Долгота:</div>
        <div id="cord_x" class="col-10"></div>
        <div class="col-2 ">Широта:</div>
        <div id="cord_y"class="col-10"></div>
    </div>

</div>
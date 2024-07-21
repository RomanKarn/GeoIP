<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}

use Bitrix\Main\Loader;
use Bitrix\Highloadblock\HighloadBlockTable;

if (!Loader::includeModule('highloadblock')) {
	throw new \Exception('Не загружены модули необходимые для работы компонента');
}
$arHlBlocList =[];
$arHlBloc = HighloadBlockTable::getList();

while($hlBloc = $arHlBloc->fetch())
{
	$arHlBlocList[$hlBloc['ID']] = '['.$hlBloc['ID'].'] '.$hlBloc['NAME'];;
}

$arComponentParameters = [
	"GROUPS" => [],
	"PARAMETERS" => [
		"HL_BLOCK_TYPE" => [
			"PARENT" => "BASE",
			"NAME" => "HL для поиска по ip",
			"TYPE" => "LIST",
			"VALUES" => $arHlBlocList,
			"REFRESH" => "Y",
		],
	],

];

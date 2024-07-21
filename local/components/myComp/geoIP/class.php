<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;
use Bitrix\Highloadblock\HighloadBlockTable;

use function PHPSTORM_META\type;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

class GeoIPComponent extends CBitrixComponent
{

    protected const URL_SITE = 'http://api.sypexgeo.net'; //сайт для запросов локации
    protected const TYPE_BEAK = 'json'; //тип возвращаемого файла

    public function __construct($component = null,$idHlBloc = null) // нужно чтобы сохранять параметры при работе через ajax 
    {
        parent::__construct($component);

        if(empty($this->arParams['HL_BLOCK_TYPE']))
        {
            $this->arParams['HL_BLOCK_TYPE'] = $idHlBloc;
        }
    }

    public function executeComponent()
    {
        if (!Loader::includeModule('highloadblock')) {
            throw new \Exception('Не загружены модули необходимые для работы модуля');
        }
        //print_r($this->getDataIp("5.165.212.7"));
        $this->includeComponentTemplate();
    }

    public function getDataIp($ip) //главная функция для ajax запросов и получение данных
    {
        try {
            $data = $this->getLocashionByHlBloc($ip);
            if (!$data) {
                $data = $this->getLocashionByIpServes($ip);
            }
            return $data;
        } catch (InvalidArgumentException $e) {
            return $e->getMessage();
        } catch (Exception $e) {
            CEventLog::Add(array(
                "SEVERITY"         => "ERROR",
                "AUDIT_TYPE_ID" => "GEO_IP_",
                "MODULE_ID" => "highloadblock",
                "DESCRIPTION" => $e->getMessage(),
            ));
            return $e->getMessage();
        }
    }

    private function getLocashionByHlBloc($ip): false|array //функция для запроса к HLBloc для получения локации 
    {
        $this->checValidashionIp($ip);

        try {
            $entity_data_class = $this->initHlBloc();
        } catch (Exception $e) {
            CEventLog::Add(array(
                "SEVERITY"         => "ERROR",
                "AUDIT_TYPE_ID" => "GEO_IP_",
                "MODULE_ID" => "highloadblock",
                "DESCRIPTION" => $e->getMessage(),
            ));
        }
        $rsData = $entity_data_class::getList(array(
            'filter' => array('UF_IP' => $ip)
        ));
        $arDataList = [];
        while ($arData = $rsData->Fetch()) {
            $arDataList[] = $arData;
        }

        if (empty($arDataList)) {
            return false;
        }

        $dataSerialaze = [
            "UF_IP" => $arDataList[0]["UF_IP"],
            "UF_SITY" => $arDataList[0]["UF_SITY"],
            "UF_CONTRI" => $arDataList[0]["UF_CONTRI"],
            "UF_POSITION_X" => $arDataList[0]["UF_POSITION_X"],
            "UF_POSITION_Y" => $arDataList[0]["UF_POSITION_Y"],
        ];
        return $dataSerialaze;
    }

    private function getLocashionByIpServes($ip): array //функция для запроса к сервису для получения локации 
    {
        $this->checValidashionIp($ip);

        $uri = new \Bitrix\Main\Web\Uri(static::URL_SITE);
        $uri->setPath("/" . static::TYPE_BEAK . "/" . $ip . "/");

        $httpClient = new \Bitrix\Main\Web\HttpClient();
        $data = $httpClient->get($uri->getUri());
        $data = (json_decode($data, true));
        if (!is_array($data)) {
            throw new \Exception('Ошибка запроса');
        }

        $dataSerialaze = [
            "UF_IP" => $data["ip"],
            "UF_SITY" => $data["city"]["name_ru"],
            "UF_CONTRI" => $data["country"]["name_ru"],
            "UF_POSITION_X" => $data["city"]["lat"],
            "UF_POSITION_Y" => $data["city"]["lon"],
        ];
        try {
            $this->setLocashionInHlBloc($dataSerialaze);
        } catch (Exception $e) {
            CEventLog::Add(array(
                "SEVERITY"         => "ERROR",
                "AUDIT_TYPE_ID" => "GEO_IP_",
                "MODULE_ID" => "highloadblock",
                "DESCRIPTION" => $e->getMessage(),
            ));
        }
        return $dataSerialaze;
    }

    private function setLocashionInHlBloc($data) // добавление ip в HlBloc
    {
        try {
            $entity_data_class = $this->initHlBloc();
        } catch (Exception $e) {
            CEventLog::Add(array(
                "SEVERITY"         => "ERROR",
                "AUDIT_TYPE_ID" => "GEO_IP_",
                "MODULE_ID" => "highloadblock",
                "DESCRIPTION" => $e->getMessage(),
            ));
        }

        $arElenent = [
            "UF_IP" => $data["UF_IP"],
            "UF_SITY" => $data["UF_SITY"],
            "UF_CONTRI" => $data["UF_CONTRI"],
            "UF_POSITION_X" => $data["UF_POSITION_X"],
            "UF_POSITION_Y" => $data["UF_POSITION_Y"],
        ];
        $obResult = $entity_data_class::add($arElenent);

        if (!$obResult->isSuccess()) {
            throw new \Exception('Не получилось добавить данные в БД');
        }
    }

    private function initHlBloc() //загрузка HlBloc 
    {
        if (!Loader::includeModule('highloadblock')) {
            throw new \Exception('Не загружены модули необходимые для работы модуля');
        }
        $hlbl = (int)$this->arParams['HL_BLOCK_TYPE'];
        $hlblock = HighloadBlockTable::getById($hlbl)->fetch();
        $entity = HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        if (empty($entity_data_class)) {
            throw new \Exception('Не получилось загрузить HlBloc');
        }
        return  $entity_data_class;
    }

    private function checValidashionIp($ip) //валидация IP
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new \InvalidArgumentException('Ошибка валидации');
        }
    }
}

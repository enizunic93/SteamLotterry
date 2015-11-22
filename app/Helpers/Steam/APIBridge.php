<?php
namespace App\Helpers\Steam;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Mockery\CountValidator\Exception;
use SebastianBergmann\GlobalState\RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class APIBridge
{
    protected $apiURL = 'http://api.steampowered.com/';
    protected $communityURL = 'http://steamcommunity.com/';

    /**
     * Ключ для операций с api.
     * @var string
     */
    protected $apiKey;

    /**
     * @var ItemPrice
     */
    protected $priceHelper;

    /**
     * @param $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;

        $this->priceHelper = new ItemPrice($apiKey);
    }

    public function callApi($category = '', $method, array $params)
    {
        $params['key'] = $this->apiKey;

        $query = http_build_query($params, null, '&');

        $contents = @file_get_contents($this->apiURL . $category . $method . '?' . $query);

        if (!$contents) {
            $error = error_get_last();
            $error = explode(': ', $error['message']);
            $error = trim($error[2]) . PHP_EOL;
            throw new \Exception('Can\'t go with applied url. The error was: ' . $error);
        }

        return $contents;
    }

    public function getRawPlayerItems($appId, $steamID)
    {
        try {
            $json = $this->callApi('IEconItems_' . $appId . '/', 'GetPlayerItems/v0001/', [
                'steamid' => $steamID,
                'language' => 'russian'
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return [];
        }

        return json_decode($json, true)['result']['items'];
    }

    /**
     * @param $appId
     * @param $steamID
     * @return array
     */
    public function getRawPlayerInventory($appId, $steamID)
    {
        $appContext = ItemStorage::getItemContextID($appId);

        $query = http_build_query([
            'l' => 'russian'
        ]);

        $url = $this->communityURL . 'profiles/' . $steamID . '/inventory/json/' . $appId . '/' . $appContext . '?' . $query;

        $json = file_get_contents($url);
        $result = json_decode($json, true);

        if (!$result['success']) {
            return [];
        }

        return $result;
    }

    /**
     * @param $steamId
     * @param $appId
     * @param $classId
     * @return Item|null
     */
    public function findItemInInventory($steamId, $appId, $classId)
    {
        $inventory = $this->getRawPlayerInventory($appId, $steamId);

        foreach ($inventory['rgDescriptions'] as $item) {
            if ($item['classid'] == $classId)
                return $this->parseItem($item);
        }

        return null;
    }

    public function getTotalPriceOfInventory($inventory)
    {
        $total = 0;

        /** @var Item $item */
        foreach ($inventory as $item) {
            if ($item->isTradable())
                $total += $item->getLotPrice();
        }

        return $total;
    }

    protected function isItemOkWhere(Item $item, array $where)
    {
        foreach ($where as $filter) {
            $response = $item->$filter['key']();

            switch ($filter['operand']) {
                default:
                case '==':
                case '=':
                    if ($response != $filter['value']) {
                        return false;
                    }
                    break;
                case '>':
                    if ($response <= $filter['value']) {
                        return false;
                    }
                    break;
                case '>=':
                    if ($response < $filter['value']) {
                        return false;
                    }
                    break;
                case '<':
                    if ($response >= $filter['value']) {
                        return false;
                    }
                    break;
                case '<=':
                    if ($response > $filter['value']) {
                        return false;
                    }
                    break;
                case 'abs=':
                    if (abs(floatval($response)) != abs(floatval($filter['value']))) {
                        return false;
                    }

                    break;
            }
        }

        // Нет предъяв
        return true;
    }

    public function parsePlayerInventory($inventory, $filters)
    {
        $result = [];

        if (!isset($inventory['rgInventory'])) {
            return $result;
        }

        foreach ($inventory['rgDescriptions'] as $info) {
            $appId = $info['appid'];

            try {
                $item = $this->parseItem($info);

                if (isset($filters['where'])) {
                    if (!$this->isItemOkWhere($item, $filters['where'])) {
                        continue;
                    }
                }

                $result[] = $item;
            } catch (\Exception $e) {
                Log::emergency($e->getMessage());
            }
        }

        $this->priceHelper->saveCache();

        return $result;
    }

    public function getPlayerInventory($appId, $steamID, array $filters = [])
    {
        return $this->parsePlayerInventory($this->getRawPlayerInventory($appId, $steamID), $filters);
    }

    /**
     * @param $info array
     * @return Item
     */
    private function parseItem($info)
    {
        $item = ItemStorage::getItemClass($info['appid']); // Имя класса предмета для игрового айди $appId
        /**
         * @var $item Item
         */
        $item = new $item($info); // Уже объект

        try {
            $prices = $this->priceHelper->getItemPrices($item->getAppId(), 5, $item->getMarketName());

            $item->setMinPrice($prices['lowest_price']);
            $item->setMedianPrice($prices['median_price']);
            $item->setMarketVolume($prices['volume']);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
        }

        return $item;
    }
}
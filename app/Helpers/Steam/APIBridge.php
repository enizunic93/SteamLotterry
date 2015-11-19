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

    protected $apiKey;

    /**
     * @param $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
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

    public function getRawPlayerInventory($appId, $appContext, $steamID)
    {
        $query = http_build_query([
            'l' => 'russian'
        ]);
        $json = file_get_contents($this->communityURL . 'profiles/' . $steamID . '/inventory/json/' . $appId . '/' . $appContext . '?' . $query);

        $result = json_decode($json, true);

        if (!$result['success']) {
            return [];
        }

        return $result;
    }

    public function getItemPrices($appId, $currency, $name)
    {
        $params = [
            'currency' => $currency,
            'appid' => $appId,
            'market_hash_name' => $name
        ];

        $query = http_build_query($params);
        $url = $this->communityURL . 'market/priceoverview/?' . $query;
        $contents = @file_get_contents($url);

        $result = json_decode($contents, true);

        if (!$result['success']) {
            throw new NotFoundHttpException('No item found for URL ' . $url);
        }

        unset($result['success']);

        $lowest = (isset($result['lowest_price'])) ? str_replace(',', '.', $result['lowest_price']) : 0;
        $median = (isset($result['median_price'])) ? str_replace(',', '.', $result['median_price']) : 0;
        $volume = (isset($result['volume'])) ? str_replace(',', '', preg_replace('/[\s]*/', '', $result['volume'])) : 0;

        $lowest = (!$lowest) ? 0 : $lowest;
        $median = (!$lowest) ? 0 : $median;
        $volume = (!$lowest) ? 0 : $volume;

        $result['lowest_price'] = $lowest;
        $result['median_price'] = $median;
        $result['volume'] = $volume;

        return $result;
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
        $additional = [];

        if (!isset($inventory['rgInventory'])) {
            return $result;
        }

        foreach ($inventory['rgInventory'] as $inventoryInfo) {
            $classid = $inventoryInfo['classid'];

            unset($inventoryInfo['id']);
            unset($inventoryInfo['instanceid']);
            unset($inventoryInfo['classid']);

            $additional[$classid] = $inventoryInfo;
        }

        foreach ($inventory['rgDescriptions'] as $info) {
            $appId = $info['appid'];

            try {
                $item = ItemStorage::getItemClass($appId); // Имя класса предмета для игрового айди $appId
                /**
                 * @var $item Item
                 */
                $item = new $item($info, $additional[$info['classid']]); // Уже объект

                if (isset($filters['where'])) {
                    if (!$this->isItemOkWhere($item, $filters['where'])) {
                        continue;
                    }
                }

                try {
                    $prices = $this->getItemPrices($item->getAppId(), 5, $item->getMarketName());

                    $item->setMinPrice($prices['lowest_price']);
                    $item->setMedianPrice($prices['median_price']);
                    $item->setMarketVolume($prices['volume']);
                } catch (NotFoundHttpException $ex) {
                    if($item->isTradable())
                        Log::alert('Невозможно получить цены для предмета ' . $item->getMarketName());
                }

                $result[] = $item;
            } catch (\InvalidArgumentException $e) {
                Log::emergency($e->getMessage(), ['appid' => $appId, 'itemName' => $info['name']]);
            }
        }

        return $result;
    }

    public function getPlayerInventory($appId, $appContext, $steamID, array $filters = [])
    {
        return $this->parsePlayerInventory($this->getRawPlayerInventory($appId, $appContext, $steamID), $filters);
    }
}
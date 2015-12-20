<?php
namespace App\Helpers\Steam;

use App\Helpers\Steam\Collection\Customs\ItemCollection;
use App\Helpers\Steam\Items\ItemStorage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use \Debugbar;

/**
 * Class, responsible for Steam-app bridge.
 * @see http://steamcommunity.com/dev/
 */
class APIBridge
{
    protected static $inventories;

    protected $apiURL = 'http://api.steampowered.com/';
    protected $communityURL = 'http://steamcommunity.com/';

    /**
     * Ключ для операций с api.
     * @var string
     */
    protected $apiKey;

    /**
     * @var ItemMarketPriceHelper
     */
    protected $priceHelper;

    /**
     * @param $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->priceHelper = new ItemMarketPriceHelper($apiKey);
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

    private function getPage($url)
    {
        $options = array(
            CURLOPT_RETURNTRANSFER => true,   // return web page
            CURLOPT_HEADER => false,  // don't return headers
            CURLOPT_FOLLOWLOCATION => true,   // follow redirects
            CURLOPT_MAXREDIRS => 10,     // stop after 10 redirects
            CURLOPT_ENCODING => "",     // handle compressed
            CURLOPT_USERAGENT => "Google Chrome like Gecko", // name of client
            CURLOPT_AUTOREFERER => false,   // set referrer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,    // time-out on connect
            CURLOPT_TIMEOUT => 120,    // time-out on response
        );

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);

        $content = curl_exec($ch);

        curl_close($ch);

        return $content;
    }

    /**
     * @param $appId
     * @param $steamID
     * @return array
     */
    public function getRawPlayerInventory($appId, $steamID)
    {
        if (isset(self::$inventories[$steamID]) && !empty(self::$inventories[$steamID]))
            return self::$inventories[$steamID];

        Debugbar::startMeasure('getRawInventory', 'Получаем инвентарь');
        $appContext = ItemStorage::getItemContextID($appId);

        $url = $this->communityURL . 'profiles/' . $steamID . '/inventory/json/' . $appId . '/' . $appContext . '?l=russian';
        $json = $this->getPage($url);

        Debugbar::stopMeasure('getRawInventory');

        Debugbar::startMeasure('parseRawInventory', 'Декодируем инвентарь');
        $result = json_decode($json, true);

        if (!$result['success']) {
            Debugbar::stopMeasure('parseRawInventory');
            Debugbar::warning('Ошибка парсинга инвентаря!');
            Debugbar::info($result);

            return [];
        }

        self::$inventories[$steamID] = $result;

        Debugbar::stopMeasure('parseRawInventory');

        return $result;
    }

    /**
     * @param $appId
     * @param $steamID
     * @return ItemCollection
     */
    public function queryPlayerInventory($appId, $steamID)
    {
        $inventory = $this->getRawPlayerInventory($appId, $steamID);

        if (!isset($inventory['rgDescriptions'])) {
            throw new \RuntimeException('Can\'t get inventory descriptions!');
        }

        $result = new ItemCollection($inventory['rgDescriptions'], new ItemMarketPriceHelper($this->apiKey));

        return $result;
    }

    public function parseSteamID($id)
    {
        if (empty($id))
            throw new \InvalidArgumentException('Bad steam id');

        if (is_array($id))
            $ids = $id;
        else
            $ids = [$id];

        $json = json_decode($this->callApi('ISteamUser/', 'GetPlayerSummaries/v0002/', [
            'key' => $this->apiKey,
            'steamids' => implode(',', $ids)
        ]), true);

        if (is_array($id)) {
            return $json['response']['players'];
        } else {
            foreach ($json['response']['players'] as $player) {
                if ($player['steamid'] == $id)
                    return $player;
            }

            throw new NotFoundHttpException("Can't find user information for id " . $id);
        }
    }
}
<?php
namespace App\Helpers\Steam;

use App\Helpers\Steam\Items\Item;
use App\Models\ItemPrice;
use Cache;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ItemMarketPriceHelper
{
    protected $communityURL = 'http://anonymouse.org/cgi-bin/anon-www.cgi/http://steamcommunity.com/';
//    protected $communityURL = 'http://steamcommunity.com/';
    protected $apiKey;

    /**
     * Идентификтор валюты, в которых будет возвращаться и сохраняться
     * @var int
     */
    protected $currency;

    /**
     * @param $apiKey
     * @param int $currency
     */
    public function __construct($apiKey, $currency = 5)
    {
        $this->apiKey = $apiKey;
        $this->currency = $currency;
    }

    protected function cleanFloat($str)
    {
        $float = (float)str_replace(',', '.', preg_replace('/[\s]*/', '', $str));
        return $float;
    }

    protected function cleanInt($str)
    {
        return (int)str_replace(',', '.', preg_replace('/[\s]*/', '', $str));
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @param $item Item
     * @return ItemPrice
     */
    public function getItemPrice($item)
    {
        $params = [
            'currency' => $this->currency,
            'appid' => $item->getAppId(),
            'market_hash_name' => $item->getMarketName()
        ];

        $query = http_build_query($params);

        $price = ItemPrice::
        where('class_id', $item->getClassId())
            ->where('app_id', $item->getAppId())
            ->first();

        // Пытаемся достать из файлового кеша, если нифига - берем из маркета
        if (!$price) {
            $url = $this->communityURL . 'market/priceoverview/?' . $query;

            $contents = @file_get_contents($url);
            $result = json_decode($contents, true);

            if (!$result['success']) {
                throw new NotFoundHttpException('Стим считает нас дудосерами по ссылке ' . $url);
            }

            $lowest = (isset($result['lowest_price'])) ? $this->cleanFloat($result['lowest_price']) : 0;
            $median = (isset($result['median_price'])) ? $this->cleanFloat($result['median_price']) : 0;
            $volume = (isset($result['volume'])) ? $this->cleanInt($result['volume']) : 0;

            $cached = [
                'class_id' => $item->getClassId(),
                'app_id' => $item->getAppId(),
                'min' => $lowest,
                'median' => $median,
                'volume' => $volume,
            ];

            $price = ItemPrice::create($cached);
        }

        return $price;
    }
}
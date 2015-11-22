<?php
namespace App\Helpers\Steam;

use Carbon\Carbon;
use Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ItemPrice
{
    protected $communityURL = 'http://anonymouse.org/cgi-bin/anon-www.cgi/http://steamcommunity.com/';

    protected $apiKey;

    protected $memcache = [];

    protected $callsInInstance = 0;

    /**
     * @param $apiKey
     */
    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function saveCache()
    {
        foreach ($this->memcache as $key => $price) {
            $expiresAt = Carbon::now()->addHour();
            Cache::put('price_' . $key, $price, $expiresAt);
        }
    }

    protected function cleanFloat($str, $places=2) {
        $float = (float) str_replace(',', '', preg_replace('/[\s]*/', '', $str));
        $mult = pow(10, $places);
        return ceil($float * $mult) / $mult;
    }

    protected function cleanInt($str) {
        return (int) str_replace(',', '', preg_replace('/[\s]*/', '', $str));
    }

    public function getItemPrices($appId, $currency, $name)
    {
        $params = [
            'currency' => $currency,
            'appid' => $appId,
            'market_hash_name' => $name
        ];
        $query = http_build_query($params);
        $key = md5($query);

        // Если в массиве кеша нет нихера
        if (!isset($this->memcache[$key]) || empty($this->memcache[$key])) {
            $cached = Cache::get($key);

            // Пытаемся достать из файлового кеша, если нифига - берем из маркета
            if ($cached == null || !$cached) {
                $url = $this->communityURL . 'market/priceoverview/?' . $query;

                $contents = @file_get_contents($url);
                $result = json_decode($contents, true);

                if (!$result['success']) {
                    throw new NotFoundHttpException('No price found for URL ' . $url);
                }

                $lowest = (isset($result['lowest_price'])) ? $this->cleanFloat($result['lowest_price']) : 0;
                $median = (isset($result['median_price'])) ? $this->cleanFloat($result['median_price']) : 0;
                $volume = (isset($result['volume'])) ? $this->cleanInt($result['volume']) : 0;

                ++$this->callsInInstance;
                $cached = [
                    'lowest_price' => $lowest,
                    'median_price' => $median,
                    'volume' => $volume,
                ];//452 2052 64
            }

            $this->setMemcache($key, $cached);
        }

        return $this->memcache[$key];
    }

    private function setMemcache($key, $result)
    {
        $this->memcache[$key] = $result;

        // Каждые 5 сохраняем

        if ($this->callsInInstance >= 5) {
            $this->saveCache();
            $this->callsInInstance = 0;
        }
    }
}
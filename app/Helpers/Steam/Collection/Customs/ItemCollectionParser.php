<?php
namespace App\Helpers\Steam\Collection\Customs;

use App\Helpers\Steam\Collection\BaseCollection;
use App\Helpers\Steam\Collection\Filters\BaseCollectionFilter;
use App\Helpers\Steam\Collection\ICollectionParser;
use App\Helpers\Steam\ItemMarketPriceHelper;
use App\Helpers\Steam\Items\Item;
use App\Helpers\Steam\Items\ItemStorage;
use App\Models\ItemPrice;

class ItemCollectionParser implements ICollectionParser
{
    private static $collectionCache;
    private static $itemCache;

    /**
     * @var ItemMarketPriceHelper
     */
    protected $priceHelper;

    public function __construct(ItemMarketPriceHelper $marketPriceHelper)
    {
        $this->priceHelper = $marketPriceHelper;
    }

    public function parseCollection(BaseCollection &$collection)
    {
        $key = $collection->getUniqueKey();

        if (isset(self::$collectionCache[$key]) && !empty(self::$collectionCache[$key]))
            return self::$collectionCache[$key];

        if ($collection->isEmpty())
            return [];

        \Debugbar::startMeasure('applyFilters', 'Применяем фильтры (' . count($collection->getFilters()) . ')');

        /** @var BaseCollectionFilter $filter */
        foreach ($collection->getFilters() as $filter) {
            $filter->execute($collection);
        }

        \Debugbar::stopMeasure('applyFilters');

        $result = [];

        \Debugbar::startMeasure('parseItems', 'Парсим предметы (' . $collection->size() . ')');

        foreach ($collection->toArray() as $itemInfo) {
            $item = $this->parseItem($itemInfo);
            $result[] = $item;
        }

        \Debugbar::stopMeasure('parseItems');

        self::$collectionCache[$key] = $result;

        return $result;
    }

    /**
     * @param $itemInfo
     * @return Item
     */
    public function parseItem($itemInfo)
    {
        if (empty($itemInfo) || !is_array($itemInfo))
            throw new \InvalidArgumentException('ItemInfo should be filled array!');

        $key = md5(json_encode($itemInfo));

        if (isset(self::$itemCache[$key]) && !empty(self::$itemCache[$key]))
            return self::$itemCache[$key];

        \Debugbar::startMeasure('parseItem', 'Парсим предмет ' . $itemInfo['name']);

        /** @var Item $item */
        $item = ItemStorage::createItem($itemInfo);

        if ($itemInfo['tradable'] !== '0') {
            try {
                /** @var $price ItemPrice */
                $price = $this->priceHelper->getItemPrice($item);

                $item->setPrice($price);
            } catch (\Exception $e) {
                \Log::debug($e->getMessage());
            }
        }

        \Debugbar::stopMeasure('parseItem');

        return $item;
    }
}
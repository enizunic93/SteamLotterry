<?php
namespace App\Helpers\Steam\Collection\Customs;

use App\Helpers\Steam\Collection\BaseCollection;
use App\Helpers\Steam\Collection\CollectionMethods;
use App\Helpers\Steam\ItemMarketPriceHelper;

class ItemCollection extends BaseCollection
{
    use CollectionMethods;

    static $searches = [];

    public function __construct(array $items, ItemMarketPriceHelper $marketPriceHelper) {
        parent::__construct($items);

        $this->collectionParser = new ItemCollectionParser($marketPriceHelper);
    }

    public function __call($name, $arguments)
    {
        if (strpos($name, 'findBy') !== false) {
            $string = strtolower(str_replace('findBy', '', $name));

            if (!empty(self::$searches[$string]))
                return self::$searches[$string];

            foreach ($this->items as $item) {
                if ($item[$string] == $arguments[0]) {
                    $parsed =  $this->collectionParser->parseItem($item);

                    self::$searches[$string] = $parsed;

                    return $parsed;
                }
            }
        }
    }
}
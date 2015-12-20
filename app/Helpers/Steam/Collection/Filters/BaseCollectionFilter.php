<?php
namespace App\Helpers\Steam\Collection\Filters;

use App\Helpers\Steam\Collection\BaseCollection;

interface BaseCollectionFilter {
    /**
     * При вызове должен совершить операции с коллекцией.
     * @param BaseCollection $collection
     * @return array
     */
    public function execute(BaseCollection &$collection);
}
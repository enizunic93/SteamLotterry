<?php
namespace App\Helpers\Steam\Collection;

interface ICollectionParser
{
    public function parseCollection(BaseCollection &$collection);

    public function parseItem($item);
}
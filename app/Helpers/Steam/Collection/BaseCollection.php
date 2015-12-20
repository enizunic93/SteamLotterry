<?php
namespace App\Helpers\Steam\Collection;

use App\Helpers\Steam\Collection\Filters\SliceCollectionFilter;

abstract class BaseCollection
{
    /**
     * Массив rgDescriptions
     * @var array
     */
    protected $items = [];

    /**
     * Все элементы реализуют ItemCollectionFilter!
     * @see ItemCollectionFilter
     * @var array
     */
    protected $filters = [];

    /**
     * @var ICollectionParser
     */
    protected $collectionParser;

    /**
     * После получения JSON-массива инвентаря стим получает значение $json['rgDescriptions']
     * @param array $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function getUniqueKey()
    {
        return md5(json_encode($this->items) . json_encode($this->filters));
    }

    /**
     * TODO: убрать фейк, сделать нормально
     * Возвращает массив типа, наследующего {@link Item}
     * @link Item
     * @see Item
     * @return array
     */
    public function get()
    {
        return $this->collectionParser->parseCollection($this);
    }

    /**
     * Returns first row
     * @return mixed
     */
    public function first()
    {
        $this->filters[] = new SliceCollectionFilter(0, 1);

        return $this->get()[0];
    }

    public function add($value)
    {
        $this->items[] = $value;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function __call($name, $arguments)
    {
        if (strpos($name, 'findBy') !== false) {
            $string = strtolower(str_replace('findBy', '', $name));

            foreach ($this->items as $item) {
                if ($item[$string] == $arguments[0]) {
                    return $this->collectionParser->parseItem($item);
                }
            }
        }
    }

    public function toArray()
    {
        return array_values($this->items);
    }

    public function toJSON()
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }

    public function isEmpty()
    {
        return !$this->size();
    }

    public function size()
    {
        return count($this->items);
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setItems(array $items)
    {
        $this->items = $items;
    }

    public function removeAt($i)
    {
        $index = 0;

        foreach ($this->items as $k => $v) {
            // Numeric or associative
            if ($index == $i || $k == $i) {
                unset($this->items[$k]);

                break;
            }

            ++$index;
        }
    }

    public function removeAtMany(array $ids)
    {
        $index = 0;

        foreach ($this->items as $k => $v) {
            // Numeric or associative
            if (in_array($index, $ids) || in_array($k, $ids)) {
                unset($this->items[$k]);
            }

            ++$index;
        }
    }
}
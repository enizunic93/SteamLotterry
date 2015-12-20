<?php
namespace App\Helpers\Steam\Collection\Filters;

use App\Helpers\Steam\Collection\BaseCollection;

class SliceCollectionFilter implements BaseCollectionFilter
{
    /**
     * @var int
     */
    private $from;

    /**
     * @var int
     */
    private $to;

    /**
     * SliceCollectionFilter constructor.
     * @param int $from
     * @param int $to
     */
    public function __construct($from, $to)
    {
        $this->from = max(0, $from);
        $this->to = (int)$to;
    }

    public function execute(BaseCollection &$collection)
    {
        $items = $collection->toArray();
        $bad = [];

        foreach ($items as $i => $v) {
            if ($i < $this->from || $i >= $this->to) {
                $bad[] = $i;
            }
        }

        $collection->removeAtMany($bad);
    }

    public function __toString()
    {
        return 'Slice';
    }
}
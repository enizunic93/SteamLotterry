<?php
namespace App\Helpers\Steam\Collection\Filters;

use App\Helpers\Steam\Collection\BaseCollection;

class WhereCollectionFilter implements BaseCollectionFilter
{
    /**
     * @var \Closure
     */
    private $closure;

    /**
     * WhereCollectionFilter constructor.
     * @param \Closure $closure
     */
    public function __construct(\Closure &$closure)
    {
        $this->closure = &$closure;
    }

    public function execute(BaseCollection &$collection)
    {
        $bad = [];

        /* @var $closure \Closure */
        $closure = $this->closure;
        $items = $collection->toArray();

        foreach ($items as $i => $v) {
            if (!$closure($v)) {
                $bad[] = $i;
            }
        }

        $collection->removeAtMany($bad);
    }

    public function __toString()
    {
        return 'Where';
    }
}
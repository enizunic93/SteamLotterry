<?php
namespace App\Helpers\Steam\Collection;

use App\Helpers\Steam\Collection\Filters\SliceCollectionFilter;
use App\Helpers\Steam\Collection\Filters\WhereCollectionFilter;

trait CollectionMethods {
    public function slice($a, $b)
    {
        $this->filters[] = new SliceCollectionFilter($a, $b);

        return $this;
    }

    /**
     * @param \Closure $closure
     * @return $this
     */
    public function where(\Closure $closure)
    {
        $this->filters[] = new WhereCollectionFilter($closure);

        return $this;
    }
}
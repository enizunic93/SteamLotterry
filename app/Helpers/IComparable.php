<?php
namespace App\Helpers;

interface IComparable
{
    /**
     * @param IComparable $other
     * @param String $comparison any of ==, <, >, =<, >=, etc
     * @return Bool true | false depending on result of comparison
     */
    public function compareTo(IComparable $other, $comparison);
}
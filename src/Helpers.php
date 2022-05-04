<?php
use Illuminate\Support\Collection;

if (! function_exists('is_numeric_list')) {
    /**
     * check as a plain array，e.g. [0 => value, 1 => value]
     *
     * @param $arr
     * @return int
     */
    function is_numeric_list($arr): bool|int
    {
        $arrayNumeric = fn($arr) => range(0, count($arr) - 1);
        if (is_array($arr) && (array_keys($arr) === $arrayNumeric($arr))) return true;
        if ($arr instanceof Collection && collect($arr)->keys()->toArray() === $arrayNumeric($arr)) return true;

        return false;
    }
}
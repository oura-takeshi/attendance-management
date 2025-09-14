<?php

namespace App\Traits;

use Carbon\Carbon;

trait CombinesDateTime
{
    private function combineDateTime($date, $time)
    {
        if (empty($time)) {
            return null;
        }

        return Carbon::parse("{$date->format('Y-m-d')} {$time}");
    }
}
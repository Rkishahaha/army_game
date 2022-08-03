<?php
if (!function_exists('random_chance')) {
    function random_chance($percent)
    {
        return mt_rand(0, 99) <= $percent;
    }
}


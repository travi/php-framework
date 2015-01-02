<?php

namespace travi\framework\utilities;


class Date
{
    public function getDate($format)
    {
        return date($format);
    }
}
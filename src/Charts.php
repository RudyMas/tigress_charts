<?php

namespace Tigress;

class Charts
{
    public static function bar(): BarChart
    {
        return new BarChart();
    }

    public static function line(): LineChart
    {
        return new LineChart();
    }

    public static function pie(): PieChart
    {
        return new PieChart();
    }
}

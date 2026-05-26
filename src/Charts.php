<?php

namespace Tigress;

/**
 * Class Charts (PHP version 8.5)
 *
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2025, rudymas.be. (http://www.rudymas.be/)
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version 2026.05.26.0
 * @package Tigress\Charts
 */
class Charts
{
    /**
     * Get the version of the Repository
     *
     * @return string
     */
    public static function version(): string
    {
        return '2026.05.26';
    }

    public static function bar(): BarChart
    {
        return new BarChart();
    }

    public static function barGroup(): BarGroupChart
    {
        return new BarGroupChart();
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

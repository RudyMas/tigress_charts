<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Tigress\Charts;
use Tigress\BarGroupChart;

$data = [
    ['label' => 'Score 1', 'values' => [5, 6, 7, 9]],
    ['label' => 'Score 2', 'values' => [3, 2, 9]],
    ['label' => 'Score 3', 'values' => [4, 7]],
    ['label' => 'Score 4', 'values' => [8, 5, 6]],
    ['label' => 'Score 5', 'values' => [2, 3, 4, 5]],
    ['label' => 'Score 6', 'values' => [1, 2, 3]],
    ['label' => 'Score 7', 'values' => [4, 5]],
    ['label' => 'Score 8', 'values' => [6, 7, 8]],
];

$chart = new BarGroupChart();
$chart
    ->setTitle('Grouped Bar Chart - Questionnaire')
    ->setData($data)
    ->setSize(800, 400)
    ->showValues()
    ->showLegend()
    ->showXAxis()
    ->showYAxis()
    ->setYAxisTicks(5)
    ->setXAxisTickSpacing(1)
    ->render(__DIR__ . '/bar_group_chart.png');

echo "Grouped bar chart generated: bar_group_chart.png\n";

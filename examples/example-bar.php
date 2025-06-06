<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Tigress\Charts;

$data = [
    ['label' => 'January', 'value' => [10, 12]],
    ['label' => 'February', 'value' => 90, 'color' => [255, 99, 132]],
    ['label' => 'March', 'value' => 150],
    ['label' => 'April', 'value' => 60, 'color' => [54, 162, 235]],
];

Charts::bar()
    ->setTitle('Monthly Revenue')
    ->setData($data)
    ->setSize(800, 400)
    ->showValues()
    ->showLegend()
    ->showXAxis()
    ->showYAxis()
    ->setYAxisTicks(150)
    ->setYAxisTickSpacing(25)
    ->setXAxisTickSpacing(1)
    ->render(__DIR__ . '/bar_chart.png');

echo "Bar chart generated: bar_chart.png\n";

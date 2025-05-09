<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Tigress\Charts;

$data = [
    ['label' => 'Week 1', 'value' => 50],
    ['label' => 'Week 2', 'value' => 90],
    ['label' => 'Week 3', 'value' => 60],
    ['label' => 'Week 4', 'value' => 80],
];

Charts::line()
    ->setTitle('Weekly Growth')
    ->setData($data)
    ->setSize(800, 400)
    ->showValues()
    ->showLegend()
    ->showXAxis()
    ->showYAxis()
    ->setYAxisTicks(4)
    ->setXAxisTickSpacing(1)
    ->render(__DIR__ . '/line_chart.png');

echo "Line chart generated: line_chart.png\n";

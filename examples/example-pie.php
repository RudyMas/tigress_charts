<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Tigress\Charts;

$data = [
    ['label' => 'Apples', 'value' => 40, 'color' => [255, 99, 132]],
    ['label' => 'Bananas', 'value' => 30, 'color' => [255, 206, 86]],
    ['label' => 'Cherries', 'value' => 20],
    ['label' => 'Dates', 'value' => 10],
];

Charts::pie()
    ->setTitle('Fruit Distribution')
    ->setData($data)
    ->setSize(600, 600)
    ->showValues()
    ->showLegend()
    ->render(__DIR__ . '/pie_chart.png');

echo "Pie chart generated: pie_chart.png\n";

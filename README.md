# Tigress Charts

A lightweight PHP library to generate simple GD-based chart images (starting with bar charts). Built to be integrated into the Tigress Framework or used standalone.

## Features
- 🟦 Bar charts with automatic or custom coloring
- 📐 Adjustable width & height
- 🖼️ Output as PNG image

## Installation
```bash
composer require tigress/charts
```

## Usage
```php
use Tigress\Charts;

$data = [
    ['label' => 'Jan', 'value' => 120],
    ['label' => 'Feb', 'value' => 80, 'color' => [255, 0, 0]],
    ['label' => 'Mar', 'value' => 150],
];

Charts::bar()
    ->setTitle('Monthly Sales')
    ->setData($data)
    ->setSize(800, 400)
    ->render(__DIR__ . '/chart.png');
```

## Roadmap
- [x] Bar chart
- [ ] Line chart
- [ ] Pie chart

## License
GPL-3.0-or-later
# Tigress Charts — Programmer's Manual

**Version:** 2025.12.09  
**Author:** Rudy Mas  
**License:** GPL-3.0-or-later  
**Requirements:** PHP >= 8.5, ext-gd

---

## Table of Contents

1. [Overview](#overview)
2. [Installation](#installation)
3. [Architecture](#architecture)
4. [Getting Started](#getting-started)
5. [Chart Types](#chart-types)
   - [Bar Chart](#bar-chart)
   - [Grouped Bar Chart](#grouped-bar-chart)
   - [Line Chart](#line-chart)
   - [Pie Chart](#pie-chart)
6. [Common API Reference](#common-api-reference)
7. [Output Methods](#output-methods)
8. [Edge Cases & Known Issues](#edge-cases--known-issues)
9. [Examples](#examples)

---

## Overview

Tigress Charts is a lightweight PHP library that generates PNG chart images using the built-in GD graphics library. It supports four chart types:

- **BarChart** — vertical bar chart
- **BarGroupChart** — grouped (clustered) vertical bar chart
- **LineChart** — line chart with point markers
- **PieChart** — pie chart with percentage labels

The library has **zero third-party Composer dependencies** — only `ext-gd` and PHP 8.5+ are required.

---

## Installation

```bash
composer require tigress/charts
```

Ensure the GD extension is enabled in your `php.ini`:

```
extension=gd
```

---

## Architecture

```
Charts (static facade)
  ├── bar()       → BarChart
  ├── barGroup()  → BarGroupChart
  ├── line()      → LineChart
  └── pie()       → PieChart
         │
         └── extends Chart (abstract)
               ├── render(string $path): void        — writes PNG to file
               └── renderBase64(): string             — returns data URI string
```

All chart classes live under the `Tigress` namespace in `src/` and follow PSR-4 autoloading. Every setter returns `static` for fluent (method chaining) usage.

---

## Getting Started

```php
require_once __DIR__ . '/vendor/autoload.php';

use Tigress\Charts;

$chart = Charts::bar()
    ->setTitle('Monthly Revenue')
    ->setData([
        ['label' => 'Jan', 'value' => 120, 'color' => [255, 99, 132]],
        ['label' => 'Feb', 'value' => 90],
        ['label' => 'Mar', 'value' => 150],
    ])
    ->setSize(800, 400)
    ->showValues()
    ->showLegend()
    ->showXAxis()
    ->showYAxis()
    ->setYAxisTicks(150)
    ->setYAxisTickSpacing(25);

// Output to file
$chart->render('/path/to/chart.png');

// Or get a base64 data URI for inline use
echo $chart->renderBase64();
```

---

## Chart Types

### Bar Chart

**Class:** `Tigress\BarChart`  
**Factory:** `Charts::bar()`

Standard vertical bar chart. Each bar is drawn as a filled rectangle.

**Data format:**

```php
[
    ['label' => 'string', 'value' => int|float, 'color' => [R, G, B]],
    // color is optional; random colors are assigned if omitted
]
```

**Note:** If `value` is an array (e.g., `[10, 12]`), it is silently treated as `0` in the bar chart renderer. This is likely unintended — use the Grouped Bar Chart for multi-value data.

---

### Grouped Bar Chart

**Class:** `Tigress\BarGroupChart`  
**Factory:** `Charts::barGroup()`

Groups of sub-bars clustered together. Useful for comparing multiple series side by side.

**Data format:**

```php
[
    ['label' => 'string', 'values' => [int, int, ...], 'colors' => [[R,G,B], [R,G,B], ...]],
    // colors is optional per group; if omitted, random colors are generated for each sub-bar index
]
```

**X-axis labels** are rendered with TrueType (`imagettftext`) at a -45 degree angle using `fonts/arial.ttf`. The font path uses the `SYSTEM_ROOT` constant (provided by Tigress Core) resolved as `SYSTEM_ROOT . '/vendor/tigress/charts/fonts/arial.ttf'`.

---

### Line Chart

**Class:** `Tigress\LineChart`  
**Factory:** `Charts::line()`

Connects data points with a line and 6px filled-circle markers.

**Data format:** Same as Bar Chart:

```php
[
    ['label' => 'string', 'value' => int|float, 'color' => [R, G, B]],
]
```

Each line segment and marker uses the `color` from its starting data point. If omitted, the default blue (0, 123, 255) is used. The legend shows the color of the first data point.

---

### Pie Chart

**Class:** `Tigress\PieChart`  
**Factory:** `Charts::pie()`

Standard pie chart with optional percentage labels on slices. Uses `imagefilledarc()` with `IMG_ARC_PIE`.

**Data format:** Same as Bar Chart:

```php
[
    ['label' => 'string', 'value' => int, 'color' => [R, G, B]],
]
```

- `value` values are proportional — they do not need to sum to 100.
- Percentages are only shown when `showValues()` is enabled (using `renderBase64()` default).
- Pie radius = `min(width, height) / 2 - 60`, so the chart auto-shrinks to leave room for the legend and title.
- **Axes** (`showXAxis`, `showYAxis`) have no effect on PieChart.

---

## Common API Reference

All properties are inherited by all chart types from `Tigress\Chart`.

| Method | Default | Description |
|---|---|---|
| `setTitle(string $title)` | `''` | Chart title, drawn at top-left |
| `setData(array $data)` | `[]` | Chart data (format varies by type). Throws `InvalidArgumentException` if empty. |
| `setSize(int $w, int $h)` | `600x400` | Image dimensions in pixels. Both must be > 0. |
| `showLegend(bool $enabled = true)` | `false` | Toggle legend display |
| `showValues(bool $enabled = true)` | `false` | Toggle value labels above bars / on pie slices |
| `showXAxis(bool $enabled = true)` | `false` | Toggle X-axis line and labels |
| `showYAxis(bool $enabled = true)` | `false` | Toggle Y-axis line and tick labels |
| `setYAxisTicks(int $ticks)` | `100` | Maximum value on the Y-axis (also serves as the scale ceiling). Minimum 1. |
| `setYAxisTickSpacing(int $n)` | `10` | Show a tick label every N ticks on Y-axis. Minimum 1. |
| `setXAxisTickSpacing(int $n)` | `1` | Show an X-axis label every N data points. Minimum 1. |
| `setXAxisLabelAngle(int $deg)` | `-45` | Rotation angle for X-axis labels (TrueType). Use `0` for horizontal. |
| `setLegendX(int $x)` | `150` | Legend horizontal offset (distance from right edge) |
| `setLegendY(int $y)` | `20` | Legend vertical offset (distance from top) |
| `setLegendLabel(string $label)` | `'Set'` | Legend prefix label (used by BarGroupChart as "Set 1", "Set 2", ...) |
| `setBottomPadding(int $n)` | `30` | Bottom padding in pixels (used by BarGroupChart for angled labels) |

### Getters

| Method | Returns |
|---|---|
| `getShowLegend(): bool` | Legend visibility |
| `getShowValues(): bool` | Value label visibility |
| `getShowXAxis(): bool` | X-axis visibility |
| `getShowYAxis(): bool` | Y-axis visibility |
| `getYAxisTicks(): int` | Y-axis tick count |
| `getXAxisTickSpacing(): int` | X-axis tick spacing |
| `getLegendX(): int` | Legend X position |
| `getLegendY(): int` | Legend Y position |
| `getLegendLabel(): string` | Legend label text |
| `getBottomPadding(): int` | Bottom padding |

---

## Output Methods

### `render(string $path): void`

Writes the PNG image to the given filesystem path. The directory must exist and be writable.

```php
$chart->render(__DIR__ . '/output/chart.png');
```

### `renderBase64(): string`

Captures the PNG output to a buffer and returns a data URI string suitable for embedding in HTML `<img>` tags or CSS.

```php
$dataUri = $chart->renderBase64();
echo '<img src="' . $dataUri . '" alt="Chart">';
```

This works by calling `render('php://output')` with output buffering.

### `Charts::version(): string`

Returns the library version string `'2025.12.09'`.

---

## Edge Cases & Known Issues

| Issue | Details |
|---|---|
| **Bar chart with array values** | `example-bar.php` passes `'value' => [10, 12]` for January. The `BarChart::render()` code treats array values as `0` (`is_array($item['value'] ?? null) ? 0 : ...`). This means array values silently disappear. Use `BarGroupChart` for multi-value data. |
| **No data validation** | Beyond `setData()` rejecting empty arrays, there is no validation that data items contain the required keys (`label`, `value`/`values`). Missing keys may produce warnings or broken images. |
| **Y-axis ticks as scale ceiling** | `yAxisTicks` serves double duty as both the tick count and the Y-axis maximum value. The highest tick value equals `yAxisTicks`, so any data value above this will be **clipped** (drawn above the visible chart area). The naming is misleading — it behaves like a max-value setting. |
| **Bottom padding for angled labels** | The default `bottomPadding` is 30px. With the TrueType -45° angled X-axis labels, you may need to increase padding via `setBottomPadding()` to prevent label clipping. |
| **Example scripts assume vendor installed** | All example files `require_once __DIR__ . '/../vendor/autoload.php'`. They will fail if `composer install` has not been run. |

---

## Examples

All examples are in the `examples/` directory. Run them from the project root after `composer install`:

```bash
php examples/example-bar.php
php examples/example-bar-group.php
php examples/example-line.php
php examples/example-pie.php
```

Each generates a PNG file in the same directory and prints a confirmation message.

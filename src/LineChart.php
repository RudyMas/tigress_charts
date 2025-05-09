<?php

namespace Tigress;

/**
 * Class LineChart (PHP version 8.4)
 *
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2025, rudymas.be. (http://www.rudymas.be/)
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version 2025.05.09.0
 * @package Tigress\LineChart
 */
class LineChart extends Chart
{
    public function render(string $path): void
    {
        $img = imagecreatetruecolor($this->width, $this->height);

        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);
        $lineColor = imagecolorallocate($img, 0, 123, 255);

        imagefill($img, 0, 0, $white);

        $topPadding = 50;
        $bottomPadding = 30;
        $leftPadding = 50;
        $rightPadding = 50;

        $maxValue = $this->yAxisTicks;
        $scaleX = ($this->width - $leftPadding - $rightPadding) / (count($this->data) - 1);
        $scaleY = ($this->height - $topPadding - $bottomPadding) / $maxValue;

        // Draw Y-axis with ticks
        if ($this->getShowYAxis()) {
            imageline($img, $leftPadding, $topPadding, $leftPadding, $this->height - $bottomPadding, $black);
            for ($i = 0; $i <= $this->yAxisTicks; $i++) {
                if ($i % $this->yAxisTickSpacing !== 0) continue;
                $step = $maxValue / $this->yAxisTicks;
                $yVal = $i * $step;
                $yPos = $this->height - $bottomPadding - ($yVal * $scaleY);
                imageline($img, $leftPadding - 5, (int)$yPos, $leftPadding + 5, (int)$yPos, $black);
                $label = (string)number_format($yVal, 0, '', '');
                $labelWidth = strlen($label) * 6;
                imagestring($img, 1, $leftPadding - 8 - $labelWidth, (int)$yPos - 6, $label, $black);
            }
        }

        // Draw X-axis
        if ($this->getShowXAxis()) {
            imageline($img, $leftPadding, $this->height - $bottomPadding, $this->width - $rightPadding, $this->height - $bottomPadding, $black);
        }

        // Draw lines between points
        for ($i = 0; $i < count($this->data) - 1; $i++) {
            $x1 = $leftPadding + $i * $scaleX;
            $y1 = $this->height - $bottomPadding - ($this->data[$i]['value'] * $scaleY);
            $x2 = $leftPadding + ($i + 1) * $scaleX;
            $y2 = $this->height - $bottomPadding - ($this->data[$i + 1]['value'] * $scaleY);
            imageline($img, (int)$x1, (int)$y1, (int)$x2, (int)$y2, $lineColor);
        }

        // Draw points, labels, and values
        foreach ($this->data as $i => $point) {
            $x = $leftPadding + $i * $scaleX;
            $y = $this->height - $bottomPadding - ($point['value'] * $scaleY);
            imagefilledellipse($img, (int)$x, (int)$y, 6, 6, $lineColor);

            if ($this->getShowXAxis() && ($i % $this->xAxisTickSpacing === 0)) {
                imagestring($img, 2, (int)$x - 10, (int)($this->height - $bottomPadding + 5), $point['label'], $black);
            }

            if ($this->getShowValues()) {
                imagestring($img, 1, (int)($x - 5), (int)($y - 15), (string)$point['value'], $black);
            }
        }

        // Draw legend if enabled
        if ($this->getShowLegend()) {
            $legendX = $this->width - 150;
            $legendY = 20;
            $legendColor = imagecolorallocate($img, 0, 123, 255);
            imagefilledrectangle($img, $legendX, $legendY, $legendX + 10, $legendY + 10, $legendColor);
            imagestring($img, 2, $legendX + 15, $legendY, $this->title, $black);
        }

        imagestring($img, 5, 10, 10, $this->title, $black);

        imagepng($img, $path);
        imagedestroy($img);
    }
}

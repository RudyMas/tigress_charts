<?php

namespace Tigress;

/**
 * Class BarChart (PHP version 8.4)
 *
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2025, rudymas.be. (http://www.rudymas.be/)
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version 2025.05.09.0
 * @package Tigress\BarChart
 */
class BarChart extends Chart
{
    public function render(string $path): void
    {
        $img = imagecreatetruecolor($this->width, $this->height);

        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);

        imagefill($img, 0, 0, $white);

        $topPadding = 50;
        $bottomPadding = 30;
        $leftPadding = 50;
        $rightPadding = 50;

        $barWidth = 40;
        $spacing = 20;
        $x = $leftPadding;

        $maxValue = $this->yAxisTicks;
        $scale = ($this->height - $topPadding - $bottomPadding) / $maxValue;

        // Draw Y-axis with ticks
        if ($this->getShowYAxis()) {
            imageline($img, $leftPadding, $topPadding, $leftPadding, $this->height - $bottomPadding, $black);
            for ($i = 0; $i <= $this->yAxisTicks; $i++) {
                if ($i % $this->yAxisTickSpacing !== 0) continue;
                $step = $maxValue / $this->yAxisTicks;
                $yVal = $i * $step;
                $yPos = $this->height - $bottomPadding - ($yVal * $scale);
                imageline($img, $leftPadding - 5, (int)$yPos, $leftPadding + 5, (int)$yPos, $black);
                $label = (string)number_format($yVal, 0, '', '');
                $labelWidth = strlen($label) * 6;
                imagestring($img, 1, $leftPadding - 8 - $labelWidth, (int)$yPos - 6, $label, $black);
            }
        }

        // Draw X-axis with ticks
        if ($this->getShowXAxis()) {
            imageline($img, $leftPadding, $this->height - $bottomPadding, $this->width - $rightPadding, $this->height - $bottomPadding, $black);
        }

        $legendY = 20;

        foreach ($this->data as $index => $item) {
            $label = $item['label'] ?? '';
            $value = is_array($item['value'] ?? null) ? 0 : ($item['value'] ?? 0);
            $color = $item['color'] ?? null;

            if (!$color) {
                $color = $this->data[$index]['color'] = [rand(50, 200), rand(50, 200), rand(50, 200)];
            }

            $colorOutput = imagecolorallocate(
                $img,
                $color[0] ?? 0,
                $color[1] ?? 0,
                $color[2] ?? 0
            );

            $barHeight = $value * $scale;
            $barX = $x + $index * ($barWidth + $spacing);
            $barY = $this->height - $bottomPadding - $barHeight;

            imagefilledrectangle($img, (int)$barX, (int)$barY, (int)($barX + $barWidth), (int)($this->height - $bottomPadding), $colorOutput);

            if ($this->getShowValues()) {
                imagestring($img, 2, (int)($barX + 2), (int)($barY - 15), (string)$value, $black);
            }

            if ($this->getShowXAxis() && ($index % $this->xAxisTickSpacing === 0)) {
                imagestring($img, 2, (int)$barX, (int)($this->height - $bottomPadding + 5), $label, $black);
            }
        }

        if ($this->getShowLegend()) {
            $xLegend = $this->width - 150;
            foreach ($this->data as $item) {
                $label = $item['label'] ?? '';
                $value = is_array($item['value'] ?? null) ? 0 : ($item['value'] ?? 0);
                $color = $item['color'] ?? [rand(50, 200), rand(50, 200), rand(50, 200)];
                $col = imagecolorallocate($img, $color[0], $color[1], $color[2]);
                imagefilledrectangle($img, $xLegend, $legendY, $xLegend + 10, $legendY + 10, $col);
                imagestring($img, 2, $xLegend + 15, $legendY, "$label ($value)", $black);
                $legendY += 15;
            }
        }

        imagestring($img, 5, 10, 10, $this->title, $black);

        imagepng($img, $path);
        imagedestroy($img);
    }
}

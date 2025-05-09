<?php

namespace Tigress;

/**
 * Class BarGroupChart (PHP version 8.4)
 *
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2025, rudymas.be. (http://www.rudymas.be/)
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version 2025.05.09.0
 * @package Tigress\BarGroupChart
 */
class BarGroupChart extends Chart
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

        $groupCount = count($this->data);
        $subBarCount = max(array_map(fn($item) => count($item['values'] ?? []), $this->data));

        $allValues = array_merge(...array_map(fn($item) => $item['values'], $this->data));
        $maxValue = $this->yAxisTicks;

        $scale = ($this->height - $topPadding - $bottomPadding) / $maxValue;
        $groupWidth = 40;
        $groupSpacing = 20;
        $subBarWidth = $groupWidth / max(1, $subBarCount);

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

        // Draw X-axis
        if ($this->getShowXAxis()) {
            imageline($img, $leftPadding, $this->height - $bottomPadding, $this->width - $rightPadding, $this->height - $bottomPadding, $black);
        }

        // Draw grouped bars
        $legendY = 20;
        $x = $leftPadding;
        $colors = [];

        foreach ($this->data as $groupIndex => $item) {
            $label = $item['label'] ?? '';
            $values = $item['values'] ?? [];

            for ($i = 0; $i < count($values); $i++) {
                if (!isset($colors[$i])) {
                    $colors[$i] = $this->data[$groupIndex]['colors'][$i] = [rand(50, 200), rand(50, 200), rand(50, 200)];
                }

                $value = $values[$i];
                $color = $colors[$i];
                $colorGD = imagecolorallocate($img, $color[0], $color[1], $color[2]);

                $barHeight = $value * $scale;
                $barX = $x + $i * $subBarWidth;
                $barY = $this->height - $bottomPadding - $barHeight;

                imagefilledrectangle(
                    $img,
                    (int)$barX,
                    (int)$barY,
                    (int)($barX + $subBarWidth - 2),
                    (int)($this->height - $bottomPadding),
                    $colorGD
                );

                if ($this->getShowValues()) {
                    imagestring(
                        $img,
                        1,
                        (int)($barX + 1),
                        (int)($barY - 12),
                        (string)$value,
                        $black
                    );
                }
            }

            if ($this->getShowXAxis()) {
                $labelX = $x + ($groupWidth / 2) - (strlen($label) * 3);
                imagestring($img, 2, (int)$labelX, (int)($this->height - $bottomPadding + 5), $label, $black);
            }

            $x += $groupWidth + $groupSpacing;
        }

        // Draw legend
        if ($this->getShowLegend()) {
            foreach ($colors as $i => $rgb) {
                $legendColor = imagecolorallocate($img, $rgb[0], $rgb[1], $rgb[2]);
                imagefilledrectangle($img, $this->width - 150, $legendY, $this->width - 140, $legendY + 10, $legendColor);
                imagestring($img, 2, $this->width - 135, $legendY, "Set " . ($i + 1), $black);
                $legendY += 15;
            }
        }

        imagestring($img, 5, 10, 10, $this->title, $black);

        imagepng($img, $path);
        imagedestroy($img);
    }
}

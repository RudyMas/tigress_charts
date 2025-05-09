<?php

namespace Tigress;

class BarChart extends Chart
{
    public function render(string $path): void
    {
        $img = imagecreatetruecolor($this->width, $this->height);

        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);

        imagefill($img, 0, 0, $white);

        $barWidth = 40;
        $spacing = 20;
        $x = 50;
        $topPadding = 50;
        $bottomPadding = 30;
        $leftPadding = 50;
        $rightPadding = 50;
        $maxValue = max(array_column($this->data, 'value'));
        $scale = ($this->height - 100) / $maxValue;

        // Draw Y-axis with ticks
        if ($this->getShowYAxis()) {
            imageline($img, 50, $topPadding, 50, $this->height - $bottomPadding, $black);
            for ($i = 0; $i <= $this->yAxisTicks; $i++) {
                $yVal = $i * $maxValue / $this->yAxisTicks;
                $yPos = $this->height - $bottomPadding - ($yVal * $scale);
                imageline($img, $leftPadding - 5, (int)$yPos, $leftPadding + 5, (int)$yPos, $black);
                imagestring($img, 1, 5, (int)$yPos - 6, (string)(int)$yVal, $black);
            }
        }

        // Draw X-axis with ticks
        if ($this->getShowXAxis()) {
            imageline($img, $leftPadding, $this->height - $bottomPadding, $this->width - $rightPadding, $this->height - $bottomPadding, $black);
        }

        $legendY = 20;

        foreach ($this->data as $index => $item) {
            $label = $item['label'] ?? '';
            $value = $item['value'] ?? 0;
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

            imagefilledrectangle($img, (int)$barX, (int)($this->height - $barHeight - $bottomPadding), (int)($barX + $barWidth), (int)($this->height - $bottomPadding), $colorOutput);

            if ($this->getShowValues()) {
                imagestring($img, 2, (int)($barX + 5), (int)($this->height - $barHeight - $bottomPadding - 15), (string)$value, $black);
            }

            if ($this->getShowXAxis() && ($index % $this->xAxisTickSpacing === 0)) {
                imagestring($img, 2, (int)$barX, (int)($this->height - $bottomPadding), $label, $black);
            }
        }

        if ($this->getShowLegend()) {
            $xLegend = $this->width - 150;
            foreach ($this->data as $item) {
                $label = $item['label'] ?? '';
                $value = $item['value'] ?? 0;
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

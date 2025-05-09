<?php

namespace Tigress;

class LineChart extends Chart
{
    public function render(string $path): void
    {
        $img = imagecreatetruecolor($this->width, $this->height);

        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);
        $lineColor = imagecolorallocate($img, 0, 123, 255);

        imagefill($img, 0, 0, $white);

        $maxValue = max(array_column($this->data, 'value'));
        $scaleX = ($this->width - 100) / (count($this->data) - 1);
        $scaleY = ($this->height - 100) / $maxValue;

        // Draw line path
        for ($i = 0; $i < count($this->data) - 1; $i++) {
            $x1 = 50 + $i * $scaleX;
            $y1 = $this->height - 50 - ($this->data[$i]['value'] * $scaleY);
            $x2 = 50 + ($i + 1) * $scaleX;
            $y2 = $this->height - 50 - ($this->data[$i + 1]['value'] * $scaleY);
            imageline($img, $x1, $y1, $x2, $y2, $lineColor);
        }

        // Draw points, labels, and values
        foreach ($this->data as $i => $point) {
            $x = 50 + $i * $scaleX;
            $y = $this->height - 50 - ($point['value'] * $scaleY);
            imagefilledellipse($img, $x, $y, 6, 6, $lineColor);
            imagestring($img, 2, $x - 10, $this->height - 40, $point['label'], $black);

            if ($this->getShowValues()) {
                imagestring($img, 1, $x - 5, $y - 15, (string)$point['value'], $black);
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

<?php

namespace Tigress;

class PieChart extends Chart
{
    public function render(string $path): void
    {
        $img = imagecreatetruecolor($this->width, $this->height);

        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);

        imagefill($img, 0, 0, $white);

        $total = array_sum(array_column($this->data, 'value'));
        $angleStart = 0;
        $centerX = $this->width / 2;
        $centerY = $this->height / 2;
        $radius = min($this->width, $this->height) / 2 - 20;

        $legendX = $this->width - 160;
        $legendY = 20;

        foreach ($this->data as $item) {
            $value = $item['value'] ?? 0;
            $label = $item['label'] ?? '';
            $angle = ($value / $total) * 360;

            $colorArr = $item['color'] ?? [rand(50, 200), rand(50, 200), rand(50, 200)];
            $color = imagecolorallocate($img, $colorArr[0], $colorArr[1], $colorArr[2]);

            imagefilledarc(
                $img,
                $centerX,
                $centerY,
                $radius * 2,
                $radius * 2,
                $angleStart,
                $angleStart + $angle,
                $color,
                IMG_ARC_PIE
            );

            if ($this->getShowValues()) {
                $midAngle = deg2rad($angleStart + $angle / 2);
                $labelX = (int)($centerX + cos($midAngle) * $radius * 0.7);
                $labelY = (int)($centerY + sin($midAngle) * $radius * 0.7);
                $percent = round(($value / $total) * 100) . '%';
                imagestring($img, 2, $labelX - 10, $labelY - 7, $percent, $black);
            }

            if ($this->getShowLegend()) {
                imagefilledrectangle($img, $legendX, $legendY, $legendX + 10, $legendY + 10, $color);
                imagestring($img, 2, $legendX + 15, $legendY, "$label ($value)", $black);
                $legendY += 15;
            }

            $angleStart += $angle;
        }

        imagestring($img, 5, 10, 10, $this->title, $black);

        imagepng($img, $path);
        imagedestroy($img);
    }
}

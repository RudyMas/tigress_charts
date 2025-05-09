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
        $maxValue = max(array_column($this->data, 'value'));
        $scale = ($this->height - 100) / $maxValue;

        $legendY = 20;

        foreach ($this->data as $item) {
            $label = $item['label'] ?? '';
            $value = $item['value'] ?? 0;
            $color = $item['color'] ?? null;

            if (!$color) {
                $color = imagecolorallocate(
                    $img,
                    rand(50, 200),
                    rand(50, 200),
                    rand(50, 200)
                );
            } else {
                $color = imagecolorallocate(
                    $img,
                    $color[0] ?? 0,
                    $color[1] ?? 0,
                    $color[2] ?? 0
                );
            }

            $barHeight = $value * $scale;
            imagefilledrectangle($img, $x, $this->height - $barHeight - 50, $x + $barWidth, $this->height - 50, $color);

            if ($this->getShowValues()) {
                imagestring($img, 2, $x + 2, $this->height - $barHeight - 65, (string)$value, $black);
            }

            imagestring($img, 2, $x, $this->height - 40, $label, $black);
            $x += $barWidth + $spacing;
        }

        if ($this->getShowLegend()) {
            $x = $this->width - 150;
            foreach ($this->data as $item) {
                $label = $item['label'] ?? '';
                $value = $item['value'] ?? 0;
                $color = $item['color'] ?? [rand(50, 200), rand(50, 200), rand(50, 200)];
                $col = imagecolorallocate($img, $color[0], $color[1], $color[2]);
                imagefilledrectangle($img, $x, $legendY, $x + 10, $legendY + 10, $col);
                imagestring($img, 2, $x + 15, $legendY, "$label ($value)", $black);
                $legendY += 15;
            }
        }

        imagestring($img, 5, 10, 10, $this->title, $black);

        imagepng($img, $path);
        imagedestroy($img);
    }
}

<?php

namespace Tigress;

/**
 * Abstract Class Chart (PHP version 8.4)
 *
 * @author Rudy Mas <rudy.mas@rudymas.be>
 * @copyright 2025, rudymas.be. (http://www.rudymas.be/)
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License, version 3 (GPL-3.0)
 * @version 2025.05.09.0
 * @package Tigress\Chart
 */
abstract class Chart
{
    protected string $title = '';
    protected array $data = [];

    protected int $width = 600;
    protected int $height = 400;

    protected bool $showLegend = false;
    protected bool $showValues = false;
    protected bool $showXAxis = false; // Controls whether X-axis is shown
    protected bool $showYAxis = false; // Controls whether Y-axis is shown
    protected int $yAxisTicks = 100;     // Number of ticks on Y-axis
    protected int $yAxisTickSpacing = 10;
    protected int $xAxisTickSpacing = 1; // Spacing between X-axis ticks (index based)

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function setData(array $data): static
    {
        if (empty($data)) {
            throw new \InvalidArgumentException("Chart data cannot be empty.");
        }
        $this->data = $data;
        return $this;
    }

    public function setSize(int $width, int $height): static
    {
        if ($width <= 0 || $height <= 0) {
            throw new \InvalidArgumentException("Width and height must be positive integers.");
        }
        $this->width = $width;
        $this->height = $height;
        return $this;
    }

    public function showLegend(bool $enabled = true): static
    {
        $this->showLegend = $enabled;
        return $this;
    }

    public function showValues(bool $enabled = true): static
    {
        $this->showValues = $enabled;
        return $this;
    }

    public function showXAxis(bool $enabled = true): static
    {
        $this->showXAxis = $enabled;
        return $this;
    }

    public function showYAxis(bool $enabled = true): static
    {
        $this->showYAxis = $enabled;
        return $this;
    }

    public function setYAxisTicks(int $ticks): static
    {
        $this->yAxisTicks = max(1, $ticks);
        return $this;
    }

    public function setYAxisTickSpacing(int $spacing): static
    {
        $this->yAxisTickSpacing = max(1, $spacing);
        return $this;
    }

    public function setXAxisTickSpacing(int $spacing): static
    {
        $this->xAxisTickSpacing = max(1, $spacing);
        return $this;
    }

    public function getShowLegend(): bool
    {
        return $this->showLegend;
    }

    public function getYAxisTicks(): int
    {
        return $this->yAxisTicks;
    }

    public function getXAxisTickSpacing(): int
    {
        return $this->xAxisTickSpacing;
    }

    public function getShowValues(): bool
    {
        return $this->showValues;
    }

    public function getShowXAxis(): bool
    {
        return $this->showXAxis;
    }

    public function getShowYAxis(): bool
    {
        return $this->showYAxis;
    }

    abstract public function render(string $path): void;

    public function renderBase64(): string
    {
        ob_start();
        $this->render('php://output');
        $imageData = ob_get_clean();
        return 'data:image/png;base64,' . base64_encode($imageData);
    }
}

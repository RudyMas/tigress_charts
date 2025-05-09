<?php

namespace Tigress;

abstract class Chart
{
    protected string $title = '';
    protected array $data = [];

    protected int $width = 600;
    protected int $height = 400;

    protected bool $showLegend = false;
    protected bool $showValues = false;

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

    public function getShowLegend(): bool
    {
        return $this->showLegend;
    }

    public function getShowValues(): bool
    {
        return $this->showValues;
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

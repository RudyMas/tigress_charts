<?php

namespace Tigress;

abstract class Chart
{
    protected string $title = '';
    protected array $data = [];

    protected int $width = 600;
    protected int $height = 400;

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    public function setSize(int $width, int $height): static
    {
        $this->width = $width;
        $this->height = $height;
        return $this;
    }

    abstract public function render(string $path): void;
}
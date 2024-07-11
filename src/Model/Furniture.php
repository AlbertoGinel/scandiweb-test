<?php

class Furniture extends Product {
  protected $height;
  protected $width;
  protected $length;

  // Constructor for Furniture
  public function __construct(int $id = null, string $sku, string $name, float $price, string $type, int $height = null, int $width = null, int $length = null) {
    parent::__construct($id ?? null, $sku, $name, $price, $type);
    $this->setHeight($height);
    $this->setWidth($width);
    $this->setLength($length);
  }

  // Getter for height
  public function getHeight(): int {
    return $this->height;
  }

  // Setter for height with validation
  public function setHeight(int $height): void {
    if ($height <= 0) {
      throw new InvalidArgumentException("Height must be a positive number.");
    }
    $this->height = $height;
  }

  // Getter for width
  public function getWidth(): int {
    return $this->width;
  }

  // Setter for width with validation
  public function setWidth(int $width): void {
    if ($width <= 0) {
      throw new InvalidArgumentException("Width must be a positive number.");
    }
    $this->width = $width;
  }

  // Getter for length
  public function getLength(): int {
    return $this->length;
  }

  // Setter for length with validation
  public function setLength(int $length): void {
    if ($length <= 0) {
      throw new InvalidArgumentException("Length must be a positive number.");
    }
    $this->length = $length;
  }

  public function getValidationErrors(): array {
    $errors = parent::getValidationErrors() ?? [];

    // Use super product parent validator

    if ($this->getHeight() <= 0) {
      $errors[] = "Height must be a positive number.";
    }
    if ($this->getWidth() <= 0) {
      $errors[] = "Width must be a positive number.";
    }
    if ($this->getLength() <= 0) {
      $errors[] = "Length must be a positive number.";
    }

    return $errors;
  }

  public function getFormattedData(): array {
    $data = [
      "id" => $this->id,
      "SKU" => $this->sku,
      "name" => $this->name,
      "price" => number_format($this->price, 2, '.', ''),
      "height" => is_null($this->height) ? null : $this->height,
      "width" => is_null($this->width) ? null : $this->width,
      "length" => is_null($this->length) ? null : $this->length,
      "type" => $this->type,
    ];

    return $data;
  }
}



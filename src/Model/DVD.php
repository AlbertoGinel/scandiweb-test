<?php

class DVD extends Product {
    protected $size;

    public function __construct(int $id = null, string $sku, string $name, float $price, string $type, int $size) {
        parent::__construct($id ?? null, $sku, $name, $price, $type);
        $this->size = $size;
    }

    // Getter for size
    public function getSize(): ?int {
        return $this->size;
    }

    // Setter for size with validation
    public function setSize(int $size): void {
        if ($size <= 0) {
            throw new InvalidArgumentException('Size must be a positive number.');
        }
        $this->size = $size;
    }

    public function getValidationErrors(): array {
        $errors = parent::getValidationErrors() ?? [];

        if ($this->size <= 0) {
            $errors[] = "Size must be a positive number.";
        }

        return $errors;
    }

    public function getFormattedData(): array {
        $data = [
            "id" => $this->id,
            "SKU" => $this->sku,
            "name" => $this->name,
            "price" => number_format($this->price, 2, '.', ''), // Format price with 2 decimal places
            "size" => is_null($this->size) ? null : $this->size, // Set size to null explicitly if not set
            "type" => $this->type,
        ];

        return $data;
    }
}

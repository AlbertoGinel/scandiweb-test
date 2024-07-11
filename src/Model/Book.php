<?php

class Book extends Product {
    protected $weight;

    public function __construct(int $id = null, string $sku, string $name, float $price, string $type, float $weight) {
        parent::__construct($id ?? null, $sku, $name, $price, $type);
        $this->weight = $weight;
    }

    // Getter for weight
    public function getWeight(): ?float {
        return $this->weight;
    }

    // Setter for weight with validation
    public function setWeight(float $weight): void {
        if ($weight <= 0) {
            throw new InvalidArgumentException('Weight must be a positive number.');
        }
        $this->weight = $weight;
    }

    public function getValidationErrors(): array {
        $errors = parent::getValidationErrors() ?? [];

        if ($this->weight <= 0) {
            $errors[] = "Weight must be a positive number.";
        }

        return $errors;
    }

    public function getFormattedData(): array {
        $data = [
            "id" => $this->id,
            "SKU" => $this->sku,
            "name" => $this->name,
            "price" => number_format($this->price, 2, '.', ''), // Format price with 2 decimal places
            "weight" => is_null($this->weight) ? null : $this->weight, // Set weight to null explicitly if not set
            "type" => $this->type,
        ];

        return $data;
    }
}

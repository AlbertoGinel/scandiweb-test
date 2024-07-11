<?php

abstract class Product {

    protected $id;
    protected $sku;
    protected $name;
    protected $price;
    protected $type;

    // Constructor
    public function __construct(int $id = null, string $sku, string $name, float $price, string $type) {
        $this->setId($id);
        $this->setSku($sku);
        $this->setName($name);
        $this->setPrice($price);
        $this->setType($type);
    }

    // Getters
    public function getId(): ?int {
        return $this->id;
    }

    public function getSku(): string {
        return $this->sku;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function getType(): string {
        return $this->type;
    }

    // Setters with basic validation
    public function setId(?int $id): void {
        if ($id !== null && !is_int($id)) {
            throw new InvalidArgumentException('ID must be an integer.');
        }
        $this->id = $id;
    }

    public function setSku(string $sku): void {
        if (empty(trim($sku))) {
            throw new InvalidArgumentException('SKU is required.');
        }
        $this->sku = $sku;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function setPrice(float $price): void {
        if ($price <= 0) {
            throw new InvalidArgumentException('Price must be a positive number.');
        }
        $this->price = $price;
    }

    public function setType(string $type): void {
        $validTypes = ['DVD', 'Furniture', 'Book'];
        if (!in_array($type, $validTypes)) {
            throw new InvalidArgumentException('Invalid product type.');
        }
        $this->type = $type;
    }

    public function getValidationErrors(): array {
        $errors = [];

        // Validate SKU
        if (empty(trim($this->sku))) {
            $errors[] = "SKU is required.";
        }

        // Validate price
        if ($this->price <= 0) {
            $errors[] = "Price must be a positive number.";
        }

        // Validate type
        $validTypes = ['DVD', 'Furniture', 'Book'];
        if (!in_array($this->type, $validTypes)) {
            $errors[] = "Invalid product type.";
        }

        // Optionally, validate other properties as needed

        return $errors;
    }
}

<?php

class ProductGateway
{
  #PHP Data Objects (PDO)
  private PDO $conn;

  public function __construct(Database $database)
  {
    $this->conn = $database->getConnection();
  }

  //I wanted a overLoadedConstructer but in PHP it isnt a thing.
  public function overLoadConstructor(array $product): Product
  {

switch ($product['type']) {
    case 'DVD':
        return new DVD(
            isset($product['id']) ? $product['id'] : null, // Check if product ID exists
            $product['SKU'],
            $product['name'],
            $product['price'],
            'DVD',
            $product['size']
        );
        break;
    case 'Furniture':
        return new Furniture(
            isset($product['id']) ? $product['id'] : null, // Check if product ID exists
            $product['SKU'],
            $product['name'],
            $product['price'],
            'Furniture',
            $product['height'],
            $product['width'],
            $product['length']
        );
        break;
    case 'Book':
        return new Book(
            isset($product['id']) ? $product['id'] : null, // Check if product ID exists
            $product['SKU'],
            $product['name'],
            $product['price'],
            'Book',
            $product['weight']
        );
        break;
    default:
        throw new Exception('Unknown product type: ' . $product['type']);
}
}


  public function getAll(): array
  {
      $sql = "SELECT * FROM products";
      $stmt = $this->conn->query($sql);
      $data = [];

      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $product = $this->overLoadConstructor($row);
        $data[] = $product->getFormattedData();
    }

      return $data;
  }

  public function create(Product $product): string
  {
    // Initialize variables with default values
    $sku = '';
    $name = '';
    $price = 0;
    $size = null;
    $weight = null;
    $height = null;
    $width = null;
    $length = null;
    $type = '';

    // Check if getter methods exist and call them if they do
    if (method_exists($product, 'getSku')) {
        $sku = $product->getSku();
    }
    if (method_exists($product, 'getName')) {
        $name = $product->getName();
    }
    if (method_exists($product, 'getPrice')) {
        $price = $product->getPrice();
    }
    if (method_exists($product, 'getSize')) {
        $size = $product->getSize();
    }
    if (method_exists($product, 'getWeight')) {
        $weight = $product->getWeight();
    }
    if (method_exists($product, 'getHeight')) {
        $height = $product->getHeight();
    }
    if (method_exists($product, 'getWidth')) {
        $width = $product->getWidth();
    }
    if (method_exists($product, 'getLength')) {
        $length = $product->getLength();
    }
    if (method_exists($product, 'getType')) {
        $type = $product->getType();
    }

    $sql = "INSERT INTO products (SKU, name, price, size, weight, height, width, length, type)
    VALUES (:SKU, :name, :price, :size, :weight, :height, :width, :length, :type)";

    $stmt = $this->conn->prepare($sql);

    $stmt->bindValue(":SKU", $sku, PDO::PARAM_STR);
    $stmt->bindValue(":name", $name, PDO::PARAM_STR);
    $stmt->bindValue(":price", $price ?? 0, PDO::PARAM_STR);
    $stmt->bindValue(":size", $size ?? NULL, PDO::PARAM_INT);
    $stmt->bindValue(":weight", $weight ?? NULL, PDO::PARAM_STR);
    $stmt->bindValue(":height", $height ?? NULL, PDO::PARAM_INT);
    $stmt->bindValue(":width", $width ?? NULL, PDO::PARAM_INT);
    $stmt->bindValue(":length", $length ?? NULL, PDO::PARAM_INT);
    $stmt->bindValue(":type", $type, PDO::PARAM_STR);

    $stmt->execute();

    $lastInsertedId = $this->conn->lastInsertId();

    return $lastInsertedId;
  }


  public function getByID(string $id): ?array
  {
    $sql = "SELECT * FROM products WHERE id = :id";
    $stmt = $this->conn->prepare($sql);

    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
      // Product not found, return null
      return null;
    }

    // Attempt to create a product object and get formatted data
    $product = $this->overLoadConstructor($data);
    $productFinal = $product->getFormattedData();

    if (!$productFinal) {
      // Formatting failed, return null
      return null;
    }

    return $productFinal;
  }

  public function update(Product $current, Product $new): int
  {
      // Initialize variables with default values
      $sku = '';
      $name = '';
      $price = 0;
      $size = null;
      $weight = null;
      $height = null;
      $width = null;
      $length = null;
      $type = '';
  
      // Check if getter methods exist and call them if they do
      if (method_exists($new, 'getSku')) {
          $sku = $new->getSku();
      } elseif (method_exists($current, 'getSku')) {
          $sku = $current->getSku();
      }
  
      if (method_exists($new, 'getName')) {
          $name = $new->getName();
      } elseif (method_exists($current, 'getName')) {
          $name = $current->getName();
      }
  
      if (method_exists($new, 'getPrice')) {
          $price = $new->getPrice();
      } elseif (method_exists($current, 'getPrice')) {
          $price = $current->getPrice();
      }
  
      if (method_exists($new, 'getSize')) {
          $size = $new->getSize();
      } elseif (method_exists($current, 'getSize')) {
          $size = $current->getSize();
      }
  
      if (method_exists($new, 'getWeight')) {
          $weight = $new->getWeight();
      } elseif (method_exists($current, 'getWeight')) {
          $weight = $current->getWeight();
      }
  
      if (method_exists($new, 'getHeight')) {
          $height = $new->getHeight();
      } elseif (method_exists($current, 'getHeight')) {
          $height = $current->getHeight();
      }
  
      if (method_exists($new, 'getWidth')) {
          $width = $new->getWidth();
      } elseif (method_exists($current, 'getWidth')) {
          $width = $current->getWidth();
      }
  
      if (method_exists($new, 'getLength')) {
          $length = $new->getLength();
      } elseif (method_exists($current, 'getLength')) {
          $length = $current->getLength();
      }
  
      if (method_exists($new, 'getType')) {
          $type = $new->getType();
      } elseif (method_exists($current, 'getType')) {
          $type = $current->getType();
      }
  
      // Proceed with database update using the variables
      $sql = "UPDATE products
              SET SKU = :SKU,
                  name = :name,
                  price = :price,
                  size = :size,
                  weight = :weight,
                  height = :height,
                  width = :width,
                  length = :length,
                  type = :type
              WHERE id = :id";
  
      $stmt = $this->conn->prepare($sql);
  
      $stmt->bindValue(":SKU", $sku, PDO::PARAM_STR);
      $stmt->bindValue(":name", $name, PDO::PARAM_STR);
      $stmt->bindValue(":price", $price ?? 0, PDO::PARAM_STR);
      $stmt->bindValue(":size", $size ?? NULL, PDO::PARAM_INT);
      $stmt->bindValue(":weight", $weight ?? NULL, PDO::PARAM_STR);
      $stmt->bindValue(":height", $height ?? NULL, PDO::PARAM_INT);
      $stmt->bindValue(":width", $width ?? NULL, PDO::PARAM_INT);
      $stmt->bindValue(":length", $length ?? NULL, PDO::PARAM_INT);
      $stmt->bindValue(":type", $type, PDO::PARAM_STR);
  
      $stmt->bindValue(":id", $current->getId(), PDO::PARAM_INT); // Assuming getId() exists
  
      $stmt->execute();
  
      return $stmt->rowCount();
  }
  

  public function delete(string $id): int
  {
    $sql = "DELETE FROM products WHERE id = :id";

    $stmt = $this->conn->prepare($sql);

    $stmt->bindValue(":id", $id, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->rowCount();
  }

}
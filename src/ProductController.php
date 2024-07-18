<?php

class ProductController
{
  public function __construct(private ProductGateway $gateway)
  {

  }

  public function processRequest(string $method, ?string $id):void
  {
    if($id){
      $this->processResourceRequest($method, $id);
    }else{
      $this->processCollectionRequest($method);
    }
  }

  private function processResourceRequest(string $method, ?string $id): void
  {
    $product = $this->gateway->getByID($id);

    if(!$product){
      http_response_code(404);
      echo json_encode(["message"=>"Product not found"]);
      return;
    }

    switch($method){
      case "GET":
        echo json_encode($product);
        break;

      case "PATCH":
        $data = file_get_contents("php://input", true);

        //var_dump($data);

        $dataJSON = (array) json_decode($data);

        var_dump($dataJSON);

        $newProduct = $this->gateway->overLoadConstructor($dataJSON);

        $productObj = $this->gateway->overLoadConstructor($product);

        $errors = $newProduct->getValidationErrors();

        if (!empty($errors)){

          http_response_code(422);
          echo json_encode(["errors"=>$errors]);
          break;

        }

        $rows = $this->gateway->update($productObj, $newProduct);

        echo json_encode([
          "message" => "Product $id updated",
          "rows" => $rows
        ]);
        break;

      case "DELETE":
        $rows = $this->gateway->delete($id);

        echo json_encode([
          "message" => "Product $id deleted",
          "rows" => $rows
        ]);
        break;

      default:
        http_response_code(405);
        header("Allow: GET, PATCH, DELETE");
    }


  }

private function processCollectionRequest(string $method): void
{
  switch ($method) {

    case "OPTIONS":
      http_response_code(200);
      header("Access-Control-Allow-Origin: *"); // Adjust this to match your CORS policy
      header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
      header("Access-Control-Allow-Headers: Content-Type, Authorization");
      echo json_encode(['message' => 'CORS preflight successful']);
      break;



    case 'GET':
        echo json_encode($this->gateway->getAll());
        break;

    case 'POST':
        $data = file_get_contents("php://input", true);
        $dataJSON = (array) json_decode($data);

        $newProduct = $this->gateway->overLoadConstructor($dataJSON);
        $errors = $newProduct->getValidationErrors();

        if (!empty($errors)) {
            http_response_code(422);
            echo json_encode(["errors" => $errors]);
            break;
        }

        $id = $this->gateway->create($newProduct);

        http_response_code(201);
        echo json_encode([
            "message" => "Product created",
            "id" => $id
        ]);
        break;

    case 'DELETE':
        $data = file_get_contents("php://input", true);
        $dataJSON = json_decode($data, true);

        if (!isset($dataJSON['idList']) || !is_array($dataJSON['idList'])) {
            http_response_code(400);
            echo json_encode(["error" => "Invalid input, expected 'idList' as an array"]);
            break;
        }

        $idList = $dataJSON['idList'];
        $deletedIds = [];
        $errors = [];

        foreach ($idList as $id) {
            if ($this->gateway->delete($id)) {
                $deletedIds[] = $id;
            } else {
                $errors[] = "Failed to delete ID $id";
         }
        }

        if (!empty($errors)) {
            http_response_code(400); // 207 Multi-Status
            echo json_encode(["deleted" => $deletedIds, "errors" => $errors]);
        } else {
            http_response_code(200);
            echo json_encode(["message" => "Products deleted", "deleted" => $deletedIds]);
        }
        break;



    default:
        http_response_code(405);
        header("Allow: GET, POST, DELETE, OPTIONS");
  }
}

  private function getValidationErrors(string $data): array
  {
      $errors = [];
      // Parse the JSON string into an associative array
      $parsedData = json_decode($data, true);
  
      // Validate SKU
      if (!isset($parsedData['SKU']) || empty(trim($parsedData['SKU']))) {
          $errors[] = "SKU is required.";
      }
  
      // Validate price
      if (isset($parsedData['price'])) {
          if (!is_numeric($parsedData['price']) || $parsedData['price'] <= 0) {
              $errors[] = "Price must be a positive number.";
          }
      }
  
      // Validate size
      if (isset($parsedData['size'])) {
          if (!is_int($parsedData['size']) || $parsedData['size'] < 0) {
              $errors[] = "Size must be a positive integer.";
          }
      }
  
      // Validate type
      if (isset($parsedData['type'])) {
          $validTypes = ['DVD', 'Furniture', 'Book'];
          if (!in_array($parsedData['type'], $validTypes)) {
              $errors[] = "Invalid product type.";
          }
      }
  
      return $errors;
  }
  
}
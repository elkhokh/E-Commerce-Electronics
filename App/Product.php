<?php

namespace App;
use App\Traits\MangesFiles;
use PDO;
use PDOException;
use Exception;

class Product {
    private ?string $id = null;
    private string $name;
    private string $description;
    private float $price;
    private int $quantity;
    private string $main_image;
    private int $category_id;
    private ?int $subcategory_id;
    private int $status;
    private array $colors = [];
    private string $created_at;

    public function __construct() {
        $this->created_at = date('Y-m-d H:i:s');
    }

    // Create new product
    public static function create(PDO $db, string $name, string $description, float $price, int $quantity, array $main_image, int $category_id, ?int $subcategory_id = null, array $colors = [], int $status = 1): ?Product {
        try {
            $db->beginTransaction();
            
            $product = new Product();
            $product->setName($name); 
            
            $main_image = MangesFiles::UploadFile($main_image, ['jpg','png','jpeg'], "Public/assets/front/img/product/{$name}");
            if(!$main_image){
                return null;
            }

            $query = "INSERT INTO products (name, description, price, quantity, main_image, category_id, subcategory_id, status) 
                     VALUES (:name, :description, :price, :quantity, :main_image, :category_id, :subcategory_id, :status)";
            
            $stmt = $db->prepare($query);
            $stmt->execute([
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'quantity' => $quantity,
                'main_image' => $main_image['path'],
                'category_id' => $category_id,
                'subcategory_id' => $subcategory_id,
                'status' => $status
            ]);

            $product_id = $db->lastInsertId();
            $product->setId($product_id);
            
            $product->description = $description;
            $product->price = $price;
            $product->quantity = $quantity;
            $product->main_image = $main_image['path'];
            $product->category_id = $category_id;
            $product->subcategory_id = $subcategory_id;
            $product->status = $status;

            if (!empty($colors)) {
                foreach ($colors as $color_id) {
                    $product->addColor($db, $color_id);
                }
            }

            $db->commit();
            return $product;
        } catch(PDOException $ex){
            $db->rollBack();
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return null;
        }
    }

    // Add product image
    public static function addImage(PDO $db, $id, array $image_path, string $product_name): bool {
        try {
            $upload_dir = "Public/assets/front/img/product/".$product_name;
            
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $uploaded_file = MangesFiles::UploadFile($image_path, ['jpg','png','jpeg'], $upload_dir);
            if(!$uploaded_file){
                return false;
            }

            $query = "INSERT INTO product_images (product_id, image_name, image_path) 
                     VALUES (:product_id, :image_name, :image_path)";
            $stmt = $db->prepare($query);
            return $stmt->execute([
                'product_id' => $id,
                'image_name' => $image_path['name'],
                'image_path' => $uploaded_file['path']
            ]);
        } catch(Exception $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return false;
        }
    }

    // Get product images
    public function getImages(PDO $db): array {
        try {
            $query = "SELECT image_path FROM product_images WHERE product_id = :product_id";
            $stmt = $db->prepare($query);
            $stmt->execute(['product_id' => $this->id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return [];
        }
    }

    // Get product by ID
    public static function findById(PDO $db, int $id): ?Product {
        try {
            $query = "SELECT * FROM products WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->execute(['id' => $id]);
            
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $product = new Product();
                $product->id = $row['id'];
                $product->name = $row['name'];
                $product->description = $row['description'];
                $product->price = $row['price'];
                $product->quantity = $row['quantity'];
                $product->main_image = $row['main_image'];
                $product->category_id = $row['category_id'];
                $product->subcategory_id = $row['subcategory_id'];
                $product->status = $row['status'];
                $product->created_at = $row['created_at'];
                $product->colors = $product->getColors($db);
                
                return $product;
            }
            return null;
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return null;
        }
    }

    // Get all products
    public static function getAll(PDO $db, int $limit = 0, int $offset = 0): array {
        try {
            $query = "SELECT p.id, p.name, p.description, p.price, p.quantity, 
                            p.main_image, p.category_id, p.subcategory_id, p.status, 
                            p.created_at 
                     FROM products p 
                     ORDER BY p.created_at ASC";
            
            if ($limit > 0) {
                $query .= " LIMIT :limit OFFSET :offset";
            }

            $stmt = $db->prepare($query);
            
            if ($limit > 0) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
                $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
        } catch(PDOException $ex) {
            if(file_exists('Config/log.log')) {
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return [];
        }
    }

    public static function getRandomProducts(PDO $db, int $limit = 3): array {
        try {
            $query = "SELECT * FROM products 
                     WHERE status = 1 
                     ORDER BY RAND() 
                     LIMIT :limit";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'App\\Product');
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return [];
        }
    }
  
    
    public function update(PDO $db, string $name, string $description, float $price, int $quantity, array $main_image, int $category_id, ?int $subcategory_id = null, array $colors = [], int $status = 1): bool {
        try {
            $db->beginTransaction();
            $product = new Product();
            $product->setName($name); 
            
            $main_image = MangesFiles::UploadFile($main_image, ['jpg','png','jpeg'], "Public/assets/front/img/product/{$name}");
            if(!$main_image){
                return false;
            }

            $query = "UPDATE products 
                     SET name = :name, 
                         description = :description, 
                         price = :price,
                         quantity = :quantity, 
                         main_image = :main_image, 
                         category_id = :category_id,
                         subcategory_id = :subcategory_id,
                         status = :status
                     WHERE id = :id";
            
            $stmt = $db->prepare($query);
            $result = $stmt->execute([
                'id' => $this->id,
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'quantity' => $quantity,
                'main_image' => $main_image['path'],
                'category_id' => $category_id,
                'subcategory_id' => $subcategory_id,
                'status' => $status
            ]);

            if ($result && !empty($colors)) {
                $this->clearColors($db);
                foreach ($colors as $color_id) {
                    $this->addColor($db, $color_id);
                }
            }

            $db->commit();
            return $result;
        } catch(PDOException $ex){
            $db->rollBack();
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return false;
        }
    }

    // Delete product
    public function delete(PDO $db): bool {
        try {
            $db->beginTransaction();


            $query = "DELETE FROM product_images WHERE product_id = :id";
            $stmt = $db->prepare($query);
            $stmt->execute(['id' => $this->id]);

            $query = "DELETE FROM products WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->execute(['id' => $this->id]);

            $db->commit();
            return true;
        } catch(PDOException $ex){
            $db->rollBack();
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return false;
        }
    }

    // Get final price with offer discount
    public function getFinalPrice(PDO $db): float {
        try {
            $query = "SELECT discount_percentage FROM offers 
                     WHERE product_id = :product_id 
                     AND status = 1 
                     AND start_date <= NOW() 
                     AND end_date >= NOW() 
                     ORDER BY created_at DESC LIMIT 1";
            $stmt = $db->prepare($query);
            $stmt->execute(['product_id' => $this->id]);
            
            if ($offer = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return $this->price - ($this->price * ($offer['discount_percentage'] / 100));
            }
            return $this->price;
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return $this->price;
        }
    }

    // Get discount
    public function getDiscount(PDO $db): float {
        try {
            $query = "SELECT discount_percentage FROM offers 
                     WHERE product_id = :product_id 
                     AND status = 1 
                     AND start_date <= NOW() 
                     AND end_date >= NOW() 
                     ORDER BY created_at DESC LIMIT 1";
            $stmt = $db->prepare($query);
            $stmt->execute(['product_id' => $this->id]);
            
            if ($offer = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return (float)$offer['discount_percentage'];
            }
            return 0.0;
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return 0.0;
        }
    }

    // Getters
    public function getId(): ?string {
        return $this->id;
    }

    public function setId(string $id): void {
        $this->id = $id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getCreatedAt(): string {
        return $this->created_at;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function getQuantity(): int {
        return $this->quantity;
    }

    public function getMainImage(): string {
        return $this->main_image;
    }

    public function getCategoryId(): int {
        return $this->category_id;
    }

    public function getSubcategoryId(): ?int {
        return $this->subcategory_id;
    }

    public function getStatus(): int {
        return $this->status;
    }



    public function getSubcategory(PDO $db): ?array {
        try {
            $query = "SELECT s.* FROM subcategories s 
                     WHERE s.id = :subcategory_id";
            $stmt = $db->prepare($query);
            $stmt->execute(['subcategory_id' => $this->subcategory_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return null;
        }
    }

 
    public function getColors(PDO $db): array {
        try {
            $query = "SELECT c.* FROM colors c 
                     INNER JOIN product_colors pc ON c.id = pc.color_id 
                     WHERE pc.product_id = :product_id";
            $stmt = $db->prepare($query);
            $stmt->execute(['product_id' => $this->id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return [];
        }
    }


    public function addColor(PDO $db, int $color_id): bool {
        try {
            $query = "INSERT INTO product_colors (product_id, color_id) 
                     VALUES (:product_id, :color_id)";
            $stmt = $db->prepare($query);
            return $stmt->execute([
                'product_id' => $this->id,
                'color_id' => $color_id
            ]);
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return false;
        }
    }

    public function removeColor(PDO $db, int $color_id): bool {
        try {
            $query = "DELETE FROM product_colors 
                     WHERE product_id = :product_id AND color_id = :color_id";
            $stmt = $db->prepare($query);
            return $stmt->execute([
                'product_id' => $this->id,
                'color_id' => $color_id
            ]);
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return false;
        }
    }


    public function clearColors(PDO $db): bool {
        try {
            $query = "DELETE FROM product_colors WHERE product_id = :product_id";
            $stmt = $db->prepare($query);
            return $stmt->execute(['product_id' => $this->id]);
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return false;
        }
    }


    public function getColorsList(): array {
        return $this->colors;
    }


    public static function find_by_name(PDO $db, string $name): array
    {
        try {
            $query = "SELECT * FROM products 
                     WHERE status = 1 
                     AND name LIKE :name 
                     ORDER BY created_at DESC";
            
            $stmt = $db->prepare($query);
            $searchTerm = "%{$name}%";
            $stmt->bindParam(':name', $searchTerm);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'App\\Product');
        } catch(PDOException $ex){
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return [];
        }
    }

    // Getters and Setters
    public function setName(string $name): void {
        $this->name = $name;
    }

    public static function getCount(PDO $db): int {
        try {
            $query = "SELECT COUNT(*) as total FROM products";
            $stmt = $db->prepare($query);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['total'];
        } catch(PDOException $ex) {
            if(file_exists('Config/log.log')) {
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return 0;
        }
    }

    public function updateQuantity(PDO $db, int $new_quantity): bool {
        try {
            $db->beginTransaction();
            
            $query = "UPDATE products 
                     SET quantity = :quantity 
                     WHERE id = :id";
            
            $stmt = $db->prepare($query);
            $result = $stmt->execute([
                'id' => $this->id,
                'quantity' => $new_quantity
            ]);

            $db->commit();
            return $result;
        } catch(PDOException $ex){
            $db->rollBack();
            if(file_exists('Config/log.log')){
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return false;
        }
    }

    public static function deleteProductImages(PDO $db, int $product_id): bool {
        try {

            $db->beginTransaction();

  
            $query = "SELECT image_path FROM product_images WHERE product_id = :product_id";
            $stmt = $db->prepare($query);
            $stmt->execute(['product_id' => $product_id]);
            $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($images as $image) {
                if (file_exists($image['image_path'])) {
                    unlink($image['image_path']);
                }
            }

            
            $query = "DELETE FROM product_images WHERE product_id = :product_id";
            $stmt = $db->prepare($query);
            $stmt->execute(['product_id' => $product_id]);

           
            $db->commit();
            return true;

        } catch(Exception $ex) {
    
            $db->rollBack();
            
      
            if(file_exists('Config/log.log')) {
                $error = date('Y-m-d H:i:s') . " - " . $ex->getMessage() . "\n";
                file_put_contents('Config/log.log', $error, FILE_APPEND);
            }
            return false;
        }
    }
}
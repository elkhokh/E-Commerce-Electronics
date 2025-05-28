<?php

namespace App;
use App\Traits\MangesFiles;
use PDO;

class Product {
    private int $id;
    private string $name;
    private string $description;
    private float $price;
    private float $discount;
    private int $quantity;
    private string $main_image;
    private ?int $category_id;
    private ?int $subcategory_id;
    private int $status;
    private array $colors = [];

    public function __construct() {
    }

    // Create new product
    public static function create(PDO $db, string $name, string $description, float $price, float $discount, int $quantity, array $main_image, int $category_id, ?int $subcategory_id = null, array $colors = []): ?Product {
        try {
            $db->beginTransaction();
            $main_image = MangesFiles::UploadFile($main_image);
            if(!$main_image){
                return null;
            }

            $query = "INSERT INTO products (name, description, price, discount, quantity, main_image, category_id, subcategory_id) 
                     VALUES (:name, :description, :price, :discount, :quantity, :main_image, :category_id, :subcategory_id)";
            
            $stmt = $db->prepare($query);
            $stmt->execute([
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'discount' => $discount,
                'quantity' => $quantity,
                'main_image' => $main_image['path'],
                'category_id' => $category_id,
                'subcategory_id' => $subcategory_id
            ]);

            $product = new Product();
            $product->id = $db->lastInsertId();
            $product->name = $name;
            $product->description = $description;
            $product->price = $price;
            $product->discount = $discount;
            $product->quantity = $quantity;
            $product->main_image = $main_image['path'];
            $product->category_id = $category_id;
            $product->subcategory_id = $subcategory_id;
            $product->status = 1;

            // إضافة الألوان للمنتج
            if (!empty($colors)) {
                foreach ($colors as $color_id) {
                    $product->addColor($db, $color_id);
                }
            }

            $db->commit();
            return $product;
        } catch (\PDOException $e) {
            $db->rollBack();
            return null;
        }
    }

    // Add product image
    public function addImage(PDO $db, array $image_path): bool {
        try {
            $image_path=MangesFiles::UploadFile($image_path);
            if(!$image_path){
                return false;
            }
            $query = "INSERT INTO product_images (product_id, image_path) VALUES (:product_id, :image_path)";
            $stmt = $db->prepare($query);
            return $stmt->execute([
                'product_id' => $this->id,
                'image_path' => $image_path['path']
            ]);
        } catch (\PDOException $e) {
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
        } catch (\PDOException $e) {
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
                $product->discount = $row['discount'];
                $product->quantity = $row['quantity'];
                $product->main_image = $row['main_image'];
                $product->category_id = $row['category_id'];
                $product->subcategory_id = $row['subcategory_id'];
                $product->status = $row['status'];
                $product->colors = $product->getColors($db);
                
                return $product;
            }
            return null;
        } catch (\PDOException $e) {
            return null;
        }
    }

    // Get all products
    public static function getAll(PDO $db): array {
        try {
            $query = "SELECT * FROM products  WHERE status = 1 ";
            $stmt = $db->query($query);
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'App\\Product');
        } catch (\PDOException $e) {
            return [];
        }
    }

    public static function get_three_product(PDO $db, int $limit = 3): array {
    try {
        $query = "SELECT * FROM products WHERE status = 1 ORDER BY RAND() LIMIT :limit";
        $stmt = $db->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'App\\Product');
    } catch (\PDOException $e) {
        return [];
    }
}

    // Update product
    public function update(PDO $db, string $name, string $description, float $price, float $discount, int $quantity, string $main_image, int $category_id, ?int $subcategory_id = null, array $colors = []): bool {
        try {
            $db->beginTransaction();

            $query = "UPDATE products 
                     SET name = :name, 
                         description = :description, 
                         price = :price,
                         discount = :discount,
                         quantity = :quantity, 
                         main_image = :main_image, 
                         category_id = :category_id,
                         subcategory_id = :subcategory_id
                     WHERE id = :id";
            
            $stmt = $db->prepare($query);
            $result = $stmt->execute([
                'id' => $this->id,
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'discount' => $discount,
                'quantity' => $quantity,
                'main_image' => $main_image,
                'category_id' => $category_id,
                'subcategory_id' => $subcategory_id
            ]);

            if ($result && !empty($colors)) {
               
                $this->clearColors($db);
                foreach ($colors as $color_id) {
                    $this->addColor($db, $color_id);
                }
            }

            $db->commit();
            return $result;
        } catch (\PDOException $e) {
            $db->rollBack();
            return false;
        }
    }

    // Delete product
    public function delete(PDO $db): bool {
        try {
            $db->beginTransaction();

            // Delete product images first
            $query = "DELETE FROM product_images WHERE product_id = :id";
            $stmt = $db->prepare($query);
            $stmt->execute(['id' => $this->id]);

            // Then delete the product
            $query = "DELETE FROM products WHERE id = :id";
            $stmt = $db->prepare($query);
            $stmt->execute(['id' => $this->id]);

            $db->commit();
            return true;
        } catch (\PDOException $e) {
            $db->rollBack();
            return false;
        }
    }

    // Calculate final price after discount
    public function getFinalPrice(): float {
        return $this->price - ($this->price * ($this->discount / 100));
    }

    // Getters
    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getPrice(): float {
        return $this->price;
    }

    public function getDiscount(): float {
        return $this->discount;
    }

    public function getQuantity(): int {
        return $this->quantity;
    }

    public function getMainImage(): string {
        return $this->main_image;
    }

    public function getCategoryId(): ?int {
        return $this->category_id;
    }

    public function getSubcategoryId(): ?int {
        return $this->subcategory_id;
    }

    public function getStatus(): int {
        return $this->status;
    }

    public function getCategory(PDO $db): ?array {
        try {
            $query = "SELECT c.* FROM categories c 
                     WHERE c.id = :category_id";
            $stmt = $db->prepare($query);
            $stmt->execute(['category_id' => $this->category_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            return null;
        }
    }

    public function getSubcategory(PDO $db): ?array {
        try {
            $query = "SELECT s.* FROM subcategories s 
                     WHERE s.id = :subcategory_id";
            $stmt = $db->prepare($query);
            $stmt->execute(['subcategory_id' => $this->subcategory_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
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
        } catch (\PDOException $e) {
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
        } catch (\PDOException $e) {
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
        } catch (\PDOException $e) {
            return false;
        }
    }


    public function clearColors(PDO $db): bool {
        try {
            $query = "DELETE FROM product_colors WHERE product_id = :product_id";
            $stmt = $db->prepare($query);
            return $stmt->execute(['product_id' => $this->id]);
        } catch (\PDOException $e) {
            return false;
        }
    }


    public function getColorsList(): array {
        return $this->colors;
    }
}

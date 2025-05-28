<?php

namespace App;

use PDO;

class Cart {
    private int $user_id;
    private array $items = [];
    private float $total = 0;
    private float $discount = 0;
    private float $final_total = 0;

    public function __construct(int $user_id) {
        $this->user_id = $user_id;
    }


    public function load(PDO $db): bool {
        try {
            $this->items = CartItem::getUserCart($db, $this->user_id);
            $this->calculateTotals();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    public function addItem(PDO $db, int $product_id, int $quantity = 1): bool {
        try {
     
            foreach ($this->items as $item) {
                if ($item->getProductId() == $product_id) {
                    return $item->updateQuantity($db, $item->getQuantity() + $quantity);
                }
            }

            
            $cartItem = CartItem::create($db, $this->user_id, $product_id, $quantity);
            if ($cartItem) {
                $this->items[] = $cartItem;
                $this->calculateTotals();
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

  
    public function updateItemQuantity(PDO $db, int $product_id, int $quantity): bool {
        try {
            foreach ($this->items as $item) {
                if ($item->getProductId() == $product_id) {
                    if ($item->updateQuantity($db, $quantity)) {
                        $this->calculateTotals();
                        return true;
                    }
                    return false;
                }
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }


    public function removeItem(PDO $db, int $product_id): bool {
        try {
            foreach ($this->items as $key => $item) {
                if ($item->getProductId() == $product_id) {
                    if ($item->delete($db)) {
                        unset($this->items[$key]);
                        $this->items = array_values($this->items);
                        $this->calculateTotals();
                        return true;
                    }
                    return false;
                }
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function clear(PDO $db): bool {
        try {
            foreach ($this->items as $item) {
                if (!$item->delete($db)) {
                    return false;
                }
            }
            $this->items = [];
            $this->calculateTotals();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function calculateTotals(): void {
        $this->total = 0;
        foreach ($this->items as $item) {
            $this->total += $item->getTotalPrice();
        }
        $this->final_total = $this->total - $this->discount;
    }


    public function applyDiscount(float $discount): void {
        $this->discount = $discount;
        $this->calculateTotals();
    }


    public function removeDiscount(): void {
        $this->discount = 0;
        $this->calculateTotals();
    }

  
    public function hasItem(int $product_id): bool {
        foreach ($this->items as $item) {
            if ($item->getProductId() == $product_id) {
                return true;
            }
        }
        return false;
    }


    public function getItemQuantity(int $product_id): int {
        foreach ($this->items as $item) {
            if ($item->getProductId() == $product_id) {
                return $item->getQuantity();
            }
        }
        return 0;
    }

    // Getters
    public function getUserId(): int {
        return $this->user_id;
    }

    public function getItems(): array {
        return $this->items;
    }

    public function getTotal(): float {
        return $this->total;
    }

    public function getDiscount(): float {
        return $this->discount;
    }

    public function getFinalTotal(): float {
        return $this->final_total;
    }

    public function getItemsCount(): int {
        return count($this->items);
    }
}

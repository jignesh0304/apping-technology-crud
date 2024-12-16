<?php
namespace App\Repositories\Contracts;

interface ProductRepositoryInterface {
    public function getAllProducts();
    public function storeProduct(array $data);
    public function updateProduct($id, array $data);
    public function deleteProduct($id);
}

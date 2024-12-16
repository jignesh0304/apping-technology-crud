<?php

namespace App\Repositories\Contracts;

interface CategoryRepositoryInterface {
    public function getAllCategoriesWithProductCount();
    public function storeCategory(array $data);
    public function updateCategory($id, array $data);
    public function deleteCategory($id);
}

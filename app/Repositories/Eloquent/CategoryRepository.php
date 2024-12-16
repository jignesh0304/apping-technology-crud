<?php
namespace App\Repositories\Eloquent;

use App\Models\Category;

class CategoryRepository {

    public function getData($request)
    {

        $columns = ['id', 'name', 'products_count'];
        $searchValue = $request['search']['value'];
        $start = $request['start'];
        $length = $request['length'];
        $orderColumnIndex = $request['order'][0]['column'];
        $orderDirection = $request['order'][0]['dir'];

        $query = Category::withCount('products');

        if ($searchValue) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'LIKE', "%{$searchValue}%")
                  ->orWhere('id', 'LIKE', "%{$searchValue}%");
            });
        }

        $query->orderBy($columns[$orderColumnIndex], $orderDirection);

        $categories = $query->skip($start)->take($length)->get();

        $data = $categories->map(function ($category) {
            $EditRoute =  route('categories.edit-data',$category->id);
            return [
                'id' => $category->id,
                'name' => $category->name,
                'products_count' => $category->products->count(), // Include products count
                'actions' => '
                    <td>
                        <button class="btn btn-success btn-sm edit-category" onclick="openAddEditCategoryPopup('.$category->id.')">
                            Edit
                        </button>
                        <button type="submit" class="btn btn-danger btn-sm delete-category" onclick="siteCrudDelete(1,'.$category->id.')">Delete</button>
                    </td>
                ',
            ];
        });

        $totalRecords = Category::count();

        $filteredRecords = $query->count();

        return [
            'data' => $data,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
        ];
    }
    public function getEditData($id) {
        return Category::where('id',$id)->first();
    }
    public function getAllCategoriesWithProductCount() {
        return Category::withCount('products')->paginate(10);
    }

    public function storeCategory(array $data) {
        return Category::create($data);
    }

    public function updateCategory($id, array $data) {
        $category = Category::find($id);
        if ($category) {
            return $category->update($data);
        }
        return false;
    }

    public function deleteCategory($id) {
        return Category::destroy($id);
    }
    public function getAllCategories()
    {
        return Category::all();
    }
}

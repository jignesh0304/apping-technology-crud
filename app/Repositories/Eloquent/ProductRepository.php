<?php
namespace App\Repositories\Eloquent;

use App\Models\Product;

class ProductRepository {
    public function getData($request)
    {
        $columns = ['id', 'name', 'category_name','price'];
        $searchValue = $request['search']['value'];
        $start = $request['start'];
        $length = $request['length'];
        $orderColumnIndex = $request['order'][0]['column'];
        $orderDirection = $request['order'][0]['dir'];

        $query = Product::with('category');

        if ($searchValue) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('products.name', 'LIKE', "%{$searchValue}%")
                  ->orWhere('products.price', 'LIKE', "%{$searchValue}%")
                  ->orWhereHas('category', function ($query) use ($searchValue) {
                      $query->where('categories.name', 'LIKE', "%{$searchValue}%");
                  });
            });
        }

        if ($orderColumnIndex == 2) {
            $query->join('categories', 'products.category_id', '=', 'categories.id')
                  ->orderBy('categories.name', $orderDirection);
        } else {
            $query->orderBy($columns[$orderColumnIndex], $orderDirection);
        }
        $products = $query->skip($start)->take($length)->get();

        $data = $products->map(function ($product) {
            $EditRoute =  route('products.edit-data',$product->id);
            return [
                'id' => $product->id,
                'name' => $product->name,
                'category_name' => $product->category->name,
                'price' => $product->price,
                'actions' => '
                    <td>
                        <button class="btn btn-success btn-sm edit-category" onclick="openAddEditProductPopup('.$product->id.')">
                            Edit
                        </button>
                        <button type="submit" class="btn btn-danger btn-sm delete-category" onclick="siteCrudDelete(2,'.$product->id.')">Delete</button>
                    </td>
                ',
            ];
        });

        $totalRecords = Product::count();
        $filteredRecords = $query->count();

        return [
            'data' => $data,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
        ];
    }
    public function getEditData($id) {
        return Product::where('id',$id)->first();
    }
    public function storeProduct(array $data) {
        return Product::create($data);
    }

    public function updateProduct($id, array $data) {
        $product = Product::find($id);
        if ($product) {
            return $product->update($data);
        }
        return false;
    }

    public function deleteProduct($id) {
        return Product::destroy($id);
    }
}

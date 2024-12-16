<?php

namespace App\Http\Controllers;

use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\Eloquent\CategoryRepository;

use Illuminate\Http\Request;
use App\Models\Product;
use Validator;

class ProductController extends Controller
{
    protected $productRepository;
    protected $categoryRepository;


    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;

    }

    public function index() {
        $categories = $this->categoryRepository->getAllCategories();

        return view('products.index',compact('categories'));
    }

    public function getData(Request $request){
        $reqData = $request->all();

        $data = $this->productRepository->getData($reqData);

        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $data['recordsTotal'],
            'recordsFiltered' => $data['recordsFiltered'],
            'data' => $data['data'],
        ]);
    }
    public function getEditData(Request $request){

        return response()->json([
            'data' => $this->productRepository->getEditData($request->id),
        ]);
    }

    public function store(Request $request) {
        $id = $request->input('productId');
        $rules = [
            'name' => 'required|max:255|unique:products,name,' . $id,
            'category_id' => 'required',
            'price' => 'required|numeric',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false,'message' => $validator->errors()->first()], 200);
        }
        if ($id) {
            $result = $this->productRepository->updateProduct($id, $request->all());
            $message = 'Product updated successfully';
        } else {
            $result = $this->productRepository->storeProduct($request->all());
            $message = 'Product added successfully';
        }

        if ($result) {
            return response()->json(['success' => true,'message' => $message], 200);
        } else {
            return response()->json(['success' => false,'message' => 'An error occurred'], 500);
        }
    }
    public function destroy(Request $request){
        try {
            $result = $this->productRepository->deleteProduct($request->id);
            if ($result) {
                return response()->json(['success' => true, 'message' => 'Product deleted successfully.'], 200);
            }
            return response()->json(['success' => false, 'message' => 'Error deleting product.'], 500);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting product.'], 500);
        }
    }
}

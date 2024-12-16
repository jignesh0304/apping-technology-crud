<?php

namespace App\Http\Controllers;

use App\Repositories\Eloquent\CategoryRepository;
use Illuminate\Http\Request;
use App\Models\Category;
use Validator;


class CategoryController extends Controller
{
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }

    public function index() {
        return view('categories.index');
    }

    public function getData(Request $request){
        $reqData = $request->all();

        $data = $this->categoryRepository->getData($reqData);

        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $data['recordsTotal'],
            'recordsFiltered' => $data['recordsFiltered'],
            'data' => $data['data'],
        ]);
    }
    public function getEditData(Request $request){

        return response()->json([
            'data' => $this->categoryRepository->getEditData($request->id),
        ]);
    }

    public function store(Request $request) {
        $id = $request->input('categoryId');
        $rules = [
            'name' => 'required|max:255|unique:categories,name,' . $id,
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['success' => false,'message' => $validator->errors()->first()], 200);
        }

        if ($id) {

            $result = $this->categoryRepository->updateCategory($id, $request->all());
            $message = 'Category updated successfully';
        } else {

            $result = $this->categoryRepository->storeCategory($request->all());
            $message = 'Category added successfully';
        }

        if ($result) {
            return response()->json(['success' => true,'message' => $message], 200);
        } else {
            return response()->json(['success' => false,'message' => 'An error occurred'], 500);
        }
    }
    public function destroy(Request $request){
        try {
            $result = $this->categoryRepository->deleteCategory($request->id);
            if ($result) {
                return response()->json(['success' => true, 'message' => 'Category deleted successfully.'], 200);
            }
            return response()->json(['success' => false, 'message' => 'Error deleting category.'], 500);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting category.'], 500);
        }
    }
}

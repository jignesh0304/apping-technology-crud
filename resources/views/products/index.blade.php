@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h2>Products</h2>
        <button class="btn btn-primary" onclick="openAddEditProductPopup()">Add Product</button>
    </div>

    <table id="productsTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category Name</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addEditProductModal" tabindex="-1" aria-labelledby="addEditProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('products.store') }}" method="POST" onsubmit="return false;" data-parsley-validate>
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addEditProductModalLabel">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="categoryId" class="form-label">Category</label>
                        <select name="category_id" class="form-control" id="categoryId" required>
                            <option value="">Please select</option>
                            @foreach ($categories as $cat)
                                <option value="{{$cat->id}}"> {{$cat->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="productName" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="text" name="price" class="form-control" id="productPrice" required
                        data-parsley-required="true"
                        data-parsley-type="digits"
                        data-parsley-min="0"
                        data-parsley-error-message="Please enter a valid price"
                        >
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" onclick="siteCrudFormModalSubmit(jQuery(this).closest('form'), 'productTable')">Save</button>
                </div>
                <input type="hidden" name='productId' id='productId' value="" />
            </form>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Category Name</label>
                        <input type="text" name="name" id="edit-name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        var productsTable;

        $(document).ready(function() {
            productsTable = $('#productsTable').DataTable({
                processing: true,  // Show loading indicator
                serverSide: true,  // Enable server-side processing
                ajax: {
                    url: '{{ route('products.data') }}',  // URL for the data
                    type: 'GET',
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'category_name', name: 'category_name' },
                    { data: 'price', name: 'price' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ]
            });
        });


    </script>
@endpush

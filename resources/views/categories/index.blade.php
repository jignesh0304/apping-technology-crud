@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h2>Categories</h2>
        <button class="btn btn-primary" onclick="openAddEditCategoryPopup()">Add Category</button>
    </div>

    <table id="categoriesTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Products Count</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addEditCategoryModal" tabindex="-1" aria-labelledby="addEditCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('categories.store') }}" method="POST" onsubmit="return false;" data-parsley-validate>
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addEditCategoryModalLabel">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" name="name" class="form-control" id="categoryName" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" onclick="siteCrudFormModalSubmit(jQuery(this).closest('form'), 'categoryTable')">Save</button>
                </div>
                <input type="hidden" name='categoryId' id='categoryId' value="" />
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
        var categoriesTable;

        $(document).ready(function() {
            categoriesTable = $('#categoriesTable').DataTable({
                processing: true,  // Show loading indicator
                serverSide: true,  // Enable server-side processing
                ajax: {
                    url: '{{ route('categories.data') }}',  // URL for the data
                    type: 'GET',
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'products_count', name: 'products_count' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ]
            });
        });


    </script>
@endpush

function openAddEditCategoryPopup(id) {
    jQuery('#categoryId').val(id ? id : 0)
    jQuery('#addEditCategoryModalLabel').html( (id ? 'Edit' : 'Add') + ' Category' )
    jQuery('#categoryName').val('')

    if(id){
        jQuery.ajax({
            type: "GET",
            url: editCategoryRoute +'?id='+id,
            contentType: false,
            processData: false,
            success: function (res) {
                if(res.data){
                    jQuery('#addEditCategoryModal').modal('show')
                    jQuery('#categoryName').val(res.data.name)
                }
            },
            error: function (err) {
                console.log('error res',err);
            }
        })
    }else{
        jQuery('#addEditCategoryModal').modal('show')

    }
}
function openAddEditProductPopup(id) {
    jQuery('#productId').val(id ? id : 0)
    jQuery('#addEditProductModalLabel').html( (id ? 'Edit' : 'Add') + ' Product' )
    jQuery('#categoryId').val('')
    jQuery('#productName').val('')
    jQuery('#productPrice').val('')

    if(id){
        jQuery.ajax({
            type: "GET",
            url: editProductsRoute +'?id='+id,
            contentType: false,
            processData: false,
            success: function (res) {
                if(res.data){
                    jQuery('#addEditProductModal').modal('show')
                    $('#categoryId').val(res.data.category_id);  // Change to "Fashion" (ID = 2)
                    // jQuery('#categoryId').val(res.data.name)
                    jQuery('#productName').val(res.data.name)
                    jQuery('#productPrice').val(res.data.price)

                }
            },
            error: function (err) {
                console.log('error res',err);
            }
        })
    }else{
        jQuery('#addEditProductModal').modal('show')

    }

}

function siteCrudFormModalSubmit(form, formType) {
    form = jQuery(form);
    if (form.parsley().validate()) {
        jQuery.ajax({
            type: "POST",
            url: form.attr('action'),
            data: new FormData(form[0]),
            contentType: false,
            processData: false,
            success: function (res) {
                if (res.success) {
                    if(formType == 'categoryTable'){
                        jQuery('#addEditCategoryModal').modal('hide');
                        if (typeof categoriesTable !== 'undefined') {
                            categoriesTable.ajax.reload(null, false); // Reload data without resetting pagination
                        }
                    }
                    if(formType == 'productTable'){
                        jQuery('#addEditProductModal').modal('hide');
                        if (typeof productsTable !== 'undefined') {
                            productsTable.ajax.reload(null, false); // Reload data without resetting pagination
                        }
                    }
                }else{
                    alert(res.message || "Success!");
                }
            }
        })
    }
}
function siteCrudDelete(type,id) {
    if (confirm('Are you sure you want to delete this record?')) {
        var url;
        if (type == 1) {
            url = deleteCategoryRoute
        }
        if (type == 2) {
            url = deleteProductsRoute
        }
        jQuery.ajax({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content'),
            },
            type: 'POST',
            url: url,
            data: {id:id},
            success: function (res) {
                console.log('resresresres',res);

                if (type == 1) {
                    if (typeof categoriesTable !== 'undefined') {
                        categoriesTable.ajax.reload(null, false); // Reload data without resetting pagination
                    }
                }
                if (type == 2) {
                    if (typeof productsTable !== 'undefined') {
                        productsTable.ajax.reload(null, false); // Reload data without resetting pagination
                    }
                }


                alert("Success!");

            },
            error: function (err) {
                alert("Error!");

            },
        })
    }
}

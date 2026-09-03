<div class="modal fade" id="faircentApplyModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" >
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Faircent</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" class="save-faircent-form" id="save-faircent-form" enctype="multipart/form-data" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6 mb-3">
                            <label>First Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="first_name" id="first_name" value="" placeholder="First Name">
                            @component('components.ajax-error',['field'=>'first_name'])@endcomponent
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            <label>Last Name<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="last_name" id="last_name" value="" placeholder="Last Name">
                            @component('components.ajax-error',['field'=>'last_name'])@endcomponent
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="category-btn" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- <script>
    $('.save-category-form').submit(function (event) {
        var status = document.activeElement.innerHTML;
        event.preventDefault();
        if (status) {
            $('.ajax-error').html('');
            var data = new FormData(this);
            $.ajax({
                url: $(this).attr("action"),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: data,
                processData: false,
                contentType: false,
                success: function (result) {
                    $(this).attr("disabled", false);
                    if (result.type === 'SUCCESS') {
                        toastr.success(result.message);
                        $('#addCategory').modal('hide');
                        $('#category-table').DataTable().ajax.reload();
                    } else {
                        toastr.error(result.message);
                    }
                },
                error: function (error) {
                    $(this).attr("disabled", false);
                    let errors = error.responseJSON.errors, errorsHtml = '';
                    $.each(errors, function (key, value) {
                        errorsHtml = '<strong>' + value[0] + '</strong>';
                        $('.' + key).html(errorsHtml);
                    });
                }
            });
        }
    });

    // img preview
    $("#cover_image").change(function(){
        const file = this.files[0];
        if (file){
            let reader = new FileReader();
            reader.onload = function(event){
                $('#imgpreview').attr('src', event.target.result);
            }
            reader.readAsDataURL(file);
        }
    });
</script> -->
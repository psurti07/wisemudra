@extends('layouts.customer')
@section('title','Change Password')
@section('breadcrumb','Lorem Ipsum is simply dummy text of the printing and typesetting industry.')
@push('style-css')
@endpush

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="container-xxl " id="kt_content_container">
            <div class="row g-3 g-xl-10 mb-xl-10">
                <div class="col-md-6 col-xl-6 col-lg-6">
                    <div class="card h-md-100">
                        <div class="card-body p-7">
                            <form action="{{ route('customer.profile.update.password') }}" id="changePasswordForm" class="contact-form" novalidate="novalidate" method="post" accept-charset="utf-8">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <input type="password" class="form-control" id="password" placeholder="******" name="password"/>
                                            <label for="password">New Password</label>
                                        </div>
                                        @component('components.ajax-error',['field'=>'password'])@endcomponent
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-floating">
                                            <input type="password" class="form-control" id="password_confirmation" placeholder="******" name="password_confirmation"/>
                                            <label for="password_confirmation">Confirm Password</label>
                                        </div>
                                        @component('components.ajax-error',['field'=>'password_confirmation'])@endcomponent
                                    </div>
                                    <div class="col-md-12 text-end">
                                        <button class="btn btn-success btn-sm hover-elevate-up" type="submit" id="support-submit-btn">Update Password</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-tag')
    <script>
        $(document).ready(function(){
            $('#changePasswordForm').submit(function(){
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
                        beforeSend: function () {
                            $('#support-submit-btn').html('<span class="spinner-border spinner-border-sm"></span> Update Password');
                            $('#support-submit-btn').attr('disabled', true);
                        },
                        success: function (result) {
                            $(this).attr("disabled", false);
                            if (result.type === 'SUCCESS') {
                                toastr.success(`${result.message}`);
                                setTimeout(function () {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                toastr.error(result.message);
                                $('#support-submit-btn').html('Update Password');
                                $('#support-submit-btn').attr('disabled', false);
                            }
                        },
                        error: function (error) {
                            $(this).attr("disabled", false);
                            let errors = error.responseJSON.errors, errorsHtml = '';
                            $.each(errors, function (key, value) {
                                errorsHtml = '<strong>' + value[0] + '</strong>';
                                $('.' + key).html(errorsHtml);
                            });
                            $('#support-submit-btn').html('Update Password');
                            $('#support-submit-btn').attr('disabled', false);
                        }
                    });
                }
            });
        });
    </script>
@endpush

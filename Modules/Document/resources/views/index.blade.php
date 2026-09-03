@extends('layouts.customer')
@section('title','Documents')

@push('style-css')
@endpush

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="container-xxl " id="kt_content_container">
        <div class="row">
            <div class="col-md-6 col-sm-12 col-lg-3 col-xl-3 col-xxl-3" id="renderData"></div>
            <div class="col-md-6 col-sm-12 col-lg-9 col-xl-9 col-xxl-9">
                <div class="card mb-5 mb-xl-8">
                    <div class="card-body">
                        <form action="{{ route('customer.addhar.store') }}" id="addharForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            <h5 class="fw-bold text-uppercase mb-4">Aadhar Card <span class="text-danger">*</span></h5>
                            <div class="mb-3">
                                <input type="text" maxlength="12" minlength="12" name="aadhar_no" id="aadhar_no" value="{{ $addharNumber ?? old('aadhar_no') }}" class="form-control" placeholder="123456789012">
                                @component('components.ajax-error',['field'=>'aadhar_no'])@endcomponent
                            </div>
                            <div class="mb-3 d-flex align-items-center justify-content-between">
                                <input type="file" name="aadhar_image[]" id="aadhar_image" class="form-control w-100 me-2 aadhar_image" multiple>
                                <button type="submit" class="btn btn-primary btn-update">Upload</button>
                            </div>
                            @component('components.ajax-error',['field'=>'aadhar_image'])@endcomponent
                        </form>
                        <h5 class="fw-bold text-uppercase mt-4">Uploaded Aadhaar Documents</h5>
                        <div class="uploaded-images d-flex flex-wrap">
                            @if(!empty($aadharDocs))
                            @foreach($aadharDocs as $aadhar)
                            <div class="uploaded-image me-2 mb-2">
                                <img src="{{ asset($aadhar) }}" alt="Aadhaar Image" class="img-thumbnail" width="150">
                            </div>
                            @endforeach
                            @else
                            <p class="text-muted">No Aadhaar documents uploaded yet.</p>
                            @endif
                        </div>

                        <!-- <div class="uploaded-images d-flex flex-wrap" id="addharDocumentsContainer">
                        </div> -->
                    </div>
                </div>
                <div class="card mb-5 mb-xl-8">
                    <div class="card-body">
                        <form action="{{ route('customer.pan.store') }}" id="panForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            <h5 class="fw-bold text-uppercase mb-4">Pan Card <span class="text-danger">*</span></h5>
                            <div class="mb-3">
                                <input type="text" name="pan_no" id="pan_no" value="{{ $panNum ?? old('pan_no') }}" class="form-control" maxlength="10" minlength="10" placeholder="ABCDE1234F">
                                @component('components.ajax-error',['field'=>'pan_no'])@endcomponent
                            </div>
                            <div class="mb-3 d-flex align-items-center justify-content-between">
                                <input type="file" name="pan_image" id="pan_image" class="form-control w-100 me-2">
                                <button type="submit" class="btn btn-primary btn-update">Upload</button>
                            </div>
                            @component('components.ajax-error',['field'=>'pan_image'])@endcomponent
                        </form>
                        <h5 class="fw-bold text-uppercase mt-4">Uploaded Pancard Documents</h5>
                        <div class="uploaded-images d-flex flex-wrap">
                            @if(!empty($panDocs))
                            <div class="uploaded-image me-2 mb-2">
                                <img src="{{ asset($panDocs) }}" alt="Pan Image" class="img-thumbnail" width="150">
                            </div>
                            @else
                            <p class="text-muted">No Pan documents uploaded yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card mb-5 mb-xl-8">
                    <div class="card-body">
                        <form action="{{ route('customer.bill.store') }}" id="billFrom" method="POST" enctype="multipart/form-data">
                            @csrf
                            <h5 class="fw-bold text-uppercase mb-4">Address Proof - Light bill<span class="text-danger">*</span></h5>
                            <div class="mb-3 d-flex align-items-center justify-content-between">
                                <input type="file" name="light_bill" id="light_bill" class="form-control w-100 me-2">
                                <button type="submit" class="btn btn-primary btn-update">Upload</button>
                            </div>
                            @component('components.ajax-error',['field'=>'light_bill'])@endcomponent
                        </form>
                        <h5 class="fw-bold text-uppercase mt-4">Uploaded Light bill Documents</h5>
                        <div class="uploaded-images d-flex flex-wrap">
                            @if(!empty($lightBillDocs))
                            @php
                            $isPdf = pathinfo($lightBillDocs, PATHINFO_EXTENSION) === 'pdf';
                            @endphp
                            @if($isPdf)
                            <div class="uploaded-file me-2 mb-2">
                                <i class="fas fa-file-pdf text-danger"></i>
                                <span>{{ basename($lightBillDocs) }}</span>
                                <a href="{{ asset($lightBillDocs) }}" class="btn btn-sm btn-link" target="_blank">Download</a>
                            </div>
                            @else
                            <div class="uploaded-image me-2 mb-2">
                                <img src="{{ asset($lightBillDocs) }}" alt="File" class="img-thumbnail" width="150">
                            </div>
                            @endif
                            @else
                            <p class="text-muted">No Light bill documents uploaded yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card mb-5 mb-xl-8">
                    <div class="card-body">
                        <form action="{{ route('customer.cheque.store') }}" id="chequeFrom" method="POST" enctype="multipart/form-data">
                            @csrf
                            <h5 class="fw-bold text-uppercase mb-4">Cancel Cheque<span class="text-danger">*</span></h5>
                            <div class="mb-3 d-flex align-items-center justify-content-between">
                                <input type="file" name="cancel_cheque" id="cancel_cheque" class="form-control w-100 me-2">
                                <button type="submit" class="btn btn-primary btn-update">Upload</button>
                            </div>
                            @component('components.ajax-error',['field'=>'cancel_cheque'])@endcomponent
                        </form>
                        <h5 class="fw-bold text-uppercase mt-4">Uploaded Cancel Cheque Documents</h5>
                        <div class="uploaded-images d-flex flex-wrap">
                            @if(!empty($cancelCheque))
                            @php
                            $isPdf = pathinfo($cancelCheque, PATHINFO_EXTENSION) === 'pdf';
                            @endphp
                            @if($isPdf)
                            <div class="uploaded-file me-2 mb-2">
                                <i class="fas fa-file-pdf text-danger"></i>
                                <span>{{ basename($cancelCheque) }}</span>
                                <a href="{{ asset($cancelCheque) }}" class="btn btn-sm btn-link" target="_blank">Download</a>
                            </div>
                            @else
                            <div class="uploaded-image me-2 mb-2">
                                <img src="{{ asset($cancelCheque) }}" alt="File" class="img-thumbnail" width="150">
                            </div>
                            @endif
                            @else
                            <p class="text-muted">No Cancel Cheque documents uploaded yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card mb-5 mb-xl-8">
                    <div class="card-body">
                        <form action="{{ route('customer.statement.store') }}" id="statementFrom" method="POST" enctype="multipart/form-data">
                            @csrf
                            <h5 class="fw-bold text-uppercase mb-4">Bank Statement - Last 6 months<span class="text-danger">*</span></h5>
                            <div class="mb-3 d-flex align-items-center justify-content-between">
                                <input type="file" name="bank_statement" id="bank_statement" class="form-control w-100 me-2">
                                <button type="submit" class="btn btn-primary btn-update">Upload</button>
                            </div>
                            @component('components.ajax-error',['field'=>'bank_statement'])@endcomponent
                        </form>
                        <h5 class="fw-bold text-uppercase mt-4">Uploaded Bank statment Documents</h5>
                        <div class="uploaded-images d-flex flex-wrap">
                            @if(!empty($bankstatement))
                            @php
                            $isPdf = pathinfo($bankstatement, PATHINFO_EXTENSION) === 'pdf';
                            @endphp
                            @if($isPdf)
                            <div class="uploaded-file me-2 mb-2">
                                <i class="fas fa-file-pdf text-danger"></i>
                                <span>{{ basename($bankstatement) }}</span>
                                <a href="{{ asset($bankstatement) }}" class="btn btn-sm btn-link" target="_blank">Download</a>
                            </div>
                            @else
                            <div class="uploaded-image me-2 mb-2">
                                <img src="{{ asset($bankstatement) }}" alt="File" class="img-thumbnail" width="150">
                            </div>
                            @endif
                            @else
                            <p class="text-muted">No Bank statment documents uploaded yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
                @if($userType == 1)
                <div class="card mb-5 mb-xl-8">
                    <div class="card-body">
                        <form action="{{ route('customer.form16.store') }}" id="sixFrom" method="POST" enctype="multipart/form-data">
                            @csrf
                            <h5 class="fw-bold text-uppercase mb-4">Form 16</h5>
                            <div class="mb-3 d-flex align-items-center justify-content-between">
                                <input type="file" name="formsixteen" id="formsixteen" class="form-control w-100 me-2">
                                <button type="submit" class="btn btn-primary btn-update">Upload</button>
                            </div>
                            @component('components.ajax-error',['field'=>'formsixteen'])@endcomponent
                        </form>
                        <h5 class="fw-bold text-uppercase mt-4">Uploaded Form Sixteen Documents</h5>
                        <div class="uploaded-images d-flex flex-wrap">
                            @if(!empty($formsixteen))
                            @php
                            $isPdf = pathinfo($formsixteen, PATHINFO_EXTENSION) === 'pdf';
                            @endphp
                            @if($isPdf)
                            <div class="uploaded-file me-2 mb-2">
                                <i class="fas fa-file-pdf text-danger"></i>
                                <span>{{ basename($formsixteen) }}</span>
                                <a href="{{ asset($formsixteen) }}" class="btn btn-sm btn-link" target="_blank">Download</a>
                            </div>
                            @else
                            <div class="uploaded-image me-2 mb-2">
                                <img src="{{ asset($formsixteen) }}" alt="File" class="img-thumbnail" width="150">
                            </div>
                            @endif
                            @else
                            <p class="text-muted">No Form 16 documents uploaded yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card mb-5 mb-xl-8">
                    <div class="card-body">
                        <form action="{{ route('customer.salaryslip.store') }}" id="salaryFrom" method="POST" enctype="multipart/form-data">
                            @csrf
                            <h5 class="fw-bold text-uppercase mb-4">Salary Slip</h5>
                            <div class="mb-3 d-flex align-items-center justify-content-between">
                                <input type="file" name="salary_slip" id="salary_slip" class="form-control w-100 me-2">
                                <button type="submit" class="btn btn-primary btn-update">Upload</button>
                            </div>
                            @component('components.ajax-error',['field'=>'salary_slip'])@endcomponent
                        </form>
                        <h5 class="fw-bold text-uppercase mt-4">Uploaded Salary Slip Documents</h5>
                        <div class="uploaded-images d-flex flex-wrap">
                            @if(!empty($salaryslip))
                            @php
                            $isPdf = pathinfo($salaryslip, PATHINFO_EXTENSION) === 'pdf';
                            @endphp
                            @if($isPdf)
                            <div class="uploaded-file me-2 mb-2">
                                <i class="fas fa-file-pdf text-danger"></i>
                                <span>{{ basename($salaryslip) }}</span>
                                <a href="{{ asset($salaryslip) }}" class="btn btn-sm btn-link" target="_blank">Download</a>
                            </div>
                            @else
                            <div class="uploaded-image me-2 mb-2">
                                <img src="{{ asset($salaryslip) }}" alt="File" class="img-thumbnail" width="150">
                            </div>
                            @endif
                            @else
                            <p class="text-muted">No Salary slip documents uploaded yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
                @elseif($userType == 2)
                <div class="card mb-5 mb-xl-8">
                    <div class="card-body">
                        <form action="{{ route('customer.businessProof.store') }}" id="businessProofFrom" method="POST" enctype="multipart/form-data">
                            @csrf
                            <h5 class="fw-bold text-uppercase mb-4">Business Proof</h5>
                            <div class="mb-3 d-flex align-items-center justify-content-between">
                                <input type="file" name="businessproof" id="businessproof" class="form-control w-100 me-2">
                                <button type="submit" class="btn btn-primary btn-update">Upload</button>
                            </div>
                            @component('components.ajax-error',['field'=>'businessproof'])@endcomponent
                        </form>
                        <h5 class="fw-bold text-uppercase mt-4">Uploaded Business Proof Documents</h5>
                        <div class="uploaded-images d-flex flex-wrap">
                            @if(!empty($businessProof))
                            @php
                            $isPdf = pathinfo($businessProof, PATHINFO_EXTENSION) === 'pdf';
                            @endphp
                            @if($isPdf)
                            <div class="uploaded-file me-2 mb-2">
                                <i class="fas fa-file-pdf text-danger"></i>
                                <span>{{ basename($businessProof) }}</span>
                                <a href="{{ asset($businessProof) }}" class="btn btn-sm btn-link" target="_blank">Download</a>
                            </div>
                            @else
                            <div class="uploaded-image me-2 mb-2">
                                <img src="{{ asset($businessProof) }}" alt="File" class="img-thumbnail" width="150">
                            </div>
                            @endif
                            @else
                            <p class="text-muted">No Business proof documents uploaded yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card mb-5 mb-xl-8">
                    <div class="card-body">
                        <form action="{{ route('customer.itReturn.store') }}" id="itReturnFrom" method="POST" enctype="multipart/form-data">
                            @csrf
                            <h5 class="fw-bold text-uppercase mb-4">IT Return</h5>
                            <div class="mb-3 d-flex align-items-center justify-content-between">
                                <input type="file" name="itreturn" id="itreturn" class="form-control w-100 me-2">
                                <button type="submit" class="btn btn-primary btn-update">Upload</button>
                            </div>
                            @component('components.ajax-error',['field'=>'itreturn'])@endcomponent
                        </form>
                        <h5 class="fw-bold text-uppercase mt-4">Uploaded Business Proof Documents</h5>
                        <div class="uploaded-images d-flex flex-wrap">
                            @if(!empty($itReturn))
                            @php
                            $isPdf = pathinfo($itReturn, PATHINFO_EXTENSION) === 'pdf';
                            @endphp
                            @if($isPdf)
                            <div class="uploaded-file me-2 mb-2">
                                <i class="fas fa-file-pdf text-danger"></i>
                                <span>{{ basename($itReturn) }}</span>
                                <a href="{{ asset($itReturn) }}" class="btn btn-sm btn-link" target="_blank">Download</a>
                            </div>
                            @else
                            <div class="uploaded-image me-2 mb-2">
                                <img src="{{ asset($itReturn) }}" alt="File" class="img-thumbnail" width="150">
                            </div>
                            @endif
                            @else
                            <p class="text-muted">No IT Return documents uploaded yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                <div class="card mb-5 mb-xl-8">
                    <div class="card-body">
                        <form action="{{ route('customer.remark.store') }}" id="remarkFrom" method="POST">
                            @csrf
                            <div class="mb-3">
                                <textarea name="remarks" id="remarks" class="form-control w-100 me-2" rows="3" placeholder="Enter your experience here...">{{ $remarks }}</textarea>
                                @component('components.ajax-error',['field'=>'remarks'])@endcomponent
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary w-25 btn-update">Submit</button>
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
    const html = `{!! $html !!}`;
    var token = $('meta[name="csrf-token"]').attr('content');

    function submitForm(form, url, csrfToken) {
        $.ajax({
            url: url,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: new FormData(form[0]),
            processData: false,
            contentType: false,
            // beforeSend: function() {
            //     $('.btn-update').html('<span class="spinner-border spinner-border-sm"></span> Update ');
            //     $('.btn-update').attr('disabled', true);
            // },
            success: function(response) {
                console.log(response, "res");
                console.log(response.html, "res.html");
                if (response.type === 'success') {
                    $('.invalid-feedback').text('');
                    $('.form-control').removeClass('is-invalid');
                    $("button[type='submit']").prop('disabled', false);
                    $('#renderData').html(response.html);
                    $('form')[0].reset();
                    $('form').find('input[type="file"]').val('');
                    toastr.success(response.message);
                    window.location.reload();
                } else {
                    toastr.error(response.message);
                }
                // resetButton();
            },
            error: function(response) {
                console.log(response, "res_Erro");
                $("button[type='submit']").prop('disabled', false);
                if (response.status === 422) {
                    let errors = response.responseJSON.errors;
                    $('.invalid-feedback').text('').hide();
                    $.each(errors, function(key, value) {
                        let errorElement = $(`.ajax-error.${key}`);
                        errorElement.text(value[0]).show();
                    });
                }
                // resetButton();
            }
        });

        // function resetButton() {
        //     $('.btn-update').html('Update');
        //     $('.btn-update').attr('disabled', false);
        // }
    }

    $('#addharForm').on('submit', function(e) {
        e.preventDefault();
        $("button[type='submit']").prop('disabled', true);
        $('.ajax-error').text('').hide();
        var form = $(this);
        var updateUrl = form.attr('action');
        submitForm(form, updateUrl, token);
    });

    $('#panForm').on('submit', function(e) {
        e.preventDefault();
        $("button[type='submit']").prop('disabled', true);
        $('.ajax-error').text('').hide();
        var form = $(this);
        var updateUrl = form.attr('action');
        submitForm(form, updateUrl, token);
    });
    $('#billFrom').on('submit', function(e) {
        e.preventDefault();
        $("button[type='submit']").prop('disabled', true);
        $('.ajax-error').text('').hide();
        var form = $(this);
        var updateUrl = form.attr('action');
        submitForm(form, updateUrl, token);
    });
    $('#chequeFrom').on('submit', function(e) {
        e.preventDefault();
        $("button[type='submit']").prop('disabled', true);
        $('.ajax-error').text('').hide();
        var form = $(this);
        var updateUrl = form.attr('action');
        submitForm(form, updateUrl, token);
    });
    $('#statementFrom').on('submit', function(e) {
        e.preventDefault();
        $("button[type='submit']").prop('disabled', true);
        $('.ajax-error').text('').hide();
        var form = $(this);
        var updateUrl = form.attr('action');
        submitForm(form, updateUrl, token);
    });
    $('#sixFrom').on('submit', function(e) {
        e.preventDefault();
        $("button[type='submit']").prop('disabled', true);
        $('.ajax-error').text('').hide();
        var form = $(this);
        var updateUrl = form.attr('action');
        submitForm(form, updateUrl, token);
    });
    $('#salaryFrom').on('submit', function(e) {
        e.preventDefault();
        $("button[type='submit']").prop('disabled', true);
        $('.ajax-error').text('').hide();
        var form = $(this);
        var updateUrl = form.attr('action');
        submitForm(form, updateUrl, token);
    });
    $('#itReturnFrom').on('submit', function(e) {
        e.preventDefault();
        $("button[type='submit']").prop('disabled', true);
        $('.ajax-error').text('').hide();
        var form = $(this);
        var updateUrl = form.attr('action');
        submitForm(form, updateUrl, token);
    });
    $('#businessProofFrom').on('submit', function(e) {
        e.preventDefault();
        $("button[type='submit']").prop('disabled', true);
        $('.ajax-error').text('').hide();
        var form = $(this);
        var updateUrl = form.attr('action');
        submitForm(form, updateUrl, token);
    });
    $('#remarkFrom').on('submit', function(e) {
        e.preventDefault();
        $("button[type='submit']").prop('disabled', true);
        $('.ajax-error').text('').hide();
        var form = $(this);
        var updateUrl = form.attr('action');
        submitForm(form, updateUrl, token);
    });

    $(document).ready(function() {
        $("#renderData").html(html);
        $("#aadhar_no").on("input", function() {
            this.value = this.value.replace(/[^0-9]/g, "");
        });
    });
</script>
@endpush
@extends('layouts.app')

@section('title', 'Deal Done')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Deal Done</h4>
                            <div class="page-title-right">
                                <a href="{{ route('inquiry.schedule_reschedule_inquirylist') }}"
                                    class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                                    Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="live-preview">
                                    <form method="POST" onsubmit="return validateFile()"
                                        action="{{ route('inquiry.dealdone_update') }}" enctype="multipart/form-data">
                                        @csrf

                                        <input type="hidden" name="invoiceid" value="{{ $invoicId ?? '' }}">
                                        <input type="hidden" name="prefix" value="{{ $prefix ?? '' }}">
                                        <input type="hidden" name="inquiry_id" value="{{ $Inquiries->inquiry_id }}">
                                        <div class="row gy-4">

                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Invoice No<span style="color:red;">*</span>
                                                    <input type="text" class="form-control"
                                                        placeholder="Enter Invoice No" name="invoiceno" id="invoiceno"
                                                        autocomplete="off" value="{{ $invoiceno }}" required readonly>
                                                </div>
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Customer Name <span style="color:red;">*</span>
                                                    <input type="text" class="form-control" placeholder="Enter Name"
                                                        name="customer_name" id="customer_name" autocomplete="off"
                                                        value="{{ $Inquiries->customer_name }}" required>
                                                </div>
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Customer Phone <span style="color:red;">*</span>
                                                    <input type="text" class="form-control"
                                                        placeholder="Enter Designation" name="customer_phone"
                                                        id="customer_phone" autocomplete="off"
                                                        value="{{ $Inquiries->customer_phone }}" required>
                                                </div>
                                                @error('designation')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    IMEI 1 <span style="color:red;">*</span>
                                                    <input type="text" class="form-control" placeholder="Enter IMEI 1"
                                                        name="imei_1" id="imei_1" autocomplete="off"
                                                        value="{{ $Inquiries->imei_1 }}" required>
                                                </div>
                                                @error('imei_1')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    IMEI 2 <span style="color:red;">*</span>
                                                    <input type="text" class="form-control" placeholder="Enter IMEI 2 "
                                                        name="imei_2" id="imei_2" autocomplete="off"
                                                        value="{{ $Inquiries->imei_2 }}" required>
                                                </div>
                                                @error('imei_2')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Brand <span style="color:red;">*</span>
                                                    <input type="text" class="form-control" placeholder="Enter Brand"
                                                        name="brand" id="brand" autocomplete="off"
                                                        value="{{ $Inquiries->brand }}" required>
                                                </div>
                                                @error('brand')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Model <span style="color:red;">*</span>
                                                    <input type="text" class="form-control" placeholder="Enter Brand"
                                                        name="model" id="model" autocomplete="off"
                                                        value="{{ $Inquiries->model }}" required>
                                                </div>
                                                @error('brand')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Address <span style="color:red;">*</span>
                                                    <input type="text" class="form-control"
                                                        placeholder="Enter Address" name="address" id="address"
                                                        autocomplete="off" value="{{ $Inquiries->address }}" required>
                                                </div>
                                                @error('address')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col-lg-4 col-md-6">
                                                <div>
                                                    Actual Amount <span style="color:red;">*</span>
                                                    <input type="text" class="form-control"
                                                        placeholder="Enter Actual Amount" name="actual_amount"
                                                        id="actual_amount" value="{{ $Inquiries->actual_amount }}"
                                                        autocomplete="off" required>
                                                </div>
                                                @error('address')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>



                                        </div>
                                        <div class="card-footer mt-5" style="float: right;">
                                            <button type="submit"
                                                class="btn btn-primary btn-user float-right mb-3 mx-2">Update</button>
                                            <a class="btn btn-primary float-right mr-3 mb-3 mx-2"
                                                href="{{ route('testimonial.index') }}">Cancel</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')

    <script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script>
    <script>
        function validateFile() {
            var allowedExtension = ['jpeg', 'jpg', 'png', 'webp', ''];
            var fileExtension = document.getElementById('strPhoto').value.split('.').pop().toLowerCase();
            var isValidFile = false;
            var image = document.getElementById('strPhoto').value;

            for (var index in allowedExtension) {

                if (fileExtension === allowedExtension[index]) {
                    isValidFile = true;
                    break;
                }
            }
            if (image != "") {
                if (!isValidFile) {
                    alert('Allowed Extensions are : *.' + allowedExtension.join(', *.'));
                }
                return isValidFile;
            }

            return true;
        }
    </script>

    {{-- Add photo --}}
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#hello').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#strPhoto").change(function() {
            html =
                '<img src="' + readURL(this) +
                '"   id="hello" width="70px" height = "70px" > ';
            $('#viewimg').html(html);
        });
    </script>

@endsection

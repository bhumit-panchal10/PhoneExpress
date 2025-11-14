@extends('layouts.app')
@section('title', 'Schedule Inquiry List')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="d-flex justify-content-between card-header">
                                <h5 class="card-title mb-0">Schedule Inquiry List</h5>
                                {{-- <a data-bs-toggle="modal" data-bs-target="#AddModal" class="btn btn-sm btn-primary">
                                    <i data-feather="plus"></i> Add New
                                </a> --}}
                            </div>
                            <div class="card-body">

                                <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th width="1%">No</th>
                                            <th width="5%"> Customer Name</th>
                                            <th width="5%">Customer Phone</th>
                                            <th width="5%">Email</th>
                                            <th width="5%">IMEI 1</th>
                                            <th width="5%">IMEI 2</th>
                                            <th width="5%">Brand</th>
                                            <th width="5%">Model</th>
                                            <th width="5%">Expected Amt</th>
                                            <th width="5%">Address</th>
                                            <th width="5%">Schedule Date</th>
                                            <th width="5%">Schedule Time</th>
                                            <th width="5%">Status</th>
                                            <th width="10%">Pickup Date</th>
                                            <th width="5%">Pickup Time</th>
                                            <th width="5%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; ?>
                                        @foreach ($Inquiries as $Inquiry)
                                            <tr class="text-center">
                                                <td>{{ $i + $Inquiries->perPage() * ($Inquiries->currentPage() - 1) }}
                                                </td>
                                                <td>{{ $Inquiry->customer_name }}</td>
                                                <td>{{ $Inquiry->customer_phone }}</td>
                                                <td>{{ $Inquiry->customer_email }}</td>
                                                <td>{{ $Inquiry->imei_1 }}</td>
                                                <td>{{ $Inquiry->imei_2 }}</td>
                                                <td>{{ $Inquiry->brand }}</td>
                                                <td>{{ $Inquiry->model }}</td>
                                                <td>{{ $Inquiry->expected_amt }}</td>
                                                <td>{{ $Inquiry->address }}</td>
                                                <td>
                                                    @if (!is_null($Inquiry->schedule_date) && $Inquiry->schedule_date != '' && $Inquiry->schedule_date != '0000-00-00')
                                                        {{ date('d-m-Y', strtotime($Inquiry->schedule_date)) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ date('h:i', strtotime($Inquiry->pickup_time)) }}</td>
                                                <td>
                                                    @if ($Inquiry->status == 0)
                                                        Pending
                                                    @elseif($Inquiry->status == 1)
                                                        Scheduled
                                                    @elseif($Inquiry->status == 2)
                                                        Rescheduled
                                                    @elseif($Inquiry->status == 3)
                                                        Cancelled
                                                    @else
                                                        Unknown
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (!is_null($Inquiry->pickup_date) && $Inquiry->pickup_date != '' && $Inquiry->pickup_date != '0000-00-00')
                                                        {{ date('d-m-Y', strtotime($Inquiry->pickup_date)) }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ date('H:i', strtotime($Inquiry->pickup_time)) }}</td>

                                                <td>
                                                    <div class="gap-6">
                                                        <a class="mx-1" title="Schedule"
                                                            href="{{ route('inquiry.schedule_inquirylist', $Inquiry->inquiry_id) }}">
                                                            <i class="fa-regular fa-calendar-days"></i>
                                                        </a>
                                                        <a class=""
                                                            href="{{ route('inquiry.dealdone', $Inquiry->inquiry_id) }}"
                                                            title="deal done">
                                                            <i class="fa-solid fa-circle-check"></i> </a>

                                                        <a class="" href="#" data-bs-toggle="modal"
                                                            title="Cancel Schedule" data-bs-target="#cancelRecordModal"
                                                            onclick="CancelData(<?= $Inquiry->inquiry_id ?>);">
                                                            <i class="fa-solid fa-xmark"></i> </a>

                                                        <a class="" href="#" data-bs-toggle="modal"
                                                            title="Delete" data-bs-target="#deleteRecordModal"
                                                            onclick="deleteData(<?= $Inquiry->inquiry_id ?>);">
                                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                                        </a>

                                                    </div>
                                                </td>
                                            </tr>
                                            <?php $i++; ?>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $Inquiries->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Delete Modal Start -->
                <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                    id="btn-close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mt-2 text-center">
                                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                        colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px">
                                    </lord-icon>
                                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                        <h4>Are you Sure ?</h4>
                                        <p class="text-muted mx-4 mb-0">Are you Sure You want to Remove this Record
                                            ?</p>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                    <a class="btn btn-primary mx-2" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('user-delete-form').submit();">
                                        Yes,
                                        Delete It!
                                    </a>
                                    <button type="button" class="btn w-sm btn-primary mx-2"
                                        data-bs-dismiss="modal">Close</button>
                                    <form id="user-delete-form" method="POST" action="{{ route('inquiry.delete') }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="inquiryid" id="deleteid" value="">

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Delete modal End -->

                <!--Cancel Modal Start -->
                <div class="modal fade zoomIn" id="cancelRecordModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                    id="btn-close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mt-2 text-center">
                                    <lord-icon src="https://cdn.lordicon.com/ebyacdql.json" trigger="loop"
                                        colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px">
                                    </lord-icon>
                                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                        <h4>Are you Sure ?</h4>
                                        <p class="text-muted mx-4 mb-0">Are you Sure You want to Cancel this Schedule
                                            ?</p>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                    <a class="btn btn-primary mx-2" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('cancel-inquiry-form').submit();">
                                        Yes,
                                        Cancel It!
                                    </a>
                                    <button type="button" class="btn w-sm btn-primary mx-2"
                                        data-bs-dismiss="modal">Close</button>
                                    <form id="cancel-inquiry-form" method="POST"
                                        action="{{ route('inquiry.cancel_inquiry') }}">
                                        @csrf
                                        <input type="hidden" name="inquiryid" id="cancelid" value="">

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Cancel Modal End -->

            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        function CancelData(id) {
            $("#cancelid").val(id);
        }
    </script>

    <script>
        function deleteData(id) {
            $("#deleteid").val(id);
        }
    </script>

@endsection

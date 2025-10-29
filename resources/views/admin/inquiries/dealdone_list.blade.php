@extends('layouts.app')
@section('title', 'Deal Done Inquiry List')
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
                                <h5 class="card-title mb-0">Deal Done Inquiry List</h5>
                            </div>
                            <div class="card-body">

                                <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th width="1%">No</th>
                                            <th width="5%"> Customer Name</th>
                                            <th width="5%">Customer Phone</th>
                                            <th width="5%">IMEI 1</th>
                                            <th width="5%">IMEI 2</th>
                                            <th width="5%">Brand</th>
                                            <th width="5%">Model</th>
                                            <th width="5%">Expected Amt</th>
                                            <th width="5%">Actual Amt</th>
                                            <th width="5%">Address</th>

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
                                                <td>{{ $Inquiry->imei_1 }}</td>
                                                <td>{{ $Inquiry->imei_2 }}</td>
                                                <td>{{ $Inquiry->brand }}</td>
                                                <td>{{ $Inquiry->model }}</td>
                                                <td>{{ $Inquiry->expected_amt }}</td>
                                                <td>{{ $Inquiry->actual_amount ?? 0 }}</td>
                                                <td>{{ $Inquiry->address }}</td>

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
            </div>
        </div>
    </div>
@endsection

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
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Deal Done Inquiry List</h5>

                                <form method="GET" action="{{ route('inquiry.dealdone_list') }}"
                                    class="d-flex align-items-end gap-2">
                                    <div>
                                        <label for="from_date" class="form-label mb-0">From Date</label>
                                        <input type="date" name="from_date" id="from_date"
                                            value="{{ request('from_date') }}" class="form-control form-control-sm">
                                    </div>
                                    <div>
                                        <label for="to_date" class="form-label mb-0">To Date</label>
                                        <input type="date" name="to_date" id="to_date" value="{{ request('to_date') }}"
                                            class="form-control form-control-sm">
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-sm btn-primary">Search</button>
                                        <a href="{{ route('inquiry.dealdone_list') }}"
                                            class="btn btn-sm btn-secondary">Reset</a>
                                    </div>
                                    <a href="{{ route('dealdone_list.export', request()->only(['from_date', 'to_date'])) }}"
                                        class="btn btn-sm btn-success" data-bs-toggle="modal"
                                        data-bs-target="#exportPasswordModal">
                                        Export CSV
                                    </a>

                                </form>
                            </div>

                            <div class="card-body">

                                <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th width="1%">No</th>
                                            <th width="5%"> Customer Name</th>
                                            <th width="5%">Customer Phone</th>
                                            <th width="5%">Customer Email</th>
                                            <th width="5%">IMEI 1</th>
                                            <th width="5%">IMEI 2</th>
                                            <th width="5%">Brand</th>
                                            <th width="5%">Model</th>
                                            <th width="5%">Expected Amt</th>
                                            <th width="5%">Actual Amt</th>
                                            <th width="20%">Address</th>
                                            <th width="10%">Schedule Date</th>
                                            <th width="5%">Schedule Time</th>

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
                                                <td>{{ $Inquiry->actual_amount ?? 0 }}</td>
                                                <td>{{ $Inquiry->address }}</td>
                                                <td>{{ date('d-m-Y', strtotime($Inquiry->schedule_date)) }}</td>
                                                <td>{{ date('H:i A', strtotime($Inquiry->schedule_time)) }}</td>

                                            </tr>
                                            <?php $i++; ?>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $Inquiries->appends(request()->all())->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 🔐 Password Modal -->
    <!-- 🔐 Password Modal -->
    <div class="modal fade" id="exportPasswordModal" tabindex="-1" role="dialog"
        aria-labelledby="exportPasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportPasswordModalLabel">Enter Export Password</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group position-relative">
                        <label for="exportPassword">Password</label>
                        <div class="input-group">
                            <input type="password" id="exportPassword" class="form-control" placeholder="Enter password">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <small id="passwordError" class="text-danger d-none">Invalid password</small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmExportBtn">Confirm & Export</button>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const correctPassword = "Admin@123"; // 🔐 Static password
            const confirmExportBtn = document.getElementById('confirmExportBtn');
            const passwordInput = document.getElementById('exportPassword');
            const passwordError = document.getElementById('passwordError');
            const togglePasswordBtn = document.getElementById('togglePassword');
            const exportModal = $('#exportPasswordModal');

            // 👁 Toggle password visibility
            togglePasswordBtn.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);

                // Change icon
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });

            // ✅ Handle export confirmation
            confirmExportBtn.addEventListener('click', function() {
                const enteredPassword = passwordInput.value.trim();

                if (enteredPassword === correctPassword) {
                    passwordError.classList.add('d-none');

                    // Close modal
                    exportModal.modal('hide');

                    // Trigger download
                    const exportUrl = @json(route('dealdone_list.export', request()->only(['from_date', 'to_date'])));
                    window.location.href = exportUrl;
                } else {
                    passwordError.classList.remove('d-none');
                }
            });

            // 🧹 Reset modal on open/close
            exportModal.on('shown.bs.modal', function() {
                passwordInput.value = '';
                passwordError.classList.add('d-none');
                passwordInput.focus();
            });

            exportModal.on('hidden.bs.modal', function() {
                passwordInput.value = '';
                passwordError.classList.add('d-none');
                passwordInput.setAttribute('type', 'password'); // reset to hidden
                const icon = togglePasswordBtn.querySelector('i');
                icon.classList.add('fa-eye');
                icon.classList.remove('fa-eye-slash');
            });
        });
    </script>

@endsection

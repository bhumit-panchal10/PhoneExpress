@extends('layouts.app')
@section('title', 'Year Master')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            {{-- Alert Messages --}}
            @include('common.alert')
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="d-flex justify-content-between card-header">
                            <h5 class="card-title mb-0">Year Master</h5>
                            <a data-bs-toggle="modal" data-bs-target="#AddModal" class="btn btn-sm btn-primary">
                                <i data-feather="plus"></i> Add New
                            </a>
                        </div>

                        <div class="card-body">
                            <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                                <thead>
                                    <tr class="text-center">
                                        <th width="5%">No</th>
                                        <th width="10%">Year</th>
                                        <th width="15%">Prefix</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($datas as $key => $data)
                                        <tr class="text-center">
                                            <td>{{ $key + $datas->firstItem() }}</td>
                                            <td>{{ $data->year }}</td>
                                            <td>{{ $data->prefix }}</td>
                                            <td>
                                                <button class="btn btn-sm {{ $data->iStatus ? 'btn-success' : 'btn-danger' }} toggle-status"
                                                    data-id="{{ $data->id }}">
                                                    {{ $data->iStatus ? 'Active' : 'Inactive' }}
                                                </button>
                                            </td>
                                            <td>
                                                <div class="gap-2">
                                                    <!--<button type="button"-->
                                                    <!--        class="btn btn-sm btn-toggle-status {{ $data->iStatus ? 'btn-success' : 'btn-danger' }}"-->
                                                    <!--        data-id="{{ $data->id }}"-->
                                                    <!--        data-status="{{ $data->iStatus ? 1 : 0 }}">-->
                                                    <!--    {{ $data->iStatus ? 'Active' : 'Inactive' }}-->
                                                    <!--</button>-->
                                                    <a class="mx-1" title="Edit" href="#"
                                                        onclick="getEditData({{ $data->id }})"
                                                        data-bs-toggle="modal" data-bs-target="#EditModal">
                                                        <i class="far fa-edit"></i>
                                                    </a>

                                                    <a class="text-danger" href="#" data-bs-toggle="modal"
                                                        title="Delete" data-bs-target="#deleteRecordModal"
                                                        onclick="deleteData({{ $data->id }});">
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No Data Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="d-flex justify-content-center mt-3">
                                {{ $datas->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================== Add Modal ================== -->
            <div class="modal fade flip" id="AddModal" tabindex="-1" aria-labelledby="AddModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-light p-3">
                            <h5 class="modal-title" id="AddModalLabel">Add Year</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        
                        <form method="POST" action="{{ route('year.store') }}" autocomplete="off">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Year <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="year" placeholder="Enter Year" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Prefix <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="prefix" placeholder="Enter Prefix" required>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Add Modal End -->

            <!-- ================== Edit Modal ================== -->
            <div class="modal fade flip" id="EditModal" tabindex="-1" aria-labelledby="EditModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-light p-3">
                            <h5 class="modal-title" id="EditModalLabel">Edit Year</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <form method="POST" action="{{ route('year.update', 'id') }}" id="editForm" autocomplete="off">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="edit_id">

                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Year <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="year" id="edit_year" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Prefix <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="prefix" id="edit_prefix" required>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Edit Modal End -->

            <!-- ================== Delete Modal ================== -->
            <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px">
                            </lord-icon>
                            <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                <h4>Are you sure?</h4>
                                <p class="text-muted mx-4 mb-0">Do you really want to delete this record?</p>
                            </div>
                            <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                <form id="deleteForm" method="POST" action="{{ route('year.destroy', 'id') }}">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="id" id="delete_id">
                                    <button type="submit" class="btn btn-danger">Yes, Delete It!</button>
                                </form>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Delete Modal End -->

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function getEditData(id) {
        let url = "{{ url('admin/year/get') }}/" + id;
        $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
                $('#edit_id').val(data.id);
                $('#edit_year').val(data.year);
                $('#edit_prefix').val(data.prefix);

                // dynamically set form action
                let action = "{{ route('year.update', ':id') }}";
                action = action.replace(':id', id);
                $('#editForm').attr('action', action);
            }
        });
    }

    function deleteData(id) {
        $('#delete_id').val(id);
        let action = "{{ route('year.destroy', ':id') }}";
        action = action.replace(':id', id);
        $('#deleteForm').attr('action', action);
    }

    // Toggle Status
    $(document).on('click', '.toggle-status', function() {
        const id = $(this).data('id');
        $.ajax({
            url: "{{ url('admin/year-status') }}/" + id,
            type: 'GET',
            success: function(data) {
                if (data.status === 'success') {
                    location.reload();
                }
            }
        });
    });
</script>
<script>
$(document).ready(function() {
    // Make sure CSRF token header is set for POST/PUT if required in your app,
    // although toggle route is GET in example.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Toggle status click handler
    $(document).on('click', '.btn-toggle-status', function(e) {
        e.preventDefault();

        var $btn = $(this);
        var id = $btn.data('id');

        // optional: confirm
        // if (!confirm('Change status for this record?')) return;

        // call named route URL (Laravel)
        var url = "{{ route('year.toggleStatus', ':id') }}";
        url = url.replace(':id', id);

        // show temporary loading state
        $btn.prop('disabled', true).text('Updating...');

        $.ajax({
            url: url,
            type: 'GET',
            success: function(res) {
                if (res.status === 'success') {
                    var newStatus = res.new_status; // expecting 0 or 1 from controller
                    // update button UI
                    if (newStatus == 1) {
                        $btn.removeClass('btn-danger').addClass('btn-success').text('Active');
                        $btn.data('status', 1);
                    } else {
                        $btn.removeClass('btn-success').addClass('btn-danger').text('Inactive');
                        $btn.data('status', 0);
                    }
                } else {
                    alert('Failed to update status.');
                }
            },
            error: function(xhr) {
                console.error(xhr);
                alert('Error updating status.');
            },
            complete: function() {
                $btn.prop('disabled', false);
            }
        });
    });
});
</script>
@endsection

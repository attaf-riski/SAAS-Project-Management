@php
    $title = 'Transaction';
    $pretitle = 'Transactions/list';
@endphp

@extends('admintemplate')

@section('adminbody')
    <div class="row row-deck row-cards">
        {{-- @include('workspace.header')s --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Search Filter</h3>
                </div>
                <div class="card-body border-bottom py-3">
                    <div class="d-flex">
                        <div class="text-muted">
                            Search:
                            <div class="ms-2 d-inline-block">
                                <input type="text" id="search" class="form-control" aria-label="Search Contract"
                                       placeholder="find Transaction by freelancer name...">
                            </div>
                        </div>
                        <div class="ms-auto me-3">
                            <div class="text-muted">
                                Show
                                <div class="mx-2 d-inline-block">
                                    <input type="number" id="data_count_shows" class="form-control" value="5"
                                           size="3" aria-label="Invoices count">
                                </div>
                                entries
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary font-weight-bolder" data-bs-toggle="modal"
                            data-bs-target="#tambah_transaction">
                            New transaction
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable">
                        <thead>
                            <tr>
                                <th class="w-1">No.
                                </th>
                                <th>Freelance</th>
                                <th>Plan</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td><span class="text-muted">{{ $loop->iteration }}</span></td>
                                    <td>{{ $transaction->fullname }}</td>
                                    <td>{{ $transaction->plan_name }}</td>
                                    <td>
                                        @if ($transaction->status == 'PAID')
                                            <span class="badge text-bg-success">{{ $transaction->status }}</span>
                                        @elseif($transaction->status == 'PENDING')
                                            <span class="badge text-bg-warning">{{ $transaction->status }}</span>
                                        @elseif($transaction->status == 'CANCEL')
                                            <span class="badge text-bg-danger">{{ $transaction->status }}</span>
                                        @endif
                                    </td>
                                    <td>@currency($transaction->amount)</td>
                                    <td class="d-flex gap-3">
                                        <a href="#" class="btn btn-secondary" data-bs-toggle="modal"
                                            data-bs-target="#modal_edit-{{ $transaction->id }}">
                                            Edit
                                        </a>
                                        <a href="#" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#modal_hapus-{{ $transaction->id }}">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                                {{-- edit modal --}}
                                <div class="modal modal-blur fade" id="modal_edit-{{ $transaction->id }}" tabindex="-1"
                                    role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <form
                                                action="{{ route('admin.transaction.update', ['id' => $transaction->id]) }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit transaction</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Freelance</label>
                                                        <input type="text" class="form-control" name="id_user"
                                                            value="{{ $transaction->fullname }}" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Plan</label>
                                                        <input type="text" class="form-control" name="id_plan"
                                                            value="{{ $transaction->plan_name }}" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Amount</label>
                                                        <input type="number" class="form-control" name="amount"
                                                            value="{{ $transaction->amount }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Status</label>
                                                        <select class="form-control form-select" name="status">
                                                            <option value="{{ $transaction->status }}">
                                                                {{ $transaction->status }}</option>
                                                            <option value="PENDING">PENDING</option>
                                                            <option value="PAID">PAID</option>
                                                            <option value="FAILED">FAILED</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Created At</label>
                                                        <input type="date" class="form-control" name="date"
                                                            value="{{ $transaction->date }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <a href="#" class="btn btn-link link-secondary"
                                                        data-bs-dismiss="modal">
                                                        Cancel
                                                    </a>
                                                    <button type="submit" class="btn btn-primary mr-2"
                                                        data-bs-dismiss="modal">
                                                        <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon"
                                                            width="24" height="24" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M12 5l0 14" />
                                                            <path d="M5 12l14 0" />
                                                        </svg>
                                                        Edit transaction
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                {{-- modal hapus --}}
                                <div class="modal modal-blur fade" id="modal_hapus-{{ $transaction->id }}"
                                    tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                            <div class="modal-status bg-danger"></div>
                                            <div class="modal-body text-center py-4">
                                                <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon mb-2 text-danger icon-lg" width="24" height="24"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                    fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z" />
                                                    <path d="M12 9v4" />
                                                    <path d="M12 17h.01" />
                                                </svg>
                                                <h3>Are you sure?</h3>
                                                <div class="text-muted">Do you really want to remove the transaction? What
                                                    you've done cannot be undone.</div>
                                            </div>
                                            <div class="modal-footer">
                                                <div class="w-100">
                                                    <div class="row">
                                                        <form
                                                            action="{{ route('admin.transaction.delete', ['id' => $transaction->id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <div class="col"><button type="button" class="btn w-100"
                                                                    data-bs-dismiss="modal">
                                                                    Cancel
                                                                    </a></div>
                                                            <div class="col"><button type="submit"
                                                                    class="btn btn-danger w-100" data-bs-dismiss="modal">
                                                                    Delete transaction
                                                                    </a></div>
                                                        </form>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                </div>
                @endforeach

                </tbody>
                </table>
            </div>
            <div class="card-footer d-flex align-items-center">
                {!! $transactions->appends(Request::except('page'))->links('pagination::bootstrap-5') !!}
            </div>
        </div>
    </div>
    </div>

    {{-- Modal Dialog --}}
    <div class="modal modal-blur fade" id="tambah_transaction" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.transaction.create') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add transaction</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Freelance</label>
                            <select class="form-control form-select" name="id_user" id="id_user">
                                <option value="">Select Freelance</option>
                                @foreach ($freelances as $freelance)
                                    <option value="{{ $freelance->id }}">{{ $freelance->fullname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Id Subscription</label>
                            <select class="form-control form-select" name="id_subscription" id="id_subscription">
                                {{-- show based on the freelance --}}
                                <option value="">Select Id Subscription</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input type="number" class="form-control" name="amount" placeholder="Fill with Amount">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control form-select" name="status">
                                <option value="">Select Status</option>
                                <option value="PENDING">PENDING</option>
                                <option value="PAID">PAID</option>
                                <option value="FAILED">FAILED</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Created At</label>
                            <input type="date" class="form-control" name="date">
                        </div>
                    </div>



                    <div class="modal-footer">
                        <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary mr-2" data-bs-dismiss="modal">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Create new transaction
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#id_user').change(function() {
                var id = $(this).val();
                console.log(id);
                $.ajax({
                    url: "{{ route('admin.transaction.listsubscriptions', ['id' => '+id+']) }}",
                    type: 'GET',
                    data: {
                        id: id
                    },
                    success: function(data) {
                        $.each(data, function(key, value) {
                            $('#id_subscription').append('<option value="' + value.id +
                                '">' + value.id + '</option>');
                        });
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#data_count_shows').on('input',function() {
                var count_shows = $(this).val();
                // update the table and the pagination
                $.ajax({
                    url: "{{ route('admin.transaction.show') }}",
                    type: 'GET',
                    data: {
                        data_count_shows: count_shows
                    },
                    success: function(response) {
                        console.log(response);
                        var newTable = $(response).find('.datatable');
                        var newPagination = $(response).find('.pagination');
                        $('.datatable').html(newTable.html());
                        $('.pagination').html(newPagination.html());
                    }
                });
            });

            $('#search').on('input',function() {
                var search = $(this).val();
                // update only the table

                $.ajax({
                    url: "{{ route('admin.transaction.show') }}",
                    type: 'GET',
                    data: {
                        search: search
                    },
                    success: function(response) {
                        var newTable = $(response).find('.datatable');
                        $('.datatable').html(newTable.html());
                    }
                });

            });
        });
    </script>
@endsection

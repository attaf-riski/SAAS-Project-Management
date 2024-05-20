@extends('template')

@php
    $title = "Invoice";
    $pretitle = "invoice/list";
@endphp


@section('body')
    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Invoice</h3>
                </div>

                <div class="card-body border-bottom py-3">
                    <div class="d-flex">
                        <div class="text-muted">
                            Search:
                            <div class="ms-2 d-inline-block">
                                <input type="text" id="search" class="form-control" aria-label="Search Contract" placeholder="find Invoice by project name...">
                            </div>
                        </div>
                        <div class="ms-auto me-3">
                            <div class="text-muted">
                                Show
                                <div class="mx-2 d-inline-block">
                                    <input type="number" id="data_count_shows" class="form-control" value="5" size="3"
                                           aria-label="Invoices count">
                                </div>
                                entries
                            </div>
                        </div>
                        <a href="{{ route('workspace.invoices.showAdd') }}" class="btn btn-primary">Create Invoice</a>
                        {{-- <button type="button" class="btn btn-primary font-weight-bolder" data-bs-toggle="modal"
        data-bs-target="#tambah_invoice">
        Create Invoice
        </button> --}}
                    </div>
                </div>
                <div >
                    <table class="table card-table table-vcenter text-nowrap datatable table-hover">
                        <thead>
                            <tr>
                                <th class="w-1">No.
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm icon-thick" width="24"
                                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M6 15l6 -6l6 6" />
                                    </svg>
                                </th>
                                <th>Project Name</th>
                                <th>Client</th>
                                <th>Tanggal buat</th>
                                <th>Status</th>
                                <th>Kadarluarsa</th>
                                <th>Total</th>
                                {{-- <th class="w-1"></th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1 + ($invoices->currentPage() - 1) * $invoices->perPage();
                            @endphp
                            @foreach ($invoices as $invoice)
                                <tr onclick="window.location='{{ route('workspace.invoices.show', $invoice->id) }}'"
                                    style="cursor: pointer;">
                                    <td><span class="text-muted">{{ $i++ }}</span></td>
                                    <td>{{ $invoice->project_name }}</td>
                                    <td>{{ $invoice->name }}</td>
                                    <td>{{ $invoice->issued_date }}</td>
                                    <td>
                                        @if ($invoice->status == 'SENT')
                                            <span class="badge text-bg-success">{{ $invoice->status }}</span>
                                        @elseif($invoice->status == 'PENDING')
                                            <span class="badge text-bg-warning">{{ $invoice->status }}</span>
                                        @elseif($invoice->status == 'PAID')
                                            <span class="badge text-bg-danger">{{ $invoice->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($invoice->due_date)
                                            {{ $invoice->due_date }}
                                        @else
                                            <span class="badge text-bg-success">Open Date</span>
                                        @endif
                                    </td>
                                    <td>@currency($invoice->total)</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex align-items-center ms-auto">
                    {!! $invoices->appends(Request::except('page'))->links('pagination::bootstrap-5') !!}
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#data_count_shows').on('input',function() {
                var count_shows = $(this).val();
                // update the table and the pagination
                $.ajax({
                    url: "{{ route('workspace.invoice') }}",
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
                    url: "{{ route('workspace.invoice') }}",
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
    </div>
@endsection


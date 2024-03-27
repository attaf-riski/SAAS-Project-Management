@php
    $title = 'Proyek';
    $pretitle = 'proyek/detail';
@endphp

@extends('template')

@section('body')
    <div class="row">
        <div class="col">
            <span class="fs-2"><a href="{{ route('workspace.projects') }}" style="text-decoration: none;">
                    Project </a>/ <strong>{{ $project->project_name }}</strong></span>
        </div>
    </div>
    <div class="row">
        <div class="col">
            {{-- add button to add invoice --}}
            <button type="button" class="btn mt-2" data-bs-toggle="modal">
                <a href="{{ route('workspace.invoices.createfromproject', ['id' => $project->id]) }}"
                    style="text-decoration: none;">Add Invoice</a>
            </button>
            <a href="#" class="btn btn-secondary dropdown-toggle mt-2" data-bs-toggle="dropdown"
                aria-expanded="false">More</a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('workspace.projects.edit', $project->id) }}">Edit Project</a>
                </li>
                @if ($project->status == 'ENDED')
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                            data-bs-target="#modalStart-{{ $project->id }}">Unend Project</a></li>
                @else
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                            data-bs-target="#modalEnd-{{ $project->id }}">End Project</a>
                    </li>
                @endif
                <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                        data-bs-target="#modalDelete-{{ $project->id }}">Delete Project</a></li>
            </ul>
            <input type="text" name="id_client" id="inputField" class="form-control mt-2" value="{{ $client->name }}"
                onfocus="showDropdown()" onblur="hideDropdown()" style="width: 190px;" disabled>
        </div>
    </div>
    <div class="col-md">
        <div class="card mt-2">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs nav-fill" data-bs-toggle="tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="#tabs-term" class="nav-link active" data-bs-toggle="tab" aria-selected="true"
                            role="tab">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-receipt-2"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2" />
                                <path d="M14 8h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5m2 0v1.5m0 -9v1.5" />
                            </svg>
                            Terms</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tabs-invoices" class="nav-link" data-bs-toggle="tab" aria-selected="true" role="tab">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-timeline"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 16l6 -7l5 5l5 -6" />
                                <path d="M15 14m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                <path d="M10 9m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                <path d="M4 16m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                <path d="M20 8m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                            </svg>
                            Invoices</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tabs-transaction-7" class="nav-link" data-bs-toggle="tab" aria-selected="true"
                            role="tab">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-report-money"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                <path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" />
                                <path d="M12 17v1m0 -8v1" />
                            </svg>
                            Transactions</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tabs-file-7" class="nav-link" data-bs-toggle="tab" aria-selected="false"
                            role="tab" tabindex="-1"><!-- Download SVG icon from http://tabler-icons.io/i/user -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bell-filled"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M14.235 19c.865 0 1.322 1.024 .745 1.668a3.992 3.992 0 0 1 -2.98 1.332a3.992 3.992 0 0 1 -2.98 -1.332c-.552 -.616 -.158 -1.579 .634 -1.661l.11 -.006h4.471z"
                                    stroke-width="0" fill="currentColor" />
                                <path
                                    d="M12 2c1.358 0 2.506 .903 2.875 2.141l.046 .171l.008 .043a8.013 8.013 0 0 1 4.024 6.069l.028 .287l.019 .289v2.931l.021 .136a3 3 0 0 0 1.143 1.847l.167 .117l.162 .099c.86 .487 .56 1.766 -.377 1.864l-.116 .006h-16c-1.028 0 -1.387 -1.364 -.493 -1.87a3 3 0 0 0 1.472 -2.063l.021 -.143l.001 -2.97a8 8 0 0 1 3.821 -6.454l.248 -.146l.01 -.043a3.003 3.003 0 0 1 2.562 -2.29l.182 -.017l.176 -.004z"
                                    stroke-width="0" fill="currentColor" />
                            </svg>
                            Files</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="#tabs-notes" class="nav-link" data-bs-toggle="tab" aria-selected="false"
                            role="tab"
                            tabindex="-1"><!-- Download SVG icon from http://tabler-icons.io/i/activity -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M3 12h4l3 8l4 -16l3 8h4"></path>
                            </svg>
                            Notes</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active show" id="tabs-term" role="tabpanel">
                        <div class="row">
                            <div class="col">
                                <h2 class="mt-2">{{ $project->project_name }}</h2>
                            </div>
                            <div class="col-auto mt-2">
                                <a href="{{ route('workspace.projects.edit', $project->id) }}" class="text-secondary fs-3"
                                    style="text-decoration: none;">
                                    <i class="bi bi-pencil-fill text-secondary me-2"></i>Edit
                                </a>
                            </div>
                        </div>
                        <hr style="position: relative; bottom: 20px;">
                        <div class="row">
                            <div class="col">
                                <h3>Billing Schedule</h3>
                            </div>
                            <div class="col-auto">
                                <a href="{{ route('workspace.projects.edit', $project->id) }}"
                                    class="text-secondary fs-3" style="text-decoration: none;">
                                    <i class="bi bi-pencil-fill text-secondary me-2"></i>Edit
                                </a>
                            </div>
                            <p class="fs-3">Set dates to get invoice reminders.</p>
                            <div class="d-flex justify-content-start align-items-center">
                                <p class="me-5 fs-3">Start Date</p>
                                <p class="fs-3">{{ $project->start_date }}</p>
                            </div>
                            <div class="d-flex justify-content-start align-items-center">
                                <p class="me-5 fs-3">End Date</p>
                                <p class="fs-3">{{ $project->end_date }}</p>
                            </div>
                            <div class="d-flex justify-content-start align-items-center">
                                <p class="me-5 fs-3">I Will Bill</p>
                                <p class="fs-3">Once</p>
                            </div>
                        </div>
                        <hr>
                        @foreach ($serviceDetails as $SD)
                            <div class="row">
                                <div class="col">
                                    <h2 class="fs-3">Project Services</h2>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('workspace.projects.edit', $project->id) }}"
                                        class=" text-secondary fs-3">
                                        <h3 class="text-secondary">{{ $SD->service_name }}</h3>
                                    </a>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col">
                                    <p class="text-success fs-3 mt-2">Currency : IDR</p>
                                </div>
                                <div class="col-auto">
                                    <h1 class="text-secondary">Rp. {{ $SD->price }}</h1>
                        @endforeach
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col">
                        <h3 class="mt-2 fs-3">Deposit</h3>
                        <p class="fs-3 mt-2">Deposit Not Required</p>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('workspace.projects.edit', $project->id) }}" style="text-decoration: none;"
                            class="text-secondary fs-3">
                            <i class="bi bi-pencil-fill text-secondary me-2"></i>Edit
                        </a>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="tabs-invoices" role="tabpanel">
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable table-hover">
                        <thead>
                            <tr>
                                <th class="w-1">No.
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm icon-thick"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" fill="none" stroke-linecap="round"
                                        stroke-linejoin="round">
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
                                    <td>{{ $project->project_name }}</td>
                                    <td>{{ $client->name }}</td>
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
            </div>
            <div class="tab-pane" id="tabs-activity-7" role="tabpanel">
                <h4>Activity tab</h4>
                <div>Ultricies tristique enim at diam, sem nunc amet, pellentesque id egestas velit sed</div>
            </div>
            <div class="tab-pane" id="tabs-notes" role="tabpanel">
                <form action="{{ route('workspace.projects.update.notes', ['id' => $project->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <textarea rows="5" class="form-control" name="notes" placeholder="Add Notes...">{{ $project->notes }}</textarea>
                    <button class="btn btn-primary mt-3">Save</button>
                </form>
            </div>
        </div>
    </div>
    </div>
    </div>
    {{-- Modal Hapus --}}
    <div class="modal modal-blur fade" id="modalDelete-{{ $project->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-danger"></div>
                <div class="modal-body text-center py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M12 9v4"></path>
                        <path
                            d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z">
                        </path>
                        <path d="M12 16h.01"></path>
                    </svg>
                    <h3>Are you sure?</h3>
                    <div class="text-secondary">Do you really want to remove project
                        {{ $project->project_name }}? What you've done cannot be undone.</div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <form action="{{ route('workspace.projects.delete', ['id' => $project->id]) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="col">
                                    <a href="#" class="btn w-100 mb-2" data-bs-dismiss="modal">Cancel</a>
                                </div>
                                <div class="col">
                                    <button class="btn btn-danger w-100" data-bs-dismiss="modal">Delete</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- end project butotn --}}
    <div class="modal modal-blur fade" id="modalEnd-{{ $project->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-danger"></div>
                <div class="modal-body text-center py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M12 9v4"></path>
                        <path
                            d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z">
                        </path>
                        <path d="M12 16h.01"></path>
                    </svg>
                    <h3>Are you sure?</h3>
                    <div class="text-secondary">Do you really want to end project
                        {{ $project->project_name }}?</div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <form action="{{ route('workspace.projects.endproject', ['id' => $project->id]) }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <div class="col">
                                    <a href="#" class="btn w-100 mb-2" data-bs-dismiss="modal">Cancel</a>
                                </div>
                                <div class="col">
                                    <button class="btn btn-danger w-100" data-bs-dismiss="modal">End Project</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="modalStart-{{ $project->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-danger"></div>
                <div class="modal-body text-center py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24"
                        height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M12 9v4"></path>
                        <path
                            d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z">
                        </path>
                        <path d="M12 16h.01"></path>
                    </svg>
                    <h3>Are you sure?</h3>
                    <div class="text-secondary">Do you really want to unend project
                        {{ $project->project_name }}?</div>
                </div>
                <div class="modal-footer">
                    <div class="w-100">
                        <div class="row">
                            <form action="{{ route('workspace.projects.unendproject', ['id' => $project->id]) }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <div class="col">
                                    <a href="#" class="btn w-100 mb-2" data-bs-dismiss="modal">Cancel</a>
                                </div>
                                <div class="col">
                                    <button class="btn btn-danger w-100" data-bs-dismiss="modal">UnEnd Project</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

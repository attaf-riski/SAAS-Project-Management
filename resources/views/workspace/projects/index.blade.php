@extends('template')

@php
  $title= "Project";
@endphp


@section('body') 
<button class="btn btn-primary mb-3" data-bs-toggle="modal"
data-bs-target="#tambah_project">Add Project</button>
<div class="row row-deck row-cards">
  <div class="col-12">
    <div class="card">
    <div class="card-header">
      <h3 class="card-title">Project</h3>
    </div>
    <div class="table-responsive">
      <table class="table card-table table-vcenter text-nowrap datatable">
        <thead>
          <tr>
            <th class="w-1">No.
              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm icon-thick" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 15l6 -6l6 6" /></svg>
            </th>
            <th>Project Name</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Status</th>
            <th>Client</th>
            <th>User</th>
            <th class="w-1"></th>
          </tr>
        </thead>
        <tbody>
            @foreach($projectmodels as $project)
            <tr>
              <td><span class="text-muted">{{ $loop->iteration }}</span></td>
              <td>{{ $project->project_name}}</td>
              <td>{{ $project->start_date }}</td>
              <td>{{ $project->end_date }}</td>
              <td>{{ $project->status }}</td>
              <td>{{ $project->id_client }}</td>
              <td>{{ $project->user_id }}</td>
            <td><div class="btn-group mb-1 dropleft ">
              <div class="dropdown dropleft">
                <button class="btn btn-primary dropdown-toggle me-1" type="button" id="dropdownMenuButtonIcon" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Aksi
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                  <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalEdit-{{$project->id}}">
                    Edit
                  </button>
                  <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalDelete-{{$project->id}}">Delete</button>
                </div>
              </div>
          </div></td>
          </tr>
          {{-- Edit Modals --}}
          <div class="modal modal-blur fade" id="modalEdit-{{ $project->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
              <div class="modal-content">
                <form action="{{ route('workspace.projects.update',['id' => $project->id]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                    <div class="modal-header">
                    <h5 class="modal-title">Edit Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-3">
                        <label class="form-label">Nama Project</label>
                        <input type="text" name="name" class="form-control" placeholder="Masukkan Nama" value="{{ $project->name }}" >
                      </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="address" class="form-control" placeholder="Masukkan Alamat" value="{{ $project->address }}">
                      </div>
                    <div class="mb-3">
                        <label class="form-label">No Telp</label>
                        <input type="text" name="no_telp" class="form-control" placeholder="Masukan Jurusan" value="{{ $project->no_telp }}">
                      </div>
                    </div>
                    <div class="modal-footer">
                      <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                          Cancel
                      </a>
                      <button type="submit" class="btn btn-primary mr-2" data-bs-dismiss="modal">
                          Edit Client
                      </button>
                      </div>
                </form>
              </div>
            </div>
          </div>

          {{-- Modal Hapus --}}

          <div class="modal modal-blur fade" id="modalDelete-{{ $project->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
              <div class="modal-content">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-status bg-danger"></div>
                <div class="modal-body text-center py-4">
                  <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 9v4"></path><path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z"></path><path d="M12 16h.01"></path></svg>
                  <h3>Are you sure?</h3>
                  <div class="text-secondary">Do you really want to remove Project {{$project->name}}? What you've done cannot be undone.</div>
                </div>
                <div class="modal-footer">
                  <div class="w-100">
                    <div class="row">
                      <form action="{{ route('workspace.projects.delete',['id' => $project->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                      <div class="col"><a href="#" class="btn w-100" data-bs-dismiss="modal">
                          Cancel
                        </a></div>
                      <div class="col"><button class="btn btn-danger w-100" data-bs-dismiss="modal">
                          Delete
                        </button></div>
                        </form>
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
    <div class="card-footer d-flex align-items-center ms-auto">
      {{-- {!! $projectmodels->appends(Request::except('page'))->links('pagination::bootstrap-5') !!} --}}
    </div>
  </div>
</div>
</div>

{{-- Modal Dialog --}}
<div class="modal fade" id="tambah_project" tabindex="-1" aria-labelledby="modal2Label" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="modal2Label">Project Name</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('workspace.projects.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="row">
                <div class="col-md-12 mt-2">
                    <div class="form-group">
                      <label for="project_name">Project Name</label>
                      <input type="text" class="form-control mt-1" name="project_name" placeholder="Masukkan Project name" required />
                      @error('project_name')
                      <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                    </div>
                </div>
                <div class="col-md-12 mt-2">
                    <div class="form-group">
                      <label for="start_date">Start Date</label>
                      <input type="date" class="form-control mt-1" id="start_date" name="start_date" placeholder="Start Date" required />
                      @error('start_date')
                      <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                    </div>
                <div class="col-md-12 mt-2">
                    <div class="form-group">
                      <label for="end_date">End Date</label>
                      <input type="date" class="form-control mt-1" id="end_date" name="end_date" placeholder="Masukkan alamat" required />
                      @error('end_date')
                      <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                    </div>
                </div>
                <div class="col-md-12 mt-2">
                    <div class="form-group">
                      <label for="status">Status</label>
                      <select class="form-control mt-1" id="statusSelect">
                        <option value="">Select Status</option>
                        <option value="active">Active</option>
                        <option value="pending">Pending</option>
                        <option value="inactive">Inactive</option>
                    </select>
                      @error('status')
                      <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                    </div>
                </div>
                <div class="col-md-12 mt-2">
                    <div class="form-group">
                      <label for="client">Nama Client</label>
                      <select class="form-control mt-1" id="clientSelect">
                        <option value="">Select client</option>
                        @foreach ($clients as  $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                      @error('client')
                      <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                    </div>
                </div>
                <div class="col-md-12 mt-2">
                    <div class="form-group">
                      <label for="freelance">Nama Freelance</label>
                      <select class="form-control mt-1" id="freelanceSelect">
                        <option value="">Select freelance</option>
                        @foreach ($freelances as  $freelance)
                        <option value="{{ $freelance->id }}">{{ $freelance->fullname }}</option>
                        @endforeach
                    </select>
                      @error('freelance')
                      <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
        <button type="submit" class="btn btn-primary mr-2">Tambah</button>
    </div>
      </div>
  </div>
</div>
@endsection

@section('sweetalert')
<script>
    // Auto-close the alert messages after 3 seconds (3000 milliseconds)
    setTimeout(function() {
        $('.swal2-popup').fadeOut();
    }, 3000);
</script>
@endsection
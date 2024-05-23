@php
    $title = "Contract";
    $pretitle = "contract/sendmail";
@endphp

@extends('template')

@section('body')
    <div class="container">
        <h1>Edit Email</h1>
        <form action="{{ route('workspace.contract.finishemail', $contract->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="recipient" class="form-label">To:</label>
                <input type="text" class="form-control" id="recipient" name="recipient" value="{{ $client->email }}" required>
            </div>
            <div class="mb-3">
                <label for="subject" class="form-label">Subject:</label>
                <input type="text" class="form-control" id="subject" name="subject" value="{{ $contract->contract_name }}" required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message:</label>
                <textarea class="form-control" id="message" name="message" rows="6" required>
Kepada Yth. {{$client->name}},
Dalam surel ini, kami lampirkan Kontrak untuk proyek {{$contract->contract_name}}.
Terlampir: Kontrak Freelancer
                </textarea>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Send Email</button>
            </div>
        </form>
    </div>
@endsection

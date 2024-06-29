@php
    $title = "Quotation";
    $pretitle = "quotation/editemail";
@endphp

@extends('template')

@section('body')
    <div class="container">
        <h1>Edit Email</h1>
        <form action="{{ route('workspace.quotation.finishemail', $quotation->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="recipient" class="form-label">To:</label>
                <input type="text" class="form-control" id="recipient" name="recipient" value="{{ $client->email }}" required>
            </div>
            <div class="mb-3">
                <label for="subject" class="form-label">Subject:</label>
                <input type="text" class="form-control" id="subject" name="subject" value="{{ $quotation->quotation_name }}" required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message:</label>
                <textarea class="form-control" id="message" name="message" rows="6" required>
Kepada Yth. {{$client->name}},
Saya tertarik dengan proyek anda,"{{$quotation->$quotation_name}}".

Saya sangat antusias untuk mempelajari lebih lanjut tentang kebutuhan Anda dan bagaimana saya dapat membantu Anda mencapai tujuan Anda.Saya telah meninjau detail proyek Anda dan menyiapkan proposal untuk pertimbangan Anda.

Detail dari proposal terlampir. Apabila anda tertarik anda dapat membalasa email ini atau chat pada platform [nama platform anda menemukan proyek]

                </textarea>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Send Email</button>
            </div>
        </form>
    </div>
@endsection

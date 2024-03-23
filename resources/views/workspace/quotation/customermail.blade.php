@php
    $title = "Quotation";
    $pretitle = "quotation/customermail";
@endphp

@extends('clienttemplate')

@section('body')
    <div class="container">
        <p>
            {{ $msg }}
        </p>
        <div class="row mb-3">
            <div class="col">
                <h3 class="card-title">Quotation</h3>
            </div>
            <div class="col d-flex justify-content-end">
                {{-- place in the left --}}
                <!-- Tombol Submit -->
                <a href={{"http://127.0.0.1:8000/workspace/quotation/dismissed/".strVal($quotation->id)}} target="_blank" rel="noopener noreferrer">
                    <button class="btn btn-danger">Reject Project</button></a>
                <a href={{"http://127.0.0.1:8000/workspace/quotation/accepted/".strVal($quotation->id)}} target="_blank" rel="noopener noreferrer">
                    <button class="btn btn-primary">Accept Project</button></a>
                
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <p><strong>Project Name:</strong> {{ $quotation->quotation_name }}</p>
                <p><strong>Client Name:</strong> {{ $client->name }}</p>
                <p><strong>Start Date:</strong> {{ $quotation->start_date }}</p>
                @if ($quotation->end_date)
                    <p><strong>End Date:</strong> {{ $quotation->end_date }}</p>
                @else
                    <p><strong>End Date:</strong> Open Date</p>
                @endif
                <p><strong>Final Invoice Date:</strong> {{ $quotation->final_invoice_date }}</p>
                <p><strong>Require Deposit:</strong> {{ $quotation->require_deposit ? 'Yes' : 'No' }}</p>
                @if ($quotation->require_deposit)
                    <p><strong>Deposit Percentage:</strong> {{ $quotation->deposit_percentage }}%</p>
                    <p><strong>Deposit Amount:</strong> ${{ $quotation->deposit_amount }}</p>
                    <p><strong>Client Agrees to Deposit:</strong> {{ $quotation->client_agrees_deposit ? 'Yes' : 'No' }}</p>
                @endif
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-header">
                <h3 class="card-title">Services</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Service Name</th>
                            <th>Price</th>
                            <th>Fee Method</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($serviceDetails as $service)
                            <tr>
                                <td>{{ $service->service_name }}</td>
                                <td>{{ $service->price }}</td>
                                <td>{{ $service->pay_method }}</td>
                                <td>{{ $service->description }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@php
    $title = "Contract";
    $pretitle = "contract/customermail";
@endphp

@extends('clienttemplate')

@section('body')
    <script src="//cdn.ckeditor.com/4.24.0-lts/basic/ckeditor.js"></script>
    <div class="row justify-content-center">
        <form action={{"http://127.0.0.1:8000/workspace/contract/accepted/".strVal($contract->id)}}>
            @csrf
            <p>{{ $msg }}</p>
            <div class="row mb-3">
                <div class="col">
                    <h3 class="card-title">Review Contract</h3>
                </div>
                <div class="col d-flex justify-content-end">
                    <a href={{"http://127.0.0.1:8000/workspace/contract/accepted/".strVal($contract->id)}} target="_blank" rel="noopener noreferrer">
                        <button class="btn btn-primary">Accept Contract</button></a>
                    
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form>
                        <p><strong>Project Name:</strong> {{ $contract->contract_name }}</p>
                        <p><strong>Client Name:</strong> {{ $client->name }}</p>
                        <p><strong>Start Date:</strong> {{ $contract->start_date }}</p>
                        @if ($contract->end_date)
                            <p><strong>End Date:</strong> {{ $contract->end_date }}</p>
                        @else
                            <p><strong>End Date:</strong> Open Date</p>
                        @endif
                        <p><strong>Final Invoice Date:</strong> {{ $contract->final_invoice_date }}</p>
                        <p><strong>Require Deposit:</strong> {{ $contract->require_deposit ? 'Yes' : 'No' }}</p>
                        @if ($contract->require_deposit)
                            <p><strong>Deposit Percentage:</strong> {{ $contract->deposit_percentage }}%</p>
                            <p><strong>Deposit Amount:</strong> ${{ $contract->deposit_amount }}</p>
                            <p><strong>Client Agrees to Deposit:</strong>
                                {{ $contract->client_agrees_deposit ? 'Yes' : 'No' }}
                            </p>
                        @endif
                    </form>
                </div>
            </div>
            <div class="card  mt-3">
                <div class="card-body">
                    <h5 class="card-title">Service Contract</h5>
                    <p class="card-text">This contract (the “Agreement”) is entered into by and between the below named
                        parties (the “Parties”). This offer will expire at the close of business on @if ($contract->end_date)
                            <strong>{{ $contract->end_date }}</strong>
                        @else
                            <strong>Open Date</strong>
                        @endif if not accepted in writing by counter-signing this Agreement by the
                        aforementioned date.</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Client:</strong> {{ $client->name }}</li>
                        <li class="list-group-item"><strong>Email:</strong> {{ $client->email }}</li>
                        <li class="list-group-item"><strong>Contractor:</strong> {{ $user->fullname }}</li>
                        <li class="list-group-item"><strong>Email:</strong> {{ $user->email }}</li>
                    </ul>
                    <h5 class="mt-3">Services</h5>
                    <p>Contractor agrees to perform services as described in Attachment A (the “Services”) and Client agrees
                        to pay Contractor as described in Attachment A.</p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <label class="form-label">Attachment A: Services</label>
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
                    <p><strong>Total:</strong> {{ $contract->total }}</p>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-body mt-3">
                    <div class="form-group">
                        <label class="form-label">Attachment B: Terms and Conditions</label>

                        {!! html_entity_decode($contract->contract_pdf == 'DEFAULT' ? env('DEFAULT_TERM') : $contract->contract_pdf) !!}
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#contract'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection

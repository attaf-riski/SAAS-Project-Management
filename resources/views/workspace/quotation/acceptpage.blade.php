@php
    $title = "Quotation";
    $pretitle = "quotation/acceptpage";
@endphp

@extends('clienttemplate')

@section('body')
    <div class="container container-tight" style="margin-top: 130px;">
        <div class="card" style="height: 200px;">
            <div class="card-status-top bg-green"></div>
            <div class="card-header">
                <h3 class="card-title">Succesfully Accepting Project!</h3>
            </div>
            <div class="card-body p-0">
                <div class="text-center mt-3">
                    <h4>Thank You For Accepting Project</h4>
                    <p>You have successfully accepted the project</p>
                    <p>We will send contact you soon!</p>
                </div>
            </div>
        </div>
    </div>
   
@endsection

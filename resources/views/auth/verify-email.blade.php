
<!doctype html>
<!--
* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.
* @version 1.0.0-beta19
* @link https://tabler.io
* Copyright 2018-2023 The Tabler Authors
* Copyright 2018-2023 codecalm.net Paweł Kuna
* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
-->
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Verify Email</title>
    <!-- CSS files -->
    <link href="{{ asset('dist/css/tabler.min.css?1684106062')}}" rel="stylesheet"/>
    <link href="{{ asset('dist/css/tabler-flags.min.css?1684106062')}}" rel="stylesheet"/>
    <link href="{{ asset('dist/css/tabler-payments.min.css?1684106062')}}" rel="stylesheet"/>
    <link href="{{ asset('dist/css/tabler-vendors.min.css?1684106062')}}" rel="stylesheet"/>
    <link href="{{ asset('dist/css/demo.min.css?1684106062')}}" rel="stylesheet"/>
    <style>
        @import url('https://rsms.me/inter/inter.css');
        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }
        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
</head>
<body  class=" d-flex flex-column">
<script src="./dist/js/demo-theme.min.js?1684106062"></script>
<div class="page page-center">
    <div class="container container-tight py-4">
        <div class="text-center mb-4">
            <a href="." class="navbar-brand navbar-brand-autodark"></a>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{-- success --}}
            @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

        </div>
        <form class="card card-md" action="{{ route('verification.send') }}" method="post" autocomplete="off">
            @csrf
            <div class="card-body">
                @if (session()->has('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                    </div>
                @endif
                <h2 class="card-title text-center mb-4">Verify Your Email</h2>
                <p class="text-muted mb-4">Resend the verification email.</p>
                <div class="mb-3">
                    <label class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter email" required>
                </div>
                <div class="form-footer">
                    <button class="btn btn-primary w-100">
                        <!-- Download SVG icon from http://tabler-icons.io/i/mail -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
                        Send me new verification email
                    </button>
                </div>
            </div>
        </form>
        <div class="text-center text-muted mt-3">
            Forget it, <a href="{{ route('login') }}">send me back</a> to the sign in screen.
        </div>
    </div>
</div>
<!-- Libs JS -->
<!-- Tabler Core -->
<script src="{{ asset('dist/js/tabler.min.js?1684106062') }}" defer></script>
<script src="{{ asset('dist/js/demo.min.js?1684106062') }}" defer></script>
</body>
</html>

@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">Paket Satın Al</h2>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            @foreach($packages as $paket)
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $paket->paketadi }}</h5>
                            <p class="card-text">Kontör: {{ $paket->kontoradeti }}</p>
                            <p class="card-text">Fiyat: {{ $paket->paketbedeli }} TL</p>
                            <form action="{{ $paket->odeme_linki }}" method="GET">
                                <button type="submit" class="btn btn-primary">
                                    Satın Al
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

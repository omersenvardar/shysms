@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">Paketler</h2>
        <div class="row">
            @foreach($paketler as $paket)
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $paket->paketadi }}</h5>
                            <p class="card-text">Kontör Adedi: {{ $paket->kontoradeti }}</p>
                            <p class="card-text">Fiyat: {{ $paket->paketbedeli }} TL</p>
                            <form action="{{ route('paketler.satin.al') }}" method="POST">
                                @csrf
                                <input type="hidden" name="paket_id" value="{{ $paket->id }}">
                                <button type="submit" class="btn btn-primary">Satın Al</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

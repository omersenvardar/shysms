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
                            <form action="{{ route('credits.process') }}" method="POST">
                                @csrf
                                <input type="hidden" name="paket_id" value="{{ $paket->id }}">
                                <div class="mb-3">
                                    <label for="payment_method">Ödeme Yöntemi</label>
                                    <select name="payment_method" id="payment_method" class="form-select" required>
                                        <option value="credit_card">Kredi Kartı</option>
                                        <option value="gsm">GSM</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Satın Al</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

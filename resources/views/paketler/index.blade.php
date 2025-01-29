@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">Paket Satın Al</h2>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('credits.process') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="package" class="form-label">Paket Seçimi</label>
                <select class="form-control" id="package" name="package_id" required>
                    @foreach ($packages as $package)
                        <option value="{{ $package->id }}">
                            {{ $package->paketadi }} - {{ $package->kontoradeti }} Kontör - {{ $package->paketbedeli }} TL
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="payment_method" class="form-label">Ödeme Yöntemi</label>
                <select class="form-control" id="payment_method" name="payment_method" required>
                    <option value="credit_card">Kredi Kartı</option>
                    <option value="gsm">GSM Ödeme</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Ödemeye Devam Et</button>
        </form>
    </div>
@endsection

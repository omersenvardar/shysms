@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2 class="mb-4">Kayıt Ol</h2>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nickname" class="form-label">Takma Ad</label>
                <input type="text" class="form-control" id="nickname" name="nickname" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-Posta</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Şifre</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Kayıt Ol</button>
        </form>
    </div>
@endsection

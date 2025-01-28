@extends('layouts.app')

@section('content')
    <div class="container text-center mt-5">
        <h2>Ödeme Başarısız</h2>
        <p>Ödemeniz gerçekleştirilemedi. Lütfen tekrar deneyin.</p>
        <a href="{{ route('credits.show') }}" class="btn btn-danger">Tekrar Dene</a>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container text-center mt-5">
        <h2>Ödeme Başarılı</h2>
        <p>Kontörler hesabınıza eklenmiştir.</p>
        <a href="{{ url('/') }}" class="btn btn-success">Ana Sayfaya Dön</a>
    </div>
@endsection

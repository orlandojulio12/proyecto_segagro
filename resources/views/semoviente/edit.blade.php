@extends('layouts.dashboard')

@section('page-title', 'Editar Semoviente')

@section('dashboard-content')
<div class="section-header">
    <h2>Editar Acta de Nacimiento</h2>
</div>

<div class="content-card">
    <form action="{{ route('semoviente.update', $semoviente) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('semoviente.partials.form', ['mode' => 'edit'])
    </form>
</div>
@endsection

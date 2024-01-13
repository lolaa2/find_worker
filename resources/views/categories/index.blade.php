@extends("layouts.main")   

@section("content")
<div class="container">
    <div class="card">
        <div class="card-header">Manage Categoreis</div>
        <div class="card-body">
            {{ $dataTable->table() }}
        </div>
    </div>
</div>
@endsection
@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
@push('css')
    <style>
        .h{color: green}
        .h2{color: red}
    </style>
@endpush


@push('css')
    <style>
        .h2{color: rgb(231, 228, 9)}
        
    </style>
@endpush

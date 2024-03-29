@extends("layouts.main")   

@section("content")
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>Manage Cities</h4>
                <a href="{{route('dashboard.cities.create')}}" class="btn btn-primary">Add</a>
            </div>

        </div>
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

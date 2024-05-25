@extends('layouts.main')

@section('content')
    <h1 class="h ">Show copmanies </h1>

    <div class="card">
        <div class="card-header">
            {{$company->name}}
        </div>

        <div class="card-body">
            <blockquote class="blockquote mb-0 p-2">
                <p>
                <h6>Name</h6>
                {{$company->name}}.
               </p> 
               <p>
                <h6>Phone</h6>
                {{$company->phone}}.
               </p> 
                <p>
                <h6>Email</h6>
                {{$company->email}}.</p>

            </blockquote>
        </div>
    </div>

    </div>
@endsection

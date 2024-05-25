@extends('layouts.main')

@section('content')
    <h1 class="h ">Show copmanies </h1>
    <a href="{{route('dashboard.servicesRequest.showRequest')}}" class="btn btn-primary">Show Requests</a>

    <div class="card">
        <div class="card-header">
            {{$customer->name}}
        </div>

        <div class="card-body">
            <blockquote class="blockquote mb-0 p-2">
                <p>
                <h6>Name</h6>
                {{$customer->name}}.
               </p> 
               <p>
                <h6>Phone</h6>
                {{$customer->phone}}.
               </p> 
                <p>
                <h6>Email</h6>
                {{$customer->email}}.</p>

            </blockquote>

        </div>
    </div>

    </div>
    
@endsection


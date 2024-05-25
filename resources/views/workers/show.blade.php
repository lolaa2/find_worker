@extends("layouts.main")   

@section("content")
<h1 class="h ">Show Workers </h1>

<div class="card">
    <div class="card-header" >
      
      {{$worker->name}}
    </div>

    <div class="card-body">
      <blockquote class="blockquote mb-0 p-2">
        <p>
          <h6>Name</h6>
          
          {{$worker->name}}.</p>
     
     
     
     
        <p>
            <h6>Description</h6>
            
            {{$worker->email}}.</p>
          
      </blockquote>
    </div>
  </div>
    
</div>

@endsection
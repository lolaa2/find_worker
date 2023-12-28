@extends("layouts.main")   

@section("content")
<h1 class="h ">Show Service </h1>
<div class="card">
    <div class="card-header">
      {{$service->name}}
    </div>
    <div class="card-body">
      <blockquote class="blockquote mb-0 p-2">
        <p>
            <h6>Description</h6>
            
            {{$service->description}}.</p>
            <h6>Images</h6>
        @foreach ($service->images as $image)
            <img style="width: 150px;height:150px;" src="{{URL::asset($image->path)}}"/>
        @endforeach
      </blockquote>
    </div>
  </div>
    
</div>
@endsection
{{-- @extends("layouts.main")   

@section("content")
<h1 class="h ">Edit Previous </h1>
@endsection --}}
@extends("layouts.main")   

@section("content")


<div class="card">
    <h5 class="card-header">Edit Previous</h5>
    <div class="card-body">
        
<form action="{{route('dashboard.previous.update',$previous->id)}}" method="POST">
    @csrf

    @method('PUT')
    <div class="form-group">
      <label for="email">Title:</label>
      <input type="text" name="title" class="form-control" id="title" value="{{$previous->title}}">
      @error('name')
      <small class="text-danger">
          {{$message}}
      </small>
      @enderror
    </div>
    <div class="form-group">
      <label for="pwd">Description:</label>
      <textarea name="description" class="form-control" id="pwd">{{$previous->description}}</textarea>
      @error('description')
      <small class="text-danger">
        {{$message}}
    </small>
      @enderror
    </div>
   
    <br>
    
    <button type="submit" class="btn btn-success">Save</button>
  </form>
    </div>
  </div>



@endsection
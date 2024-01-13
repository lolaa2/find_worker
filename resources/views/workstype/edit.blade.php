{{-- @extends("layouts.main")   

@section("content")
<h1 class="h"> Edit Work Type </h1>
@endsection --}}
@extends("layouts.main")   

@section("content")


<div class="card">
    <h5 class="card-header">Edit Work Type</h5>
    <div class="card-body">
        
<form action="{{route('dashboard.workstype.update',$workt->id)}}"method="POST">
    @csrf

    @method('PUT')
    <div class="form-group">
      <label for="email">Name:</label>
      <input type="text" name="name" class="form-control" id="name" value="{{$workt->name}}">
      @error('name')
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
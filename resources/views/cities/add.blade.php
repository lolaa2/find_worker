@extends("layouts.main")   

@section("content")






<div class="card">
    <h5 class="card-header">Add City</h5>
    <div class="card-body">
        
<form action="{{route('dashboard.cities.store')}}"  method="POST">
    @csrf

    <div class="form-group">
      <label for="email">Name:</label>
      <input type="text" name="name" class="form-control" id="name" value="">
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
@extends("layouts.main")   

@section("content")

<div class="card">
    <h5 class="card-header">Add Previous</h5>
    <div class="card-body">
        
<form action="{{route('dashboard.previous.add')}}"  method="POST">
    @csrf

    <div class="form-group">
      <label for="email">Title:</label>
      <input type="text" name="title" class="form-control" id="title" value="">
      @error('title')
      <small class="text-danger">
          {{$message}}
      </small>
      @enderror
    </div>
    <div class="form-group">
        <label for="email">Description:</label>
        <textarea name="description" class="form-control" id="pwd"></textarea>
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
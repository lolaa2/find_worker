@extends("layouts.main")   

@section("content")
<div class="card">
    <h5 class="card-header">Add Customers</h5>
    <div class="card-body">
        
<form action="{{route('dashboard.customers.add')}}"  method="POST">
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


    <div class="form-group">
        <label for="pwd">Phone:</label>
        <input name="phone" class="form-control" id="phone">
        @error('phone')
        <small class="text-danger">
          {{$message}}
      </small>
        @enderror
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="text" name="email" class="form-control" id="email" value="">
       @error('email')
       <small class= "text-danger">
        {{$message}}
       </small>
       @enderror  
      </div>
      

      <div class="form-group mt-4">
        <label for="email">Password :</label>
        <input type="password" name="password" class="form-control" id="password" value="">
       @error('password')
       <small class= "text-danger">
        {{$message}}
       </small>
       @enderror  
      </div>  
      <div class="form-group mt-4">
        <label for="email">City :</label>
        <select class="form-control" name="city_id">
  
          @foreach($cities as $city)
            <option value="{{$city->id}}" @selected($service->city_id==$city->id)>
                {{$city->name}}
            </option>
          @endforeach
        </select>
       @error('city_id')
       <small class= "text-danger">
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
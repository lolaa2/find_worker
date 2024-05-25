@extends("layouts.main")   

@section("content")






<div class="card">
    <h5 class="card-header">Add Service</h5>
    <div class="card-body">
        
<form action="{{route('dashboard.services.add')}}"  method="POST">
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
    <div class="form-group mt-4">
      <label for="email">User Id</label>
      <select class="form-control" name="user_id">

        @foreach($users as $user)
          <option value="{{$user->id}}" @selected($service->user_id==$user->id)>
              {{$user->name}}
          </option>
        @endforeach
      </select>
     @error('user_id')
     <small class= "text-danger">
      {{$message}}
     </small>
     @enderror  
    </div>



{{-- 
    <div class="form-group">
      <label for="email">User Id:</label>
      <input type="text" name="user_id" class="form-control" id="User_id" value="">
      @error('name')
      <small class="text-danger">
          {{$message}}
      </small>
      @enderror
    </div> --}}



  
    <div class="form-group">
        <label for="pwd">Description:</label>
        <textarea name="description" class="form-control" id="pwd"></textarea>
        @error('description')
        <small class="text-danger">
          {{$message}}
      </small>
        @enderror
      </div>
      <div class="form-group">
        <label for="email">Price:</label>
        <input type="text" name="price" class="form-control" id="price" value="">
       @error('price')
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

      <div class="form-group mt-4">
        <label for="email">Category :</label>
        <select class="form-control" name="category_id">
  
          @foreach($categories as $category)
            <option value="{{$category->id}}" @selected($service->category_id==$category->id)>
                {{$category->name}}
            </option>
          @endforeach
        </select>
       @error('category_id')
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
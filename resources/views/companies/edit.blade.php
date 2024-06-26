{{-- @extends("layouts.main")   

@section("content")
<h1 class="h"> Edit company </h1>
@endsection --}}
@extends("layouts.main")   

@section("content")


<div class="card">
    <h5 class="card-header">Edit Companies</h5>
    <div class="card-body">
        
<form action="{{route('dashboard.companies.update',$company->id)}}" method="POST">
    @csrf

    @method('PUT')
    <div class="form-group">
      <label for="name">Title:</label>
      <input type="text" name="name" class="form-control" id="name" value="{{$company->name}}">
      @error('name')
      <small class="text-danger">
          {{$message}}
      </small>
      @enderror
    </div>
    <div class="form-group">
      <label for="email">Email:</label>
      <textarea name="email" class="form-control" id="email">{{$company->email}}</textarea>
      @error('name')
      <small class="text-danger">
        {{$message}}
    </small>
      @enderror
    </div>
    <div class="form-group">
      <label for="phone">Phone:</label>
      <input type="text" name="phone" class="form-control" id="phone" value="{{$company->phone}}">
     @error('phone')
     <small class= "text-danger">
      {{$message}}
     </small>
     @enderror  
    </div>
    
    <div class="form-group mt-4">
      <label for="email">City :</label>
      <select class="form-control" name="city_id">

        @foreach($cities as $city)
          <option value="{{$city->id}}" @selected($company->city_id==$city->id)>
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
    {{-- <div class="form-group mt-4">
        <label for="email">Work :</label>
        <select class="form-control" name="work_id">
  
          @foreach($works as $work)
            <option value="{{$work->id}}" @selected($company->work_id==$work->id)>
                {{$work->name}}
            </option>
          @endforeach
        </select>
       @error('work_id')
       <small class= "text-danger">
        {{$message}}
       </small>
       @enderror  
      </div> --}}
      <br>
    
    <button type="submit" class="btn btn-success">Save</button>
  </form>
    </div>
  </div>



@endsection
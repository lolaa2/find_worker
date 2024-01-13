@extends("layouts.main")   

@section("content")


<div class="card">
    <h5 class="card-header">Edit Service</h5>
    <div class="card-body">
        
<form action="{{route('dashboard.services.update',$service->id)}}" method="POST" enctype="multipart/form-data">
    @csrf

    @method('PUT')
    <div class="form-group">
      <label for="email">Title:</label>
      <input type="text" name="name" class="form-control" id="email" value="{{$service->name}}">
      @error('name')
      <small class="text-danger">
          {{$message}}
      </small>
      @enderror
    </div>
    <div class="form-group">
      <label for="pwd">Description:</label>
      <textarea name="description" class="form-control" id="pwd">{{$service->description}}</textarea>
      @error('description')
      <small class="text-danger">
        {{$message}}
    </small>
      @enderror
    </div>
    <div class="form-group">
      <label for="email">Price:</label>
      <input type="text" name="price" class="form-control" id="price" value="{{$service->price}}">
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
    <br>
    <div class="form-group mt-4">
      <label for="email mb-5">Images :</label>
      
      <div class="row my-2 mb-3 ">
          <label for="up-im">Upload new images</label>
          <input type="file" multiple name="new_images[]" />
          @error('new_images')
            <small class="text-danger">
              Failed to upload images
            </small>
          @enderror
      </div>

      <div class="row my-2 mb-3">
        <label class="col-12">Select Images to remove</label>
      @foreach($service->images as $image)
        <div class="col-4">
          <label for={{"m".$image->id}} >
        <img src="{{URL::asset($image->path)}}" class="mx-2" style="width:100px;heigth:100px;border:solid 1px gray;border-radius:10px;"/>
          </label>
        <input id="{{'m'.$image->id}}" type="checkbox" value="{{$image->id}}" name="deleted_images[]" />
      </div>
        @endforeach
      </div>
    </div>
    <button type="submit" class="btn btn-success">Save</button>
  </form>
    </div>
  </div>



@endsection
@props(['id'])
<div class="d-flex ">

    <a href="{{route('dashboard.cities.edit',$id)}}" class="btn btn-warning mx-1" >
        edit
    </a>
    <a href="{{route('dashboard.cities.show',$id)}}" class="btn btn-danger mx-1" >
        delete
    </a>
</div>
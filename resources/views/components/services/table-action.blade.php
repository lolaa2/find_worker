@props(['id'])
<div class="d-flex ">
    <a href="{{route('dashboard.services.show',$id)}}" class="btn btn-success mx-1" >
        show
    </a>
    <a href="{{route('dashboard.services.edit',$id)}}" class="btn btn-warning mx-1" >
        edit
    </a>
    <form action="{{route('dashboard.services.delete',$id)}}" method="POST">
        @method('delete')
        @csrf
    <button class="btn btn-danger mx-1" >
        delete
    </button>
    </form>
</div>
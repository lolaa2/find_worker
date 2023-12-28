@props(['id'])
<div class="d-flex ">
    <a href="{{route('dashboard.workstype.show',$id)}}" class="btn btn-success mx-1" >
        show
    </a>
    <a href="{{route('dashboard.workstype.edit',$id)}}" class="btn btn-warning mx-1" >
        edit
    </a>
    <form method="POST" action="{{route('dashboard.workstype.delete',$id)}}">
        @method('delete')
        @csrf
    <button  class="btn btn-danger mx-1" >
        delete
    </button>
    </form>
</div>
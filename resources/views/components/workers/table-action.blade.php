@props(['id'])
<div class="d-flex ">
    {{-- <a href="{{route('dashboard.workers.show',$workers)}}" class="btn btn-success mx-1" >
        show
    </a>
    <a href="{{route('dashboard.workers.edit',$workers)}}" class="btn btn-warning mx-1" >
        edit
    </a> --}}



    <form action="{{route('dashboard.workers.show',$id)}}" method="GET" >
        @csrf
    <button class="btn btn-success mx-1" >
       Show
    </button>
    </form> <form action="{{route('dashboard.workers.edit',$id)}}" method="GET">
        @csrf
    <button class="btn btn-warning mx-1" >
        Edit
    </button>
    </form>
    <form action="{{route('dashboard.workers.delete',$id)}}" method="POST" onsubmit="showDeleteAlert(event,'All Worker services and previous Work will be deleted ')">
        @method('delete')
        @csrf
    <button class="btn btn-danger mx-1" >
        delete
    </button>
    </form>

</div>
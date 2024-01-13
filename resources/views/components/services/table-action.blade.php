@props(['id'])
<div class="d-flex ">
    <a href="{{route('dashboard.services.show',$id)}}" class="btn btn-success mx-1" >
        show
    </a>
    <a href="{{route('dashboard.services.edit',$id)}}" class="btn btn-warning mx-1" >
        edit
    </a>
    <form action="{{route('dashboard.workers.delete',$id)}}" method="POST" onsubmit="showDeleteAlert(event,'All services will be deleted ')">
        @method('delete')
        @csrf
    <button class="btn btn-danger mx-1" >
        delete
    </button>
    </form>
</div>
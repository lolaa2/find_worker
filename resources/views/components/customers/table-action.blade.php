@props(['id'])
<div class="d-flex ">
    <a href="{{route('dashboard.customers.show',$id)}}" class="btn btn-success mx-1" >
        show
    </a>
    <a href="{{route('dashboard.customers.edit',$id)}}" class="btn btn-warning mx-1" >
        edit
    </a>
    <form action="{{route('dashboard.customers.delete',$id)}}
    " method="POST"
     onsubmit="showDeleteAlert(event,This Previous will be deleted )">
        @method('delete')
        @csrf
    <button class="btn btn-danger mx-1" >
        delete
    </button>
    </form>
</div>
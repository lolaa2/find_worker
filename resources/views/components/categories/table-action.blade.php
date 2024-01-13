@props(['id'])
<div class="d-flex ">

    <a href="{{route('dashboard.categoreis.edit',$id)}}" class="btn btn-warning mx-1"   >
        edit
    </a>
    <form method="POST" action="{{route('dashboard.categoreis.delete',$id)}}" method="POST" onsubmit="showDeleteAlert(event,'All Categoreis will be deleted ')">
        @csrf
        @method("DELETE")
    <button  class="btn btn-danger mx-1" >
        delete
    </button>
    </form>
</div>
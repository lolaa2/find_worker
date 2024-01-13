@props(['id'])
<div class="d-flex ">

    <a href="{{route('dashboard.cities.edit',$id)}}" class="btn btn-warning mx-1"   >
        edit
    </a>
    <form method="POST" action="{{route('dashboard.cities.delete',$id)}}" method="POST" onsubmit="showDeleteAlert(event,'All City will be deleted ')">
        @csrf
        @method("DELETE")
    <button  class="btn btn-danger mx-1" >
        delete
    </button>
    </form>
    <form method="GET" action="{{route('dashboard.cities.create')}}" >
        @csrf
        
    <button  class="btn btn-success mx-1" >
        Add
    </button>
    </form>
</div>
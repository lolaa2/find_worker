@props(['route','name','icon'])
<li class="nav-item">
  @php
    $active_route =Str::beforeLast($route,'.');
    $active_route = $active_route!='dashboard'?$active_route.'.*':$route;    
  @endphp

    <a class="nav-link text-white @if(Route::is($active_route)) active  bg-gradient-primary  @endif" href="{{route($route)}}">
      <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
        <i class="material-icons opacity-10">{{$icon}}</i>
      </div>
      <span class="nav-link-text ms-1">{{$name}}</span>
    </a>
  </li>
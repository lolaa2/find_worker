@extends("layouts.main")   

@push('css')
<link
rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
/>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush

@section("content")


<div class="row gap-1 ">
               
    <div class="card col-9">
        <div class="card-header">
            <h4 class="h">Requests</h4>
        </div>
        <div class="table-responsive p-2">

          {{ $dataTable->table() }}

        </div>
    </div>
    <div class="card col-3" style="width: 18rem;">
 
        <div class="card-img-top">
            <div class="swiper ">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper ">
            
                    
                @foreach ($service->images as $image)
                <div class="swiper-slide ">
                    <div class="d-flex justify-content-center w-100">
    
                    <img style="width: 100%;height:100%;object-fit:contain" src="{{URL::asset($image->path)}}"/>
                    </div>
                </div>
    
                
                @endforeach
                
                </div>
                <!-- If we need pagination -->
                <div class="swiper-pagination"></div>
            
        
            
            </div>
        </div>
        <div class="card-body">
            <h6 class="card-title d-flex gap-5 justify-content-between w-100" style="color:rgb(137, 137, 137)"> 
                <small ><i class="fa mr-1 fa-user" style="color:grey"></i>  {{$service->user?->name}} <small> 
                    <small ><i class="fa mr-1 fa-clock" style="color:grey"></i>  {{$service->created_at->diffForHumans()}} <small> 

            </h6>
          
          <h5 class="card-title"><i class="fa mr-1 fa-info-circle" style="color:grey"></i>  {{$service->name}}</h5>
          
          <p class="card-text">
            <i class="fa mr-1 fa-paragraph" style="color:grey"></i>   {{$service->description}}
    
          </p>
          
        </div>
    </div>
    
</div>




@endsection

@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
    <script>
                    const swiper = new Swiper('.swiper', {
            // Optional parameters
        
            loop: true,

            // If we need pagination
            pagination: {
                el: '.swiper-pagination',
            },

            // Navigation arrows
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },

            // And if we need scrollbar
            scrollbar: {
                el: '.swiper-scrollbar',
            },
            });
    </script>
@endpush
<?php

/*

type: layout

name: Categories 2

position: 2

categories: Categories

*/

?>





<section>
    <div class="container">
        <div class="row g-4">



            @if($categories->count())
            @foreach($categories as $category)


            <!-- Category item -->
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="card card-body shadow rounded-3">
                    <div class="d-flex align-items-center">
                        <!-- Icon -->
                        <div class="icon-lg text-white bg-opacity-10 rounded-circle"  style="background-color: {{$category->bg_color}};">  <i class="la {{$category->icon_class}}"></i></div>
                        <div class="ms-3">
                            <h5 class="mb-0"><a href="{{route('category_view', $category->slug)}}" class="stretched-link">{{$category->category_name}}</a></h5>
                        </div>
                    </div>
                </div>
            </div>


        @endforeach

            @endif




    </div>
</section>


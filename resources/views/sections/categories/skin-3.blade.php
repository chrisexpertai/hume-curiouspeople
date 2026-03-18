<?php

/*

type: layout

name: Categories 3

position: 3

categories: Categories

*/
?>




<section>
    <div class="container">
        <!-- Title -->
        <div class="row mb-4">
            <div class="col-lg-8 text-center mx-auto">
                <h2 class="fs-1">Top Courses for IT</h2>
                <p class="mb-0">Information Technology Courses to expand your skills and boost your career &amp; salary
                </p>
            </div>
        </div>

        <div class="row g-4">

            @if ($categories->count())
                @foreach ($categories as $category)
                    <div class="col-md-4">
                        <div class="category-item-name">

                            <a href="{{ route('category_view', $category->slug) }}"
                                style="background-color: {{ $category->bg_color }};"
                                class="py-4 d-block text-center text-white mb-3 ">
                                {{-- <i class="la {{ $category->icon_class }}"></i>  --}}
                                {{ $category->category_name }}
                            </a>
                        </div>
                    </div>
                @endforeach

            @endif


        </div> <!-- Row END -->
    </div>
</section>

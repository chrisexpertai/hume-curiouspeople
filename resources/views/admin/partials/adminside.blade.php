@php
$nav_items = admin_settngs_menu();
@endphp




<div class="col-xl-3">
    <!-- Tab START -->
    <ul class="nav nav-pills nav-tabs-bg-dark flex-column" role="tablist">

        @if(is_array($nav_items) && count($nav_items))
        @foreach($nav_items as $route => $nav_item)




        <li class="nav-item" role="presentation">
            <a class="nav-link {{ array_get($nav_item, 'is_active') ? 'active' : '' }}" href="{!! array_get($nav_item, 'link') !!}">
                <i class=""></i>{{ array_get($nav_item, 'name') }}
            </a>
        </li>


        @endforeach
        @endif
    </ul>
    <!-- Tab END -->
</div>

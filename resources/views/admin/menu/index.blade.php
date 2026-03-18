
@extends('layouts.admin')

@section('content')


<div class="page-content-wrapper border">

    <!-- Title -->
    <div class="row">
        <div class="col-12 mb-3">
            <h1 class="h3 mb-2 mb-sm-0">{{ tr('Admin Settings') }}</h1>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left side START -->
      @include('admin.partials.pikserasettings')
        <!-- Left side END -->

	<!-- Right side START -->
    <div class="col-xl-9">

        <!-- Tab Content START -->
        <div class="tab-content">


                <!-- Personal Information content START -->
                <div class="tab-pane show active" id="tab-1" role="tabpanel">
                    <div class="card shadow">

                        <!-- Card header -->
                        <div class="card-header border-bottom">
                            <h5 class="card-header-title">{{ tr('Website Settings') }}</h5>
                        </div>

                        <!-- Card body START -->
                        <div class="card-body">

    <div class="container">
        <h2>Menus</h2>

        <div class="mb-3">
            <a href="{{ route('menus.create') }}" class="btn btn-primary">Create New Menu</a>
        </div>
        <form id="sortableForm" action="{{ route('menus.updateOrder') }}" method="POST">
            @csrf
            <ul class="list-group" id="sortable">

                @php
                $sortedMenus = $menus->sortBy('order');
                 @endphp



                @foreach($sortedMenus as $menu)
                <li class="list-group-item" data-id="{{ $menu->id }}">
                        <div class="row">
                            <div class="col-md-8" style="cursor: move;">
                                {{ $menu->name }}
                            </div>
                            <div class="col-md-2">
                                <input type="hidden" name="sortOrder[]" class="form-control" value="{{ $menu->order }}">
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('menus.edit', $menu->id) }}" class="btn btn-primary">{{ tr('Edit') }}</a>
                                <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this menu?')">{{ tr('Delete') }}</button>
                                </form>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            <button type="button" class="btn btn-success mt-3" id="saveOrderBtn">Save Order</button>
        </form>
    </div>


                        <!-- Card body END -->

                    </div>
                </div>
                <!-- Personal Information content END -->

        </div>
        <!-- Tab Content END -->
    </div>
    <!-- Right side END -->
    </div> <!-- Row END -->



    <!-- Include SortableJS and Bootstrap in the head section of your HTML -->
     <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
    <script src="/assets/vendor/jquery/jquery-3.7.1.min.js"></script>

    <script>
     document.addEventListener('DOMContentLoaded', function () {
    var sortable = new Sortable(document.getElementById('sortable'), {
        animation: 150,
        onUpdate: function (evt) {
            updateOrder(sortable.toArray());
        }
    });

    $("#saveOrderBtn").click(function () {
        var newOrder = [];
        $('#sortable li').each(function (index) {
            newOrder.push($(this).data('id'));
        });
        updateOrder(newOrder);
    });
});

function updateOrder(newOrder) {
    // Make the order values unique and sequential
    newOrder = Array.from(new Set(newOrder));

    $.ajax({
        type: "POST",
        url: $("#sortableForm").attr("action"),
        data: { sortOrder: newOrder, _token: "{{ csrf_token() }}" },
        success: function (response) {
            console.log(response);
            alert("Menu order updated successfully!");
        },
        error: function (error) {
            console.error(error);
            alert("Failed to update menu order!");
        },
    });
}

    </script>
@endsection

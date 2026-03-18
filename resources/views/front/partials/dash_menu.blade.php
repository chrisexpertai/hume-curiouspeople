<div class="col-xl-3">
    <!-- Responsive offcanvas body START -->
    <nav class="navbar navbar-light navbar-expand-xl mx-0">
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <!-- Offcanvas header -->
            <div class="offcanvas-header bg-light">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">{{ tr('My profile') }}</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <!-- Offcanvas body -->
            <div class="offcanvas-body p-3 p-xl-0">
                <div class="bg-dark border rounded-3 pb-0 p-3 w-100">
                    <div class="menu-container mobile-scroll"> <!-- Added container for scrolling only on mobile -->
                        @php
                            $menus = dashboard_menu();
                            $menus1 = instructor_menu();
                            $menus2 = student_menu();
                        @endphp

                        @if (is_array($menus) && count($menus))
                            @foreach ($menus as $key => $dashboard_menu)
                                <div class="list-group list-group-dark list-group-borderless">
                                    <a class="list-group-item {{ array_get($dashboard_menu, 'is_active') ? 'active' : '' }}"
                                        href="{{ route($key) }}">
                                        <i class="{!! array_get($dashboard_menu, 'icon') !!} me-2"></i>{!! array_get($dashboard_menu, 'name') !!}
                                    </a>
                                </div>
                            @endforeach
                        @endif

                        <!-- Instructor Dashboard menu -->
                        @if (is_array($menus1) && count($menus1))
                            @foreach ($menus1 as $key => $instructor_menu)
                                <div class="list-group list-group-dark list-group-borderless">
                                    <a class="list-group-item {{ array_get($instructor_menu, 'is_active') ? 'active' : '' }}"
                                        href="{{ route($key) }}">
                                        <i class="{!! array_get($instructor_menu, 'icon') !!} me-2"></i>{!! array_get($instructor_menu, 'name') !!}
                                    </a>
                                </div>
                            @endforeach
                        @endif

                        @if ($user->is_instructor)
                            <hr>
                        @endif

                        @if (is_array($menus2) && count($menus2))
                            @foreach ($menus2 as $key => $student_menu)
                                <div class="list-group list-group-dark list-group-borderless">
                                    <a class="list-group-item {{ array_get($student_menu, 'is_active') ? 'active' : '' }}"
                                        href="{{ route($key) }}">
                                        <i class="{!! array_get($student_menu, 'icon') !!} me-2"></i>{!! array_get($student_menu, 'name') !!}
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <!-- Responsive offcanvas body END -->
</div>


<style>
    @media (max-width: 767.98px) {
        .mobile-scroll {
            max-height: calc(100vh - 150px);
            /* Adjust according to your needs */
            overflow-y: auto;
        }
    }
</style>

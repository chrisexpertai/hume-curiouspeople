<div class="accordion accordion-icon accordion-bg-light" id="accordionExample2">

    @foreach($course->sections as $section)
    <!-- Item -->
    <div class="accordion-item mb-3">
        <h6 class="accordion-header font-base" id="heading-{{ $section->id }}">
            <a class="accordion-button fw-bold rounded collapsed d-block" href="#collapse-{{ $section->id }}" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $section->id }}" aria-expanded="true" aria-controls="collapse-{{ $section->id }}">
                <span class="mb-0"> {{$section->section_name}}</span>
                <span class="small d-block mt-1">({{$section->items->count()}} {{__t('lectures')}})</span>
            </a>
        </h6>
        <div id="collapse-{{ $section->id }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $section->id }}" data-bs-parent="#accordionExample2">
            <div class="accordion-body mt-3">
                <div class="vstack gap-3">

                    @if($section->items->count())
                    @foreach($section->items as $item)

                        <!-- Course lecture -->
                        <div class="d-flex custom-border justify-content-between align-items-center">
                            <!-- Item icon -->
                            <a href="{{route('single_'.$item->item_type, [$course->slug, $item->id ] )}}" class="icon-md mb-0 position-static flex-shrink-0 text-body">
                                {!! $item->icon_html !!}
                            </a>
                             
                            <!-- Title container -->
                            <div class="d-flex align-items-center justify-content-center flex-grow-1">
                                <a href="{{route('single_'.$item->item_type, [$course->slug, $item->id ] )}}" class="text-center text-truncate mb-0 h6 fw-normal">
                                    {{clean_html($item->title)}}
                                </a>
                            </div>

                            <!-- Runtime -->
                            <p class="mb-0 text-truncate">{{$item->runtime}}</p>

                            <!-- Preview or Resume button based on item status -->
                            <div>
                                @if($item->is_preview)
                                    <!-- Preview button -->
                                    <a class="btn btn-sm btn-primary mb-0"  href="{{route('single_lecture', [$course->slug, $item->id ] )}}">
                                        <span class="d-none d-sm-block">{{__t('preview')}}</span>
                                        <span class="d-sm-none"><i class="bi bi-play-fill"></i></span>
                                    </a>
                                @elseif($isEnrolled)
                                    <!-- Resume button -->
                                    <a class="btn btn-sm btn-success mb-0"  href="{{route('single_'.$item->item_type, [$course->slug, $item->id ] )}}">
                                        <span class="d-none d-sm-block">{{__t('resume')}}</span>
                                        <span class="d-sm-none"><i class="bi bi-play-fill"></i></span>
                                    </a>
                                @endif
                            </div>

                        </div>

                    @endforeach
                @endif

                </div>
            </div>
        </div>
    </div>

    @endforeach

</div>

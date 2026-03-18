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
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="position-relative d-flex align-items-center">
                            <a href="{{route('single_'.$item->item_type, [$course->slug, $item->id ] )}}" class="btn btn-danger-soft btn-round btn-sm mb-0 stretched-link position-static">
                                <i class="fas fa-play me-0"></i>
                            </a>
                            <span class="d-inline-block text-truncate ms-2 mb-0 h6 fw-light w-200px">{{clean_html($item->title)}}</span>
                        </div>
                        <p class="mb-0 text-truncate">{{$item->runtime}}</p>
                    </div>

                    @endforeach
                    @endif

                </div>
            </div>
        </div>
    </div>

    @endforeach

</div>

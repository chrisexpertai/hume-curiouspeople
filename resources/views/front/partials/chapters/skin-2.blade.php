

        @if($course->sections->count())

            <!-- Accordion START -->
            <div class="accordion accordion-icon accordion-border" id="accordionExample2">
                <!-- Item -->

                @foreach($course->sections as $section)

                <div class="accordion-item mb-3">
                    <h6 class="accordion-header font-base" id="heading-{{$section->id}}">
                        <button class="accordion-button fw-bold rounded d-flex collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{$section->id}}" aria-expanded="false" aria-controls="collapse-{{$section->id}}">
                            {{$section->section_name}}
                            <span class="small ms-2">{{$section->created_at->format('D j. F Y')}}</span>
                            <span class="small ms-0 ms-sm-2 d-none d-sm-block">({{$section->items->count()}} Items)</span>

                            {{-- <!-- Mark button -->
                            <span class="text-secondary ms-auto pe-4" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="Mark as completed">
                                <i class="bi bi-check-circle-fill"></i>
                            </span> --}}
                        </button>
                    </h6>

                    <div id="collapse-{{$section->id}}" class="accordion-collapse collapse" aria-labelledby="heading-{{$section->id}}" data-bs-parent="#accordionExample">
                        <div class="accordion-body mt-3">

                            @if($section->items->count())
                              @foreach($section->items as $item)
                            <!-- Course slide -->
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="position-relative d-flex align-items-center">
                                    <div class="d-flex align-items-center">
                                        <!-- Video button -->
                                        <a href="{{route('single_'.$item->item_type, [$course->slug, $item->id ] )}}" class="icon-md mb-0 position-static flex-shrink-0 text-body">
                                            {!! $item->icon_html !!}
                                       </a>

                                        <!-- Content -->
                                        <div class="ms-3">
                                            <a href="{{route('single_'.$item->item_type, [$course->slug, $item->id ] )}}" class="d-inline-block text-truncate mb-0 h6 fw-normal w-100px w-sm-200px w-md-400px"">
                                                {{clean_html($item->title)}}
                                            </a>
                                            {{$item->runtime}}
                                            @if($item->attachments->count())
                                    <span class="color  bg-sec section-item-attachments mr-3" data-toggle="tooltip" title="{{__t('dl_resource_available')}}">
                                    <i class="color  bg-sec la la-paperclip"></i>
                                    </span>
                                    @endif

                                        </div>

                                    </div>
                                </div>
                                <!-- Actions -->
                                <div class="hstack gap-2 align-items-center">

                                    @if($item->is_preview)


                                    <!-- Resume button -->
                                    <a class="btn btn-sm btn-primary mb-0"  href="{{route('single_lecture', [$course->slug, $item->id ] )}}">
                                        <span class="d-none d-sm-block">{{__t('preview')}}</span>
                                        <span class="d-sm-none"><i class="bi bi-play-fill"></i></span>
                                    </a>

                                    @endif

                                    <?php
                                    if ($isEnrolled) {
                                    ?>

                                    <!-- Resume button -->
                                    <a class="btn btn-sm btn-success mb-0"  href="{{route('single_'.$item->item_type, [$course->slug, $item->id ] )}}">
                                        <span class="d-none d-sm-block">{{__t('resume')}}</span>
                                        <span class="d-sm-none"><i class="bi bi-play-fill"></i></span>
                                    </a>

                                    <?php
                                }
                                ?>





                                </div>
                            </div>

                            <hr> <!-- Divider -->


                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
            @endif

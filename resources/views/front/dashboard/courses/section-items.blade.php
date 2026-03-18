@if($section->items->count())
    @foreach($section->items as $item)

        <div id="section-item-{{$item->id}}" class="edit-curriculum-item mb-2 edit-curriculum-{{$item->item_type}}">

            <div class="section-item-top border bg-light p-3 d-flex">
                <div class="section-item-title">
                    {!! $item->icon_html !!}
                    <span class="section-item-title-text">{{$item->title}}</span>
                </div>

                <button class="section-item-btn-tool btn px-1 py-0 section-item-edit-btn" data-item-id="{{$item->id}}" ><i class="la la-pencil"></i> </button>
                <button class="section-item-btn-tool text-danger btn ml-2 px-1 py-0 section-item-delete-btn" data-item-id="{{$item->id}}" ><i class="la la-trash"></i> </button>

                <p class="section-item-btn-tool m-0 btn ml-2 px-1 py-0 section-item-sorting-bar ml-auto" ><i class="la la-bars"></i> </p>
            </div>

            <div class="section-item-edit-form-wrap"></div>

        </div>
    @endforeach

@else
    <p class="m-0 text-muted">{{__t('section_empty_text')}}</p>
@endif



<script>
    /**
     * Start Sorting Curriculum item
     */

    function dashboard_sections_items_sort() {
        if (jQuery().sortable) {
            $("#dashboard-curriculum-sections-wrap").sortable({
                handle: ".section-move-handler",
                start: function (e, ui) {
                    ui.placeholder.css('visibility', 'visible');
                },
                stop: function (e, ui) {
                    sorting_contents();
                },
            });
            $(".dashboard-section-body").sortable({
                connectWith: ".dashboard-section-body",
                handle: ".section-item-top",
                items: "div.edit-curriculum-item",
                start: function (e, ui) {
                    ui.placeholder.css('visibility', 'visible');
                },
                stop: function (e, ui) {
                    sorting_contents();
                },
            });
        }
    }
    dashboard_sections_items_sort();

    /**
     * Shorting Sections and It's item
     */
    function sorting_contents(){
        var sections = {};
        $('.dashboard-course-section').each(function(index, item){
            var $section = $(this);
            var topics_id = parseInt($section.attr('id').match(/\d+/)[0], 10);

            var items = {};
            $section.find('.edit-curriculum-item').each(function(lessonIndex, lessonItem){
                items[lessonIndex] = parseInt($(this).attr('id').match(/\d+/)[0], 10);
            });
            sections[index] = { 'section_id' : topics_id, 'item_ids' : items };
        });

        $.post(pageData.routes.curriculum_sort, { sections : sections, _token : pageData.csrf_token });
    }

    //END: Sorting
</script>

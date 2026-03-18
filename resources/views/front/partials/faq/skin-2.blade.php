


  @php

$faqs = \App\Models\Faq::where('course_id', $course->id)->get();

  @endphp

     <div class="col-12">
      <div class="card border rounded-3">
          <!-- Card header START -->
          <div class="card-header border-bottom">
              <h3 class="mb-0">{{ tr('Frequently Asked Questions') }}</h3>
          </div>
          <!-- Card header END -->

          <!-- Card body START -->
          <div class="card-body">
              <!-- FAQ item -->

              @forelse($faqs as $faq)

              <div>
                  <h6 class="mb-3">{{ $faq->question }}</h6>
                  <p class="mb-3">{{ $faq->answer }}</p>
              </div>



          @empty

          <div class="col-12">
            <p>{{ tr('No FAQs found.') }}</p>
        </div>
      @endforelse

    </div>

          <!-- Card body START -->
      </div>
  </div>
  <!-- FAQs END -->





   <!-- Title -->
   <h5 class="mb-3">Frequently Asked Questions</h5>
   <!-- Accordion START -->

@php
       $user = auth()->user();

     $faqs = \App\Models\Faq::where('course_id', $course->id)
                   ->where('user_id', $user->id)
                   ->get();
@endphp


<div class="accordion accordion-flush" id="accordionExample">


       @forelse($faqs as $faq)




       <div class="accordion-item">
           <h2 class="accordion-header" id="heading{{ $faq->question[0] }}">
               <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $faq->question[0] }}" aria-expanded="true" aria-controls="collapse{{ $faq->question[0] }}">
                    <span class="h6 mb-0">{{ $faq->question }}</span>
               </button>
           </h2>
           <div id="collapse{{ $faq->question[0] }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $faq->question[0] }}" data-bs-parent="#accordionExample">
               <div class="accordion-body pt-0">
                   {{ $faq->answer }}
               </div>
           </div>
        </div>


       @empty
       <div class="col-12">
           <p>{{ tr('No FAQs found.') }}</p>
       </div>

   @endforelse
   <!-- Accordion END -->






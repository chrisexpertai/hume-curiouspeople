

<?php

use App\Models\Course;

$new_courses = Course::publish()->orderBy('created_at', 'desc')->take(12)->get();

?>




<?php if ($new_courses->count()): ?>
<section class="pb-0 pb-md-5">
	<div class="container">
		<!-- Title -->
		<div class="row mb-4">
			<h2 class="mb-0">{{ tr('New') }} <span class="text-warning">{{ tr('Courses') }}</span> {{ tr('Available') }}</h2>
		</div>
		<div class="row">
			<!-- Slider START -->
			<div class="tiny-slider arrow-round arrow-creative arrow-blur arrow-hover">
				<div class="tns-outer" id="tns2-ow"><div class="tns-liveregion tns-visually-hidden" aria-live="polite" aria-atomic="true">{{ tr('slide') }} <span class="current">6 to 8</span> {{ tr(' of 5') }}</div><div id="tns2-mw" class="tns-ovh"><div class="tns-inner" id="tns2-iw"><div class="tiny-slider-inner  tns-slider tns-carousel tns-subpixel tns-calc tns-horizontal" data-autoplay="false" data-arrow="true" data-dots="false" data-items-xl="3" data-items-md="2" data-items-xs="1" id="tns2" style="transition-duration: 0s; transform: translate3d(-33.3333%, 0px, 0px);">
				<?php foreach ($new_courses as $course): ?>

				<div class="card bg-transparent tns-item tns-slide-cloned" aria-hidden="true" tabindex="-1">
						<div class="position-relative">
							<!-- Image -->
							<img src="<?php echo $course->thumbnail_url; ?>" href="<?php echo route('course', $course->slug); ?>" class="card-img" alt="course image">
							<!-- Overlay -->
							<div class="card-img-overlay d-flex align-items-start flex-column p-3">
								<div class="w-100 mt-auto">
									<!-- Category -->
									<a href="#" class="badge text-bg-white fs-6 rounded-1"><i class="far fa-calendar-alt text-orange me-2"></i><?php echo date("F d, Y", strtotime($course->created_at)); ?></a>
								</div>
							</div>
						</div>

						<!-- Card body -->
						<div class="card-body px-2">
							<!-- Title -->
							<h5 class="card-title"><a href="<?php echo route('course', $course->slug); ?>"><?php echo $course->title; ?></a></h5>
							<!-- Address and button -->
							<div class="d-flex justify-content-between align-items-center">
								<address class="mb-0"><i class="fas fa-map-marker-alt me-2"></i><?php echo $course->author->name; ?> <?php echo $course->author->last_name; ?></address>
								<a href="#" class="btn btn-sm btn-primary-soft mb-0">{{ tr('Join now') }}</a>
							</div>
						</div>
					</div>

					<?php endforeach; ?>

					</div></div></div></div><div class="tns-controls" aria-label="Carousel Navigation" tabindex="0"><button type="button" data-controls="prev" tabindex="-1" aria-controls="tns2"><i class="fas fa-chevron-left"></i></button><button type="button" data-controls="next" tabindex="-1" aria-controls="tns2"><i class="fas fa-chevron-right"></i></button></div></div>
			</div>
			<!-- Slider END -->
		</div>
	</div>
</section>

<?php endif; ?>

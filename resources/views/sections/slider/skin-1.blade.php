
<?php
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

$auth_user = auth()->user();
$featured_courses = Course::publish()->whereIsFeatured(1)->orderBy('featured_at', 'desc')->take(6)->get();

 ?>










<section class="pb-5 pt-0 pt-lg-5">
	<div class="container">
		<!-- Title -->
		<div class="row mb-4">
			<div class="col-lg-8 mx-auto text-center">
				<h2 class="fs-1">{{ tr('Our Trending Courses') }}</h2>
				<p class="mb-0">{{ tr('Check out most 🔥 courses in the market') }}</p>
			</div>
		</div>
		<div class="row">
			<!-- Slider START -->
			<div class="tiny-slider arrow-round arrow-blur arrow-hover">
				<div class="tns-outer" id="tns1-ow"><div class="tns-liveregion tns-visually-hidden" aria-live="polite" aria-atomic="true">slide <span class="current">12 to 16</span>  of 4</div><div id="tns1-mw" class="tns-ovh"><div class="tns-inner" id="tns1-iw"><div class="tiny-slider-inner pb-1  tns-slider tns-carousel tns-subpixel tns-calc tns-horizontal" data-autoplay="true" data-arrow="true" data-edge="2" data-dots="false" data-items="3" data-items-lg="2" data-items-sm="1" id="tns1" style="transform: translate3d(-66.6667%, 0px, 0px);">

				<?php if ($featured_courses->count()): ?>
                    <?php foreach ($featured_courses as $course): ?>

						<?php
$isEnrolled = false;
if (Auth::check()) {
    $user = Auth::user();

    $enrolled = $user->isEnrolled($course->id);
    if ($enrolled) {
        $isEnrolled = $enrolled;
    }
} ?>


				<div class="tns-item tns-slide-cloned" aria-hidden="true" tabindex="-1">
						<div class="card action-trigger-hover border bg-transparent">
							<!-- Image -->
							<img src="<?php echo $course->thumbnail_url; ?>" href="<?php echo route('course', $course->slug); ?>" class="card-img-top" alt="course image">
							<!-- Card body -->
							<div class="card-body pb-0">
								<!-- Badge and favorite -->
								<div class="d-flex justify-content-between mb-3">
									<span class="hstack gap-2">
										<a href="#" class="badge bg-primary bg-opacity-10 text-primary"><?php
									if ($course->second_category_id) {
										$category = App\Models\Category::find($course->second_category_id);
										echo $category->category_name;
									}
									?>
										</a>
										<a href="#" class="badge text-bg-dark"><?php echo course_levels($course->level); ?></a>
									</span>
									<a href="#" class="h6 fw-light mb-0"><i class="far fa-bookmark"></i></a>
								</div>
								<!-- Title -->
								<h5 class="card-title"><a href="<?php echo route('course', $course->slug); ?>"><?php echo $course->title; ?></a></h5>
								<!-- Rating -->
								<div class="d-flex justify-content-between mb-2">
									<div class="hstack gap-2">
										<p class="text-warning m-0"><?php echo number_format($course->rating_value, 1); ?><i class="fas fa-star text-warning ms-1"></i></p>
 									</div>
									<div class="hstack gap-2">
										<p class="h6 fw-light mb-0 m-0"><?php echo number_format($course->enrolled_students); ?></p>
										<span class="small">(Enrolled Students)</span>
									</div>
								</div>
								<!-- Time -->
								<div class="hstack gap-3">
									<span class="h6 fw-light mb-0"><i class="far fa-clock text-danger me-2"></i><?php echo seconds_to_time_format($course->total_video_time); ?></span>
									<span class="h6 fw-light mb-0"><i class="fas fa-table text-orange me-2"></i><?php echo $course->total_lectures; ?> {{ tr('Lectures') }}</span>
								</div>
							</div>
							<!-- Card footer -->
							<div class="card-footer pt-0 bg-transparent">
								<hr>
								<!-- Avatar and Price -->
								<div class="d-flex justify-content-between align-items-center">
									<!-- Avatar -->
									<div class="d-flex align-items-center">
										<div class="avatar avatar-sm">
											<img class="avatar-img rounded-1" src="assets/images/avatar/04.jpg" alt="avatar">
										</div>
										<p class="mb-0 ms-2"><a href="#" class="h6 fw-light mb-0"><?php echo $course->author->name; ?> <?php echo $course->author->last_name; ?></a></p>
									</div>
									<!-- Price -->
									<div>




           <?php
if ($isEnrolled) {
?>

        <a href="<?php echo $course->continue_url; ?>" class="color btn btn-success w-100 mb-0 btn btn-primary bg-light text-black fav-enroll btn-lg btn-block">{{ tr('Continue Course') }}</a>

        <?php
} elseif ($course->free) {
?>


             <a href="<?php echo route('course', $course->slug); ?>" class="color btn btn-success w-100 mb-0 btn btn-primary bg-primary fav-enroll btn-lg btn-block">{{ tr('Free Enroll') }}</a>


    <?php
} elseif ($course->paid) {
?>

<div class="price-container">
    <a href="<?php echo route('course', $course->slug); ?>" class="btn btn-success w-100 mb-0 btn-light bg-red fav-enroll btn-lg btn-block">{{ tr('Paid Enroll') }}</a>
</div>




<?php
} elseif ($course->price_plan == 'subscription') {
?>

             <a href="<?php echo route('course', $course->slug); ?>" class="color btn btn-success w-100 mb-0 btn btn-light bg-red fav-enroll btn-lg btn-block">
                <?php echo __('Premium Enroll'); ?>
				</a>


 <?php
}
?>

									</div>
								</div>
							</div>
						</div>
					</div>
					<?php endforeach; ?>

					<?php endif; ?>



					</div></div></div></div>


 			</div>
			<!-- Slider END -->
		</div>
	</div>
</section>




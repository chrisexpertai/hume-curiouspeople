

<?php

use App\Models\Category;
use App\Models\Course;

    $title = __t('courses');
    $courses = Course::query()->publish();

	$popular_courses = Course::publish()->whereIsPopular(1)->orderBy('popular_added_at', 'desc')->take(8)->get();

    $courses = $courses->paginate(8);

?>



<section class="py-0 py-xl-5">
	<div class="container">
		<!-- Title -->
		<div class="row">
			<div class="col-lg-8 mb-4">
				<h2 class="mb-0">{{ tr('Our') }} <span class="text-warning">{{ tr('Trending') }}</span> {{ tr('Courses') }}</h2>
				<p class="mb-0">{{ tr('Check out most 🔥 courses in the market') }}</p>
			</div>
		</div>

		<div class="row g-4">
			<!-- Card Item START -->



			<?php if ($popular_courses->count()): ?>
    <?php $counter = 0; ?>
    <?php foreach ($popular_courses as $course): ?>
        <?php if ($counter >= 6) break; ?> <!-- Added line to break the loop after 6 iterations -->

			<div class="col-md-6 col-xl-4">
				<div class="card p-2 shadow h-100">
					<div class="rounded-top overflow-hidden">
						<div class="card-overlay-hover">
							<!-- Image -->
							<img src="<?php echo $course->thumbnail_url; ?>" href="<?php echo route('course', $course->slug); ?>" class="card-img-top" alt="course image">
						</div>
					<!-- Hover element -->
                    <div class="card-img-overlay">
                        <div class="card-element-hover d-flex justify-content-end">


                            <form action="{{route('add_to_cart')}}" class="color bg link enroll-btn" method="post">
                                @csrf
                                <input type="hidden" name="course_id" value="{{$course->id}}">
                                <button type="submit" class="btn btn-warning" name="cart_btn" value="buy_now" >                                        <i class="fas fa-shopping-cart text-danger"></i>
                                </button>
                             </form>
                        </div>
                    </div>
					</div>
					<!-- Card body -->
					<div class="card-body">
						<!-- Rating and avatar -->
						<div class="d-flex justify-content-between">
							<!-- Rating and info -->
							<ul class="list-inline hstack gap-2 mb-0">
								<!-- Info -->
								<li class="list-inline-item d-flex justify-content-center align-items-center">
									<div class="icon-md bg-orange bg-opacity-10 text-orange rounded-circle"><i class="fas fa-user-graduate"></i></div>
									<a href="<?php echo route('profile', $course->author->id); ?>" class="h6 fw-light mb-0 ms-2"><?php echo $course->author->name . ' ' . $course->author->last_name; ?></a>
								</li>
								<!-- Rating -->
								<li class="list-inline-item d-flex justify-content-center align-items-center">
									<div class="icon-md bg-warning bg-opacity-15 text-warning rounded-circle"><i class="fas fa-star"></i></div>
									<span class="h6 fw-light mb-0 ms-2"><?php echo number_format($course->rating_value, 1); ?></span>
								</li>
							</ul>
							<!-- Avatar -->
							<div class="avatar avatar-sm">
								<img class="avatar-img rounded-circle" href="<?php echo route('profile', $course->author->id); ?>" src="<?php echo $course->author->get_photo; ?>" alt="avatar">
							</div>
						</div>
						<!-- Divider -->
						<hr>
						<!-- Title -->
						<h5 class="card-title"><a href="<?php echo route('course', $course->slug); ?>"><?php echo $course->title; ?></a></h5>
						<!-- Badge and Price -->
						<div class="d-flex justify-content-between align-items-center mb-0">
							<a href="#" class="badge bg-info bg-opacity-10 text-info me-2"><i class="fas fa-circle small fw-bold"></i> <?php
							if ($course->second_category_id) {
								$category = App\Models\Category::find($course->second_category_id);
								echo $category->category_name;
							}
							?>
 							</a>
							<!-- Price -->
							<h3 class="text-success mb-0"><?php echo $course->paid_price(false, true); ?></h3>
						</div>
					</div>
				</div>
			</div>
			<!-- Card Item END -->
			<?php $counter++; ?>
    <?php endforeach; ?>
<?php endif; ?>

		</div>
		<!-- Button -->
		<div class="text-center mt-5">
			<a href="{{ route('courses') }}" class="btn btn-primary-soft mb-0">View more<i class="fas fa-sync ms-2"></i></a>
		</div>
	</div>


</section>

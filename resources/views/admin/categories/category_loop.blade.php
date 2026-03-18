<tr class="bg-category-step{{ $category->step }}">
    <td style="width: 10%;">
        <label class="form-check-label">
            <input class="" name="checked_category[]" type="checkbox" value="{{ $category->id }}" /> #{{ $category->id }}
        </label>
    </td>
    <td style="width: 70%;">
        <div class="d-flex align-items-center">
            @for($i = 0; $i <= $category->step; $i++)
                <span class="category-loop-icon me-2">


                    @if( ! $category->step)
                        @if($category->icon_class)
                            <i class="la {{$category->icon_class}}" data-bs-toggle="tooltip" title="Top Category"></i>
                        @else
                            <i class="la {{$category->icon_class}} bi-arrow-up-circle" data-bs-toggle="tooltip" title="Top Category"></i>
                        @endif
                    @endif
                    @if( $category->step == 1 && $i == 1)
                        <i class="bi bi-arrow-right-circle"></i>
                    @endif
                    @if( $category->step == 2 && $i == 2)
                        <i class="bi bi-tag" data-bs-toggle="tooltip" title="Topic"></i>
                    @endif
                </span>
            @endfor

            <span>{!! $category->category_name !!}</span>
        </div>
    </td>

    <td style="width: 20%;">
        <div class="d-flex">
            <a href="{{ route('category_edit', $category->id) }}" class="btn btn-primary btn-sm me-2"><i class="bi bi-pencil"></i> </a>
            <a href="{{ route('category_view', $category->slug) }}" class="btn btn-outline-info btn-sm" target="_blank"><i class="bi bi-eye"></i> </a>
        </div>
    </td>
</tr>

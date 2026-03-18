<?php

use Illuminate\Support\Arr;


 use App\Models\Complete;
 use App\Models\Content;
 use App\Models\Post;
 use App\Models\User;
 use App\Models\Review;
 use Carbon\Carbon;
 use Illuminate\Support\Facades\DB;


  require __DIR__.'/menu.php';
  require __DIR__.'/options.php';



if (!function_exists('cleanhtml')) {
    /**
     * Sanitize HTML content using HTML Purifier.
     *
     * @param  string  $html
     * @return string
     */
    function cleanhtml($html)
    {
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);

        return $purifier->purify($html);
    }
}

if (!function_exists('apply_piksera_filter')) {
    /**
     * Apply a piksera filter.
     *
     * @param string $tag
     * @param mixed  $value
     * @param array  $args
     *
     * @return mixed
     */
    function apply_piksera_filter($tag, $value, $args = [])
    {
        // Create an instance of pikseraHook if it doesn't exist
        if (!isset($GLOBALS['piksera_hook_instance'])) {
            $GLOBALS['piksera_hook_instance'] = new pikseraHook();
        }

        // Apply the filter using pikseraHook
        return $GLOBALS['piksera_hook_instance']->apply_filters($value, $args);
    }
}


if (!function_exists('get_current_language')) {
    /**
     * Get the current language based on session or system-wide option.
     *
     * @return string
     */
    function get_current_language()
    {
        $sessionLanguage = session('applocale');
        if ($sessionLanguage && array_key_exists($sessionLanguage, config('languages'))) {
            return $sessionLanguage;
        }

        $systemLanguage = get_option('language'); // Get the system-wide language option
        if ($systemLanguage && array_key_exists($systemLanguage, config('languages'))) {
            return $systemLanguage;
        }

        return config('app.fallback_locale');
    }
}


if (!function_exists('uploadFile')) {
    /**
     * Upload a file and return its stored path.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $destination
     * @return string|false
     */
    function uploadFile($file, $destination)
    {
        // Validate file
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        $maxFileSize = 10240; // Maximum file size in kilobytes (10 MB)

        $validationRules = [
            'file' => 'required|mimes:' . implode(',', $allowedExtensions) . '|max:' . $maxFileSize,
        ];

        $validator = \Illuminate\Support\Facades\Validator::make(['file' => $file], $validationRules);

        if ($validator->fails()) {
            // File validation failed
            return false;
        }

        // Generate a unique filename
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Store the file
        $storedPath = $file->storeAs($destination, $filename, 'public');

        if ($storedPath) {
            // File successfully stored
            return $storedPath;
        } else {
            // Error storing the file
            return false;
        }
    }



    if (!function_exists('translate')) {
        /**
         * Translate a string using Laravel's @lang directive.
         *
         * @param  string  $key
         * @return string
         */
        function translate($key)
        {
            return __($key);
        }
    }

    // app/helpers.php

/**
 * Get Media Image URL
 *
 * @param  mixed  $media
 * @return object
 */

 // In a helper file or directly in your PostController or another relevant location
function formatViews($views)
{
    if ($views < 1000) {
        return $views;
    } elseif ($views < 1000000) {
        return round($views / 1000, 1) . 'K';
    } else {
        return round($views / 1000000, 1) . 'M';
    }
}
// In a helper file or directly in your PostController or another relevant location
function estimatedReadingTime($content)
{
    // Calculate the number of words in the content
    $wordCount = str_word_count(strip_tags($content));

    // Assuming an average reading speed of 200 words per minute
    $readingSpeed = 200;

    // Calculate the estimated reading time in minutes
    $readingTime = ceil($wordCount / $readingSpeed);

    return $readingTime;
}
if (!function_exists('media_image_uri')) {
    function media_image_uri($media = null)
    {
        $sizes = config('media.size');
        $sizes['original'] = "Original Image";
        $sizes['full'] = "Original Image";

        foreach ($sizes as $img_size => $name) {
            $sizes[$img_size] = asset('assets/images/placeholder-image.png');
        }
        return (object)$sizes;

        if ($media) {
            // Replace this with the logic to fetch your media details
            // $mediaDetails = fetch_media_details($media);

            // For the purpose of this example, I'm assuming an stdClass object
            $mediaDetails = (object) [
                'slug_ext' => 'example.jpg',
            ];
            $url_path = null;
            // Getting resized images
            foreach ($sizes as $img_size => $name) {
                if ($img_size === 'original' || $img_size === 'full') {
                    $thumb_size = '';
                } else {
                    $thumb_size = $img_size . '/';
                }

                $url_path = asset("uploads/images/{$thumb_size}" . $mediaDetails->slug_ext);
                $sizes[$img_size] = $url_path;
            }
        }

        return (object)$sizes;
    }
}


if (! function_exists('array_add')) {
    /**
     * Add an element to an array using "dot" notation if it doesn't exist.
     *
     * @param  array  $array
     * @param  string  $key
     * @param  mixed  $value
     * @return array
     */
    function array_add($array, $key, $value)
    {
        return Arr::add($array, $key, $value);
    }
}

if (! function_exists('array_collapse')) {
    /**
     * Collapse an array of arrays into a single array.
     *
     * @param  array  $array
     * @return array
     */
    function array_collapse($array)
    {
        return Arr::collapse($array);
    }
}

if (! function_exists('array_divide')) {
    /**
     * Divide an array into two arrays. One with keys and the other with values.
     *
     * @param  array  $array
     * @return array
     */
    function array_divide($array)
    {
        return Arr::divide($array);
    }
}

if (! function_exists('array_dot')) {
    /**
     * Flatten a multi-dimensional associative array with dots.
     *
     * @param  array  $array
     * @param  string  $prepend
     * @return array
     */
    function array_dot($array, $prepend = '')
    {
        return Arr::dot($array, $prepend);
    }
}

if (! function_exists('array_except')) {
    /**
     * Get all of the given array except for a specified array of keys.
     *
     * @param  array  $array
     * @param  array|string  $keys
     * @return array
     */
    function array_except($array, $keys)
    {
        return Arr::except($array, $keys);
    }
}

if (! function_exists('array_first')) {
    /**
     * Return the first element in an array passing a given truth test.
     *
     * @param  array  $array
     * @param  callable|null  $callback
     * @param  mixed  $default
     * @return mixed
     */
    function array_first($array, callable $callback = null, $default = null)
    {
        return Arr::first($array, $callback, $default);
    }
}

if (! function_exists('array_flatten')) {
    /**
     * Flatten a multi-dimensional array into a single level.
     *
     * @param  array  $array
     * @param  int  $depth
     * @return array
     */
    function array_flatten($array, $depth = INF)
    {
        return Arr::flatten($array, $depth);
    }
}

if (! function_exists('array_forget')) {
    /**
     * Remove one or many array items from a given array using "dot" notation.
     *
     * @param  array  $array
     * @param  array|string  $keys
     * @return void
     */
    function array_forget(&$array, $keys)
    {
        Arr::forget($array, $keys);
    }
}

if (! function_exists('array_get')) {
    /**
     * Get an item from an array using "dot" notation.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function array_get($array, $key, $default = null)
    {
        return Arr::get($array, $key, $default);
    }
}

if (! function_exists('array_has')) {
    /**
     * Check if an item or items exist in an array using "dot" notation.
     *
     * @param  \ArrayAccess|array  $array
     * @param  string|array  $keys
     * @return bool
     */
    function array_has($array, $keys)
    {
        return Arr::has($array, $keys);
    }
}

if (! function_exists('array_last')) {
    /**
     * Return the last element in an array passing a given truth test.
     *
     * @param  array  $array
     * @param  callable|null  $callback
     * @param  mixed  $default
     * @return mixed
     */
    function array_last($array, callable $callback = null, $default = null)
    {
        return Arr::last($array, $callback, $default);
    }
}

if (! function_exists('array_only')) {
    /**
     * Get a subset of the items from the given array.
     *
     * @param  array  $array
     * @param  array|string  $keys
     * @return array
     */
    function array_only($array, $keys)
    {
        return Arr::only($array, $keys);
    }
}

if (! function_exists('array_pluck')) {
    /**
     * Pluck an array of values from an array.
     *
     * @param  array  $array
     * @param  string|array  $value
     * @param  string|array|null  $key
     * @return array
     */
    function array_pluck($array, $value, $key = null)
    {
        return Arr::pluck($array, $value, $key);
    }
}

if (! function_exists('array_prepend')) {
    /**
     * Push an item onto the beginning of an array.
     *
     * @param  array  $array
     * @param  mixed  $value
     * @param  mixed  $key
     * @return array
     */
    function array_prepend($array, $value, $key = null)
    {
        return Arr::prepend($array, $value, $key);
    }
}

if (! function_exists('array_pull')) {
    /**
     * Get a value from the array, and remove it.
     *
     * @param  array  $array
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function array_pull(&$array, $key, $default = null)
    {
        return Arr::pull($array, $key, $default);
    }
}

if (! function_exists('array_random')) {
    /**
     * Get a random value from an array.
     *
     * @param  array  $array
     * @param  int|null  $num
     * @return mixed
     */
    function array_random($array, $num = null)
    {
        return Arr::random($array, $num);
    }
}

if (! function_exists('array_set')) {
    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param  array  $array
     * @param  string  $key
     * @param  mixed  $value
     * @return array
     */
    function array_set(&$array, $key, $value)
    {
        return Arr::set($array, $key, $value);
    }
}

if (! function_exists('array_sort')) {
    /**
     * Sort the array by the given callback or attribute name.
     *
     * @param  array  $array
     * @param  callable|string|null  $callback
     * @return array
     */
    function array_sort($array, $callback = null)
    {
        return Arr::sort($array, $callback);
    }
}

if (! function_exists('array_sort_recursive')) {
    /**
     * Recursively sort an array by keys and values.
     *
     * @param  array  $array
     * @return array
     */
    function array_sort_recursive($array)
    {
        return Arr::sortRecursive($array);
    }
}

if (! function_exists('array_where')) {
    /**
     * Filter the array using the given callback.
     *
     * @param  array  $array
     * @param  callable  $callback
     * @return array
     */
    function array_where($array, callable $callback)
    {
        return Arr::where($array, $callback);
    }
}

if (! function_exists('array_wrap')) {
    /**
     * If the given value is not an array, wrap it in one.
     *
     * @param  mixed  $value
     * @return array
     */
    function array_wrap($value)
    {
        return Arr::wrap($value);
    }
}

if (! function_exists('camel_case')) {
    /**
     * Convert a value to camel case.
     *
     * @param  string  $value
     * @return string
     */
    function camel_case($value)
    {
        return Str::camel($value);
    }
}

if (! function_exists('ends_with')) {
    /**
     * Determine if a given string ends with a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    function ends_with($haystack, $needles)
    {
        return Str::endsWith($haystack, $needles);
    }
}

if (! function_exists('kebab_case')) {
    /**
     * Convert a string to kebab case.
     *
     * @param  string  $value
     * @return string
     */
    function kebab_case($value)
    {
        return Str::kebab($value);
    }
}

if (! function_exists('snake_case')) {
    /**
     * Convert a string to snake case.
     *
     * @param  string  $value
     * @param  string  $delimiter
     * @return string
     */
    function snake_case($value, $delimiter = '_')
    {
        return Str::snake($value, $delimiter);
    }
}

if (! function_exists('starts_with')) {
    /**
     * Determine if a given string starts with a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    function starts_with($haystack, $needles)
    {
        return Str::startsWith($haystack, $needles);
    }
}

if (! function_exists('str_after')) {
    /**
     * Return the remainder of a string after a given value.
     *
     * @param  string  $subject
     * @param  string  $search
     * @return string
     */
    function str_after($subject, $search)
    {
        return Str::after($subject, $search);
    }
}

if (! function_exists('str_before')) {
    /**
     * Get the portion of a string before a given value.
     *
     * @param  string  $subject
     * @param  string  $search
     * @return string
     */
    function str_before($subject, $search)
    {
        return Str::before($subject, $search);
    }
}

if (! function_exists('str_contains')) {
    /**
     * Determine if a given string contains a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    function str_contains($haystack, $needles)
    {
        return Str::contains($haystack, $needles);
    }
}

if (! function_exists('str_finish')) {
    /**
     * Cap a string with a single instance of a given value.
     *
     * @param  string  $value
     * @param  string  $cap
     * @return string
     */
    function str_finish($value, $cap)
    {
        return Str::finish($value, $cap);
    }
}

if (! function_exists('str_is')) {
    /**
     * Determine if a given string matches a given pattern.
     *
     * @param  string|array  $pattern
     * @param  string  $value
     * @return bool
     */
    function str_is($pattern, $value)
    {
        return Str::is($pattern, $value);
    }
}

if (! function_exists('str_limit')) {
    /**
     * Limit the number of characters in a string.
     *
     * @param  string  $value
     * @param  int  $limit
     * @param  string  $end
     * @return string
     */
    function str_limit($value, $limit = 100, $end = '...')
    {
        return Str::limit($value, $limit, $end);
    }
}

if (! function_exists('str_plural')) {
    /**
     * Get the plural form of an English word.
     *
     * @param  string  $value
     * @param  int  $count
     * @return string
     */
    function str_plural($value, $count = 2)
    {
        return Str::plural($value, $count);
    }
}

if (! function_exists('str_random')) {
    /**
     * Generate a more truly "random" alpha-numeric string.
     *
     * @param  int  $length
     * @return string
     *
     * @throws \RuntimeException
     */
    function str_random($length = 16)
    {
        return Str::random($length);
    }
}

if (! function_exists('str_replace_array')) {
    /**
     * Replace a given value in the string sequentially with an array.
     *
     * @param  string  $search
     * @param  array  $replace
     * @param  string  $subject
     * @return string
     */
    function str_replace_array($search, array $replace, $subject)
    {
        return Str::replaceArray($search, $replace, $subject);
    }
}

if (! function_exists('str_replace_first')) {
    /**
     * Replace the first occurrence of a given value in the string.
     *
     * @param  string  $search
     * @param  string  $replace
     * @param  string  $subject
     * @return string
     */
    function str_replace_first($search, $replace, $subject)
    {
        return Str::replaceFirst($search, $replace, $subject);
    }
}

if (! function_exists('str_replace_last')) {
    /**
     * Replace the last occurrence of a given value in the string.
     *
     * @param  string  $search
     * @param  string  $replace
     * @param  string  $subject
     * @return string
     */
    function str_replace_last($search, $replace, $subject)
    {
        return Str::replaceLast($search, $replace, $subject);
    }
}

if (! function_exists('str_singular')) {
    /**
     * Get the singular form of an English word.
     *
     * @param  string  $value
     * @return string
     */
    function str_singular($value)
    {
        return Str::singular($value);
    }
}


if (!function_exists('__t')) {
    /**
     * Custom translation function
     *
     * @param string $key
     * @param array $replace
     * @param string|null $locale
     * @return \Illuminate\Contracts\Translation\Translator|string
     */
    function __t($key, $replace = [], $locale = null)
    {
        return app('translator')->get("frontend.$key", $replace, $locale);
    }
}



if (!function_exists('tr')) {
    /**
     * Custom translation function
     *
     * @param string $key
     * @param array $replace
     * @param string|null $locale
     * @return \Illuminate\Contracts\Translation\Translator|string
     */
    function tr($key, $replace = [], $locale = null)
    {
        return app('translator')->get("global.$key", $replace, $locale);
    }
}

if (!function_exists('__a')) {
    /**
     * Custom translation function for anchors (links)
     *
     * @param string $key
     * @param array $replace
     * @param string|null $locale
     * @return \Illuminate\Contracts\Translation\Translator|string
     */
    function __a($key, $replace = [], $locale = null)
    {
        return app('translator')->get("admin.$key", $replace, $locale);
    }
}


if (! function_exists('str_slug')) {
    /**
     * Generate a URL friendly "slug" from a given string.
     *
     * @param  string  $title
     * @param  string  $separator
     * @param  string  $language
     * @return string
     */
    function str_slug($title, $separator = '-', $language = 'en')
    {
        return Str::slug($title, $separator, $language);
    }
}

if (! function_exists('str_start')) {
    /**
     * Begin a string with a single instance of a given value.
     *
     * @param  string  $value
     * @param  string  $prefix
     * @return string
     */
    function str_start($value, $prefix)
    {
        return Str::start($value, $prefix);
    }
}

if (! function_exists('studly_case')) {
    /**
     * Convert a value to studly caps case.
     *
     * @param  string  $value
     * @return string
     */
    function studly_case($value)
    {
        return Str::studly($value);
    }
}

// helpers.php


function instructor_card($instructor, $columnClasses = 'col-md-4') {
    return view_template_part('partials.instructor_card', compact('instructor', 'columnClasses'));
}


if (! function_exists('title_case')) {
    /**
     * Convert a value to title case.
     *
     * @param  string  $value
     * @return string
     */
    function title_case($value)
    {
        return Str::title($value);
    }
}

/**
 *
 * END Laravel Helper
 */

}

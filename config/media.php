<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Setting Image Size
    |--------------------------------------------------------------------------
    | [width X height]
    |
    | Thumbnail (default 250px x 250px)
    | Thumbnail Large (default 600px x 600px)
    | Product Image resolution (default 350px x 500px max)
    | Product 2x Image resolution (default 700px x 1000px max)
    | Banner (default 900px x 350px)
    | Banner Large (default 1000px x 500px)
    | Original image resolution (unmodified)
    |
    */

    'size' => [
        'thumbnail'     => [600, 450],
        'avatar'         => [512, 512],
        'image_sm'      => [550, 450], //image small
        'image_md'      => [512, 512], //image medium
        'image_lg'      => [1000, 563], //image large, for blog
        //'full' => null, //DON'T uncomment, full size will uploaded by default.This will cause Errors!
    ],
];

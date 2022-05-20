<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
        ],
        'user_uploads' => [
            'driver' => 'local',
            'root' => public_path() . '/user',
            'url' => env('APP_URL') . '/public/user'
        ],
        'user_uploads_thumb' => [
            'driver' => 'local',
            'root' => public_path() . '/user/thumbs',
            'url' => env('APP_URL') . '/public/user/thumbs'
        ],
        'blog_uploads' => [
            'driver' => 'local',
            'root' => public_path() . '/blog',
            'url' => env('APP_URL') . '/public/blog'
        ],
        'case_study_uploads' => [
            'driver' => 'local',
            'root' => public_path() . '/case_study',
            'url' => env('APP_URL') . '/public/case_study'
        ],
        'prescription_uploads' => [
            'driver' => 'local',
            'root' => public_path() . '/prescription_images',
            'url' => env('APP_URL') . '/public/prescription_images'
        ],
        'cat_uploads' => [
            'driver' => 'local',
            'root' => public_path() . '/cat_images',
            'url' => env('APP_URL') . '/public/cat_images'
        ],
        'cat_uploads_thumb' => [
            'driver' => 'local',
            'root' => public_path() . '/cat_images/thumbs',
            'url' => env('APP_URL') . '/public/cat_images/thumbs'
        ],
        'enquiry_uploads' => [
            'driver' => 'local',
            'root' => public_path() . '/enquiry_images',
            'url' => env('APP_URL') . '/public/enquiry_images'
        ],  'product_uploads' => [
            'driver' => 'local',
            'root' => public_path() . '/product_images',
            'url' => env('APP_URL') . '/public/product_images'
        ],
        'product_uploads_thumb' => [
            'driver' => 'local',
            'root' => public_path() . '/product_images/thumbs',
            'url' => env('APP_URL') . '/public/product_images/thumbs'
        ], 'banner_uploads' => [
            'driver' => 'local',
            'root' => public_path() . '/banner_images',
            'url' => env('APP_URL') . '/public/banner_images'
        ],

		'store_img' => [
            'driver' => 'local',
            'root' => public_path() . '/store_img',
            'url' => env('APP_URL') . '/public/store_img'
        ],
		'store_img_thumb' => [
            'driver' => 'local',
            'root' => public_path() . '/store_img/thumbs',
            'url' => env('APP_URL') . '/public/store_img/thumbs'
        ],
		'license_image' => [
            'driver' => 'local',
            'root' => public_path() . '/license_image',
            'url' => env('APP_URL') . '/public/license_image'
        ],
		'license_image_thumb' => [
            'driver' => 'local',
            'root' => public_path() . '/license_image/thumbs',
            'url' => env('APP_URL') . '/public/license_image/thumbs'
        ],

        // For Device
        'device_uploads' => [
            'driver' => 'local',
            'root' => public_path() . '/images',
            'url' => env('APP_URL') . '/public/images'
        ],  'room_uploads' => [
            'driver' => 'local',
            'root' => public_path() . '/rooms',
            'url' => env('APP_URL') . '/public/rooms'
        ],'home_owner_uploads' => [
            'driver' => 'local',
            'root' => public_path() . '/home_owner',
            'url' => env('APP_URL') . '/public/home_owner'
        ],
        'ticket_attachment' => [
            'driver' => 'local',
            'root' => public_path() . '/ticket_attachment',
            'url' => env('APP_URL') . '/public/ticket_attachment'
        ],
          'support_attachment' => [
            'driver' => 'local',
            'root' => public_path() . '/support_attachment',
            'url' => env('APP_URL') . '/public/support_attachment'
        ], 'order_bank_uploads' => [
            'driver' => 'local',
            'root' => public_path() . '/order_bank_uploads',
            'url' => env('APP_URL') . '/public/order_bank_uploads'
        ],
    ],

];

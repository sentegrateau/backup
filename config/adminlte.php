<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | The default title of your admin panel, this goes into the title tag
    | of your page. You can override it per page with the title section.
    | You can optionally also specify a title prefix and/or postfix.
    |
    */

    'title' => 'Sentegrate',

    'title_prefix' => '',

    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | This logo is displayed at the upper left corner of your admin panel.
    | You can use basic HTML here if you want. The logo has also a mini
    | variant, used for the mini side bar. Make it 3 letters or so
    |
    */

    'logo' => '<b>Sentegrate</b>',

    'logo_mini' => '<b>Sentegrate</b>',

    /*
    |--------------------------------------------------------------------------
    | Skin Color
    |--------------------------------------------------------------------------
    |
    | Choose a skin color for your admin panel. The available skin colors:
    | blue, black, purple, yellow, red, and green. Each skin also has a
    | ligth variant: blue-light, purple-light, purple-light, etc.
    |
    */

    'skin' => 'blue',

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Choose a layout for your admin panel. The available layout options:
    | null, 'boxed', 'fixed', 'top-nav'. null is the default, top-nav
    | removes the sidebar and places your menu in the top navbar
    |
    */

    'layout' => null,

    /*
    |--------------------------------------------------------------------------
    | Collapse Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we choose and option to be able to start with a collapsed side
    | bar. To adjust your sidebar layout simply set this  either true
    | this is compatible with layouts except top-nav layout option
    |
    */

    'collapse_sidebar' => false,

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Register here your dashboard, logout, login and register URLs. The
    | logout URL automatically sends a POST request in Laravel 5.3 or higher.
    | You can set the request to a GET or POST with logout_method.
    | Set register_url to null if you don't want a register link.
    |
    */

    'dashboard_url' => 'admin/dashboard',

    'logout_url' => 'admin/logout',

    'logout_method' => null,

    'login_url' => 'admin/login',

    'register_url' => null,

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Specify your menu items to display in the left sidebar. Each menu item
    | should have a text and and a URL. You can also specify an icon from
    | Font Awesome. A string instead of an array represents a header in sidebar
    | layout. The 'can' is a filter on Laravel's built in Gate functionality.
    |
    */

    'menu' => [
        'MAIN NAVIGATION',
        [
            'text' => 'Dashboard',
            'url' => 'admin/dashboard',
            'icon' => 'dashboard',
            'role' => 1
        ],
        [
            'text' => 'Users',
            'icon' => 'users',
            'submenu' => [
                [
                    'text' => 'Admin User',
                    'url' => 'admin/admin-user',
                    'icon' => 'users',
                    'role' => 1
                ],
                [
                    'text' => 'Users',
                    'url' => 'admin/customers',
                    'icon' => 'users',
                    'role' => 1
                ],
            ]
        ],
        [
            'text' => 'Blog',
            'icon' => 'rss',
            'submenu' => [
                [
                    'text' => 'Category',
                    'url' => 'admin/blog-category',
                    'icon' => 'newspaper-o',
                    'role' => 1
                ],
                [
                    'text' => 'Tag',
                    'url' => 'admin/blog-tag',
                    'icon' => 'tag',
                    'role' => 1
                ],
                [
                    'text' => 'Post',
                    'url' => 'admin/blog',
                    'icon' => 'rss',
                    'role' => 1
                ]
            ],

            'role' => 1
        ],
        [
            'text' => 'Support Ticket',
            'icon' => 'rss',
            'submenu' => [
                [
                    'text' => 'Category',
                    'url' => 'admin/ticket-category',
                    'icon' => 'newspaper-o',
                    'role' => 1
                ],
                [
                    'text' => 'Ticket',
                    'url' => 'admin/support-ticket',
                    'icon' => 'newspaper-o',
                    'role' => 1
                ],
            ],

            'role' => 1
        ],
        [
            'text' => 'Case Study',
            'url' => 'admin/case_study',
            'icon' => 'newspaper-o',
            'role' => 1
        ],
        [
            'text' => 'Page',
            'url' => 'admin/page',
            'icon' => 'newspaper-o',
            'role' => 1
        ], [
            'text' => 'Coupon',
            'url' => 'admin/coupon',
            'icon' => 'newspaper-o',
            'role' => 1
        ], [
            'text' => 'Quotations',
            'url' => 'admin/drafts',
            'icon' => 'newspaper-o',
            'role' => 1
        ],
        [
            'text' => 'Kit',
            'icon' => 'newspaper-o',
            'submenu' => [
                [
                    'text' => 'Packages',
                    'url' => 'admin/package',
                    'icon' => 'newspaper-o',
                    'role' => 1
                ]
                , [
                    'text' => 'Rooms',
                    'url' => 'admin/room',
                    'icon' => 'newspaper-o',
                    'role' => 1
                ],
                [
                    'text' => 'Devices',
                    'url' => 'admin/device',
                    'icon' => 'newspaper-o',
                    'role' => 1
                ], [
                    'text' => 'Device Features',
                    'url' => 'admin/device-features',
                    'icon' => 'newspaper-o',
                    'role' => 1
                ],
            ],
            'role' => 1
        ],

        [
            'text' => 'Scene',
            'icon' => 'newspaper-o',
            'submenu' => [
                [
                    'text' => 'Auto Lights',
                    'url' => 'admin/scene/auto_lights',
                    'icon' => 'newspaper-o',
                    'role' => 1
                ]
                , [
                    'text' => 'Security',
                    'url' => 'admin/scene/security',
                    'icon' => 'newspaper-o',
                    'role' => 1
                ],
                [
                    'text' => 'Entertainment',
                    'url' => 'admin/scene/entertainment',
                    'icon' => 'newspaper-o',
                    'role' => 1
                ],
            ],
            'role' => 1
        ],

        [
            'text' => 'Banners',
            'url' => 'admin/banner',
            'icon' => 'list-alt',
            'role' => 1
        ],
        [
            'text' => 'Videos',
            'url' => 'admin/video',
            'icon' => 'newspaper-o',
            'role' => 1
        ], [
            'text' => 'Settings',
            'url' => 'admin/settings',
            'icon' => 'newspaper-o',
            'role' => 1
        ], [
            'text' => 'Standard Kits',
            'url' => 'admin/make-packages',
            'icon' => 'newspaper-o',
            'role' => 1
        ],
        [
            'text' => 'Orders',
            'url' => 'admin/orders',
            'icon' => 'shopping-cart',
            'role' => 1
        ],
        [
            'text' => 'Order Email Setting',
            'url' => 'admin/order_email_all/1',
            'icon' => 'shopping-cart',
            'role' => 1
        ],
		[
            'text' => 'Order Mail',
            'url' => 'admin/order_email_all/2',
            'icon' => 'shopping-cart',
            'role' => 1
        ],
        [
            'text' => 'Home Owner',
            'icon' => 'newspaper-o',
            'submenu' => [
                [
                    'text' => 'Functions',
                    'url' => 'admin/home-owner-settings',
                    'icon' => 'newspaper-o',
                    'role' => 1
                ], [
                    'text' => 'Relations',
                    'url' => 'admin/home-owner-settings/relations',
                    'icon' => 'newspaper-o',
                    'role' => 1
                ], [
                    'text' => 'Quotes',
                    'url' => 'admin/home-owner-quotes',
                    'icon' => 'newspaper-o',
                    'role' => 1
                ],
            ],
            'role' => 1
        ],
        [
            'text' => 'Contact Us',
            'url' => 'admin/contact-us',
            'icon' => 'newspaper-o',
            'role' => 1
        ],
        /*[
            'text' => 'News',
            'url' => 'admin/news',
            'icon' => 'newspaper-o',
            'role' => 1
        ],
        [
            'text' => 'E-Commerce',
            'icon' => 'shopping-bag',
            'submenu' => [
                [
                    'text' => 'Category',
                    'url' => 'admin/categories',
                    'icon' => 'newspaper-o',
                    'role' => 1
                ],
                [
                    'icon' => 'shopping-bag',
                    'text' => 'Brand',
                    'url' => 'admin/brand',
                    'role' => 1
                ],
                [
                    'icon' => 'tag',
                    'text' => 'Tag',
                    'url' => 'admin/tag',
                    'role' => 1
                ],
                [
                    'icon' => 'medkit',
                    'text' => 'Medicine Type',
                    'url' => 'admin/medicine-type',
                    'role' => 1
                ],
                [
                    'text' => 'Products',
                    'url' => 'admin/products',
                    'icon' => 'shopping-bag',
                    'role' => 1
                ], [
                    'text' => 'Orders',
                    'url' => 'admin/orders',
                    'icon' => 'shopping-cart',
                    'role' => 1
                ],
                [
                    'text' => 'Coupons',
                    'url' => 'admin/coupons',
                    'icon' => 'trophy',
                    'role' => 1
                ],
                [
                    'text' => 'Tax',
                    'url' => 'admin/tax',
                    'icon' => 'calculator',
                    'role' => 1
                ],
                [
                    'text' => 'Attribute',
                    'url' => 'admin/attribute',
                    'icon' => 'cog',
                    'role' => 1
                ]
            ],
            'role' => 1
        ],
        [
            'text' => 'Customers',
            'url' => 'admin/customers',
            'icon' => 'users',
            'role' => 1
        ],
        [
            'text' => 'Countries',
            'url' => 'admin/countries',
            'icon' => 'list-alt',
            'role' => 1
        ],
        [
            'text' => 'Banners',
            'url' => 'admin/banner',
            'icon' => 'list-alt',
            'role' => 1
        ],
        [
            'text' => 'Delivery Agents',
            'url' => 'admin/shipping',
            'icon' => 'truck',
            'role' => 1
        ], [
            'text' => 'Settings',
            'url' => 'admin/settings',
            'icon' => 'cog',
            'role' => 1
        ],
        [
            'text' => 'Store',
            'url' => 'admin/stores',
            'icon' => 'users',
            'role' => 1
        ],
        [
            'text' => 'Medicine Enquiry',
            'url' => 'admin/medicine-enquiry',
            'icon' => 'medkit',
            'role' => 1
        ],
        [
            'text' => 'Dashboard',
            'url' => 'admin/dashboard',
            'icon' => 'dashboard',
            'role' => 2
        ],
        [
            'text' => 'Prescription',
            'icon' => 'rss',
            'submenu' => [
                [
                    'text' => 'Active Prescriptions',
                    'url' => 'admin/prescription',
                    'icon' => 'newspaper-o',
                    'role' => 2
                ],
                [
                    'text' => 'Approved Prescription',
                    'url' => 'admin/prescription-approved',
                    'icon' => 'newspaper-o',
                    'role' => 2
                ],
                [
                    'text' => 'Rejected Prescription',
                    'url' => 'admin/prescription-rejected',
                    'icon' => 'newspaper-o',
                    'role' => 2
                ]
            ],
            'role' => 2
        ],
        [
            'text' => 'Orders',
            'url' => 'admin/orders',
            'icon' => 'shopping-cart',
            'role' => 2
        ],
        [
            'text' => 'Prescription',
            'icon' => 'rss',
            'submenu' => [
                [
                    'text' => 'Active Prescriptions',
                    'url' => 'admin/admin-active-prescription',
                    'icon' => 'newspaper-o',
                    'role' => 1
                ],
                [
                    'text' => 'Approved Prescriptions',
                    'url' => 'admin/admin-approved-prescription',
                    'icon' => 'newspaper-o',
                    'role' => 1
                ]
            ],
            'role' => 1
        ],
        [
            'text' => 'News Letters',
            'url' => 'admin/newsletters',
            'icon' => 'newspaper-o',
            'role' => 1
        ],
        [
            'text' => 'Contact List',
            'url' => 'admin/contact-list',
            'icon' => 'newspaper-o',
            'role' => 1
        ],

        /*, [
            'text' => 'Attribute Value',
            'url' => 'admin/attributeValue',
            'icon' => 'cog',
        ]*/
        /*[
            'text'        => 'Pages',
            'url'         => 'admin/pages',
            'icon'        => 'file',
            'label'       => 4,
            'label_color' => 'success',
        ],
        'ACCOUNT SETTINGS',
        [
            'text' => 'Profile',
            'url'  => 'admin/settings',
            'icon' => 'user',
        ],
        [
            'text' => 'Change Password',
            'url'  => 'admin/settings',
            'icon' => 'lock',
        ],
        [
            'text'    => 'Multilevel',
            'icon'    => 'share',
            'submenu' => [
                [
                    'text' => 'Level One',
                    'url'  => '#',
                ],
                [
                    'text'    => 'Level One',
                    'url'     => '#',
                    'submenu' => [
                        [
                            'text' => 'Level Two',
                            'url'  => '#',
                        ],
                        [
                            'text'    => 'Level Two',
                            'url'     => '#',
                            'submenu' => [
                                [
                                    'text' => 'Level Three',
                                    'url'  => '#',
                                ],
                                [
                                    'text' => 'Level Three',
                                    'url'  => '#',
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'text' => 'Level One',
                    'url'  => '#',
                ],
            ],
        ],
        'LABELS',
        [
            'text'       => 'Important',
            'icon_color' => 'red',
        ],
        [
            'text'       => 'Warning',
            'icon_color' => 'yellow',
        ],
        [
            'text'       => 'Information',
            'icon_color' => 'aqua',
        ],*/
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Choose what filters you want to include for rendering the menu.
    | You can add your own filters to this array after you've created them.
    | You can comment out the GateFilter if you don't want to use Laravel's
    | built in Gate functionality
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SubmenuFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        //JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Choose which JavaScript plugins should be included. At this moment,
    | only DataTables is supported as a plugin. Set the value to true
    | to include the JavaScript file from a CDN via a script tag.
    |
    */

    'plugins' => [
        'datatables' => true,
        'select2' => true,
        'chartjs' => true,
    ],
];

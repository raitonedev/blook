<?php

return [

    # The title in side menu
    "title" => "My Components",

    # Path where your components are stored
    "path" => 'resources/views/components/',

    # Root component group name
    "root_group_name" => "base",

    # Assets to load on all styleguide interface. Should be vite "bundle" names
    "assets" => [
        "app",
        "alpine"
    ],

    # Which components do you want to ignore on the styleguide interface
    "banlist" => [
        "asset",
        "application-logo",
    ],

    # Define your variations for components with variations
    "variations" => [
        "atoms.button" => [
            "primary" => [
                "label" => "Primary",
                "attributes" => [
                    "variation" => "primary",
                    "ofk" => "ghj",
                    "ofuio" => "uio",
                    "ofgfk" => "ghj",
                    "offgdduio" => "uio",
                    "ofdgk" => "ghj",
                    "offfduio" => "uio",
                    "ouio" => "uio",
                    "offgfgk" => "ghj",
                    "ofdffduio" => "uio",
                    "ofgfgdfdgk" => "ghj",
                    "offffddfduio" => "uio",
                    "oudfdfio" => "uio",
                    "offdk" => "ghj",
                    "ofddfdfduio" => "uio",
                    "ofdfdfdgk" => "ghj",
                    "ofdfdfffduio" => "uio",
                ]
            ],
            "danger" => [
                "label" => "Danger",
                "attributes" => [
                    "variation" => "danger"
                ]
            ]
        ]
    ]
];

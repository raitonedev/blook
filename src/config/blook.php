<?php

return [

    # The title in side menu
    "title" => "My Components",

    # Path where your components are stored
    # Subfolders will automatically be discovered
    "path" => 'resources/views/components/',

    # Group name for components in root of "path" folder
    "root_group_name" => "base",

    # Base route under which you want the interface
    # You may want to put it under "styleguide" or "design"
    "base_route" => "/blook",

    # List of envs on which you want blook (will enable or disable routes)
    # Not available on "production" by default.
    # Please be aware that blook was made for development purposes.
    "enabled_environments" => [
        "local",
        "test",
        "staging"
    ],

    # Assets to load on all styleguide interface. Should be your vite "bundle" names
    "assets" => [
        "app",
        "alpine"
    ],

    # Background colors that can be toggled on interface
    "backgrounds" => [
        [
            "id" => "light",
            "label" => "Light",
            "color" => "#F8F8F8",
        ],
        [
            "id" => "dark",
            "label" => "Dark",
            "color" => "#222222"
        ],
    ],

    # Viewports you can toggle on interface
    "viewports" => [
        [
            "id" => "desktop",
            "label" => "Desktop",
            "width" => "1280px",
            "height" => "720px",
            "scale" => 0.67,
        ],
        [
            "id" => "tablet",
            "label" => "Tablet",
            "width" => "768px",
            "height" => "1024px",
            "scale" => 0.67,
        ],
        [
            "id" => "smartphone",
            "label" => "Smartphone",
            "width" => "414px",
            "height" => "896px",
            "scale" => 0.67,
        ]
    ],

    # Which components do you want to ban from blook interface
    "banlist" => [
        # "layouts/", # Suffix slash will ban a whole folder
        # "icon", # Will ban {path}/icon.blade.php
        # "atoms.button", # Will ban {path}/atoms/button.blade.php
    ],
];

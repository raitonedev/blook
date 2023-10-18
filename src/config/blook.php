<?php

return [

    # The title in side menu
    "title" => "My Components",

    # Path where your components are stored
    "path" => 'resources/views/components/',

    # Group name for components in root of "path" folder
    "root_group_name" => "base",

    # Base route under which you want the interface
    "base_route" => "/blook",

    # List of envs on which you want blook (will enable or diable routes)
    # By default it is not made available on "production"
    "enabled_environments" => [
        "local",
        "test",
        # production,
    ],

    # Assets to load on all styleguide interface. Should be vite "bundle" names
    "assets" => [
        "app",
        "alpine"
    ],

    # Which components do you want to ignore on the styleguide interface
    "banlist" => [
        "asset",
        "button",
        "application-logo",
        "auth-card",
        "auth-session-status",
        "auth-validation-errors",
        "dropdown-link",
        "dropdown",
        "input",
        "label",
        "nav-link",
        "responsive-nav-link"
    ],
];

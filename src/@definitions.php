<?php

return [
    /* Simple example */
    /*
    "atoms.button" => [
        "default" => [ # Variation with 'default' key is a special one and lets you define fallbacks
            "slots" => [
                "slot" => "Click me button" # Slot with 'slot' key is the default one
            ],
            "attributes" => [
                "variation" => "primary",
                "type" => "button",
                "size" => "medium",
            ]
        ],
        "primary" => [
            "attributes" => [
                "variation" => "primary",
            ]
        ],
        "secondary" => [
            "attributes" => [
                "variation" => "secondary",
            ]
        ],
        "link" => [
            "slots" => [
                "slot" => "Click me link", # Will override 'default.slots.slot'
            ],
            "attributes" => [
                "type" => "href", # Will override 'default.attributes.type'
                "size" => "small", # Will override 'default.attributes.size'
                "variation" => "primary",
            ]
        ]
    ],
    */

    /* More advanced example with slots, queries and assets */
    /*
    "cards.card" => [
        "default" => [
            "slots" => [
                "heading" => view('components.cards.heading'),
                "slot" => "This is the default slot content",
                "footer" => view('components.cards.footer']),
            ],
            "assets" => [ # Will load those vite bundles in all variations of this component
                "tailwind",
                "alpine",
            ],
            "attributes" => [
                "movie" => Movie::find(1) # You can pass queries to your component if you need to
                "displayButton" => false,
                "displayImage" => false,
            ]
        ],
        "rich" => [
            "label" => "Rich Card",
            "attributes" => [
                "movie" => Movie::find(2),
                "displayButton" => true,
                "displayImage" => true,
            ]
        ],
        "animated" => [
            "label" => "Animated",
            "assets" => [
                "animatedCard"
            ],
            "attributes" => [
                "movie" => Movie::find(2),
                "displayButton" => true,
                "displayImage" => true,
            ],
        ]
    ],
    "grid" => [
        "default" => [
            "assets" => [
                "grid",
                "gridFilters",
            ],
            "attributes" => [
                "movies" => Movies::where('category', 'comedy'),
                "pagination" => true,
            ]
        ]
    ]
    */
];

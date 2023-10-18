<!DOCTYPE html>
<html class="blook-null" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Components - {{ config('app.name') }}</title>

        <!-- Loading alpine via CDN. -->
        <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <script>

                document.addEventListener('alpine:init', () => {
                    
                    Alpine.data('blook', function() {
                        return{
                            bgMenuOpen: false,
                            viewportMenuOpen: false,

                            // Persistent settings
                            colorId: this.$persist("default"),
                            viewportId: this.$persist("default"),
                            alignInMiddle: this.$persist(false),
                            showBottomPanel: this.$persist(true),

                            iframe: null,
                            workingBody: null,
                            workingCanva: null,
                            workingCanvaParent: null,

                            init(){
                                this.iframe = this.$refs.iframe;

                                this.iframe.addEventListener('load', () => {
                                    this.workingBody = this.iframe.contentWindow.document.body;
                                    this.workingCanva = this.workingBody.querySelector("#canva");
                                    this.workingCanvaParent = this.workingBody.querySelector("#canva-parent");

                                    this.workingBody.addEventListener("click", () => {
                                        this.closeMenus();
                                    });

                                    // Init interface
                                    this.changeBackground(this.colorId);
                                    this.changeViewport(this.viewportId);

                                });
                            },

                            changeBackground(colorId) {
                                this.workingBody.classList.remove('bg-' + this.colorId);
                                this.colorId = colorId;
                                this.workingBody.classList.toggle('bg-' + colorId);
                            },

                            changeViewport(viewportId) {
                                this.workingCanva.classList.remove('viewport-' + this.viewportId);
                                this.viewportId = viewportId;
                                this.workingCanva.classList.toggle('viewport-' + viewportId);
                            },

                            toggleAlignment(alignInMiddle) {
                                this.alignInMiddle = alignInMiddle;
                                this.workingCanvaParent.classList.toggle('blook-centered-canva');
                            },

                            closeMenus() {
                                this.bgMenuOpen = false;
                                this.viewportMenuOpen = false;
                            }

                        }
                    });
                });
  
        </script>

        <style>
            .blook-title{ font-weight: bold; font-size: 1.2em;}
            .blook-container{ display:flex; height: 100svh; overflow:hidden; }
            .blook-null{ margin: 0 !important; padding: 0 !important; }
            .blook-body{ height: 100vh; }
            .blook-sidebar{
                font-family: Arial, Helvetica, sans-serif;
                width: 15vw;
                background: #f8f8f8;
                border-right: 2px solid #f0f0f0;
                font-size: .8em;
                padding: 24px;
                min-height: 100svh;
                overflow-y: scroll;
            }
            .blook-variations-bloc{
                margin-top: 6px;
                margin-left: 16px;
            }
            .blook-group-name{ text-transform: capitalize;}
            .blook-menu-item{margin-top: 8px;}
            .blook-menu-item a{ text-transform: capitalize; text-decoration: none;}
            .blook-iframe{ border: none; width: 85vw; height: 100svh; overflow:hidden;}

            /* Utilites */
            .blook-flex{ display:flex; }
            .blook-tools{
                gap: 10px;
                background-color: #f8f8f8;
                display:flex;
                padding-left: 20px;
                padding-top: 10px;
            }

            .blook-tools-menu{
                min-width: 100px; padding: 12px; border: 1px solid #eee; position: absolute; background: #f8f8f8; border-radius: 4px;
            }

            .blook-tools-menu-item{
                cursor:pointer; margin-bottom: 8px; display:block; border:none; background:none;
            }

        </style>
    </head>

    <body class="blook-null blook-body" x-data="blook">

        <div class="blook-container">
            <div class="blook-sidebar">

                <div>
                    <span class="blook-title">{{ config("blook.title") }}</span>
                </div>

                @foreach($components as $group => $item)
                    @include('blook::components.group', ['group' => $group, 'items' => $item["children"]])
                @endforeach

            </div>
            <div class="blook-workspace">
                
                <div class="blook-tools">
                
                <div>
                        <div
                            @click="bgMenuOpen = !bgMenuOpen"
                            @click.outside="bgMenuOpen = false"
                        >
                            @include('blook::components.icon', ['icon' => 'backgrounds'])
                        </div>
                        <div class="blook-tools-menu" x-cloak x-transition x-show="bgMenuOpen">
                            <button class="blook-tools-menu-item" @click="changeBackground('default')">
                                Default
                            </button>
                            @foreach(config('blook.backgrounds') as $background)
                                <button class="blook-tools-menu-item" @click="changeBackground('{{ $background['id'] }}')">
                                {{ $background['label'] }}
                                </button>
                            @endforeach
                        </div>
                    </div>


                    <div>
                        <div
                            @click="viewportMenuOpen = !viewportMenuOpen"
                            @click.outside="viewportMenuOpen = false"
                        >
                        @include('blook::components.icon', ['icon' => 'viewports'])
                        </div>
                        <div class="blook-tools-menu" x-cloak x-transition x-show="viewportMenuOpen">
                            <button class="blook-tools-menu-item" @click="changeViewport('default')">
                                Default
                            </button>
                            @foreach(config('blook.viewports') as $viewport)
                                <button class="blook-tools-menu-item" @click="changeViewport('{{ $viewport['id'] }}')">
                                {{ $viewport['label'] }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div @click="toggleAlignment">
                        @include('blook::components.icon', ['icon' => 'align'])
                    </div>

                </div>

                <iframe
                    x-ref="iframe"
                    class="blook-iframe"
                    src="{{ $componentShowRoute }}"
                    title="Component Detail">
                </iframe>

            </div>
        </div>

    </body>
</html>

<!DOCTYPE html>
<html class="blook-null" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Components - {{ config('app.name') }}</title>

  <!-- Loading alpine via CDN. -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  <!-- Highlightjs for code -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/default.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>

  <script>

    hljs.highlightAll();

    document.addEventListener('alpine:init', () => {

      Alpine.data('blook', function() {
        return {
          mobileMenuOpen: false,
          bgMenuOpen: false,
          viewportMenuOpen: false,

          // Tabs & bottombar
          bottomBarActive: this.$persist(true),
          activeTab: this.$persist("context"),

          // Persistent settings
          colorId: this.$persist("default"),
          viewportId: this.$persist("default"),
          alignInMiddle: this.$persist(false),
          showBottomPanel: this.$persist(true),

          iframe: null,
          workingBody: null,
          workingCanva: null,
          workingCanvaParent: null,

          init() {
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

          toggleAlignment() {
            this.alignInMiddle = !this.alignInMiddle;
            if (this.alignInMiddle) {
              this.workingCanvaParent.classList.remove('blook-centered-canva');
            } else {
              this.workingCanvaParent.classList.add('blook-centered-canva');
            }
          },

          toggleTab(tab) {
            this.activeTab = tab;
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
    /* Code block with numbered lines */
    pre.code {
      white-space: pre-wrap;
    }

    pre.code::before {
      counter-reset: listing;
    }

    pre.code code {
      counter-increment: listing;
    }

    pre.code code::before {
      content: counter(listing) ". ";
      color: #888;
      display: inline-block;
      width: 3em;
    }

    pre code.hljs{
      padding:0;
    }

  </style>
</head>

<body x-data="blook">

  <div>
    <!-- Off-canvas menu for mobile, show/hide based on off-canvas menu state. -->
    <div class="relative z-50 lg:hidden" role="dialog" aria-modal="true">

      <div x-show="mobileMenuOpen" x-transition class="fixed inset-0 bg-gray-900/80"></div>

      <div x-show="mobileMenuOpen" x-transition class="fixed inset-0 flex">

        <div class="relative mr-16 flex w-full max-w-xs flex-1">

          <div @click="mobileMenuOpen = false" class="absolute left-full top-0 flex w-16 justify-center pt-5">
            <button type="button" class="-m-2.5 p-2.5">
              <span class="sr-only">Close sidebar</span>
              <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <!-- Sidebar component, swap this element with another sidebar if you like -->
          <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-2">
            <div class="flex h-16 shrink-0 items-center">
            <span class="font-semibold text-lg">{{ config("blook.title") }}</span>
            </div>
            <nav class="flex flex-1 flex-col">
              <ul role="list" class="flex flex-1 flex-col gap-y-7">
              <li>
                  <ul role="list" class="-mx-2 space-y-1">
                    @foreach($components as $group => $item)
                    @include('blook::components.group',[
                    'group' => $group,
                    'items' => $item["children"],
                    'id' => $loop->iteration
                    ])
                    @endforeach
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Static sidebar for desktop -->
    <div class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-72 lg:flex-col">
      <!-- Sidebar component, swap this element with another sidebar if you like -->
      <div class="flex grow flex-col gap-y-2 overflow-y-auto border-r border-gray-200 bg-white px-6">
        <div class="flex h-16 shrink-0 items-center">
          <span class="font-semibold text-lg">{{ config("blook.title") }}</span>
        </div>
        <nav class="flex flex-1 flex-col">
          <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
              <ul role="list" class="-mx-2 space-y-1">
                @foreach($components as $group => $item)
                @include('blook::components.group',[
                'group' => $group,
                'items' => $item["children"],
                'id' => $loop->iteration
                ])
                @endforeach
              </ul>
            </li>
          </ul>
        </nav>
      </div>
    </div>

    <div class="sticky top-0 z-40 flex items-center gap-x-6 bg-white px-4 py-4 shadow-sm sm:px-6 lg:hidden">
      <button @click="mobileMenuOpen = true" type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden">
        <span class="sr-only">Open sidebar</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
      </button>
      <div class="flex-1 text-sm font-semibold leading-6 text-gray-900">{{ $componentName }}</div>
      <div class="text-gray-400 text-xs">
        <a target="_blank" href="{{ $componentShowRoute }}">Open in new tab</a>
      </div>
    </div>

    <main class="lg:pl-72">
      <div>
        <div class="h-[100svh] w-full">

          <div class="border-b border-gray-300 sticky top-0 bg-white">
            
            <!-- INFOBAR -->
            <div class="hidden md:flex flex justify-between py-2 px-4">
              <div>
                <span class="font-semibold">{{ $componentName }}</span>
                @if($variation)
                - <span class="text-gray-400">[{{ $variation }}]</span>
                @else
                - <span class="text-gray-400">Default</span>
                @endif
              </div>
              <div class="text-gray-400">
                <a target="_blank" href="{{ $componentShowRoute }}">Open in new tab</a>
              </div>
            </div>

          <!-- TOOLBAR -->
          <div class="flex gap-4 pb-2 px-4">

            <div class="relative inline-block text-left">

              <div @click="bgMenuOpen = !bgMenuOpen" @click.outside="bgMenuOpen = false">
                @include('blook::components.icon', ['icon' => 'backgrounds'])
              </div>

              <div x-cloak x-transition x-show="bgMenuOpen" class="absolute right-0 z-50 mt-2 w-auto origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                <div class="py-1" role="none">
                  <button @click="changeBackground('default')" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="menu-item-0">
                    Default
                  </button>
                  @foreach(config('blook.backgrounds') as $background)
                  <button @click="changeBackground('{{ $background['id'] }}')" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="menu-item-0">
                    {{ $background['label'] }}
                  </button>
                  @endforeach
                </div>
              </div>

            </div>

            <div class="relative inline-block text-left">
              
              <div @click="viewportMenuOpen = !viewportMenuOpen" @click.outside="viewportMenuOpen = false">
                @include('blook::components.icon', ['icon' => 'viewports'])
              </div>
              <div x-cloak x-transition x-show="viewportMenuOpen" class="absolute right-0 z-50 mt-2 w-auto origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                <div class="py-1" role="none">
                  <button @click="changeViewport('default')" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="menu-item-0">
                    Default
                  </button>
                  @foreach(config('blook.viewports') as $viewport)
                  <button @click="changeViewport('{{ $viewport['id'] }}')" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem" tabindex="-1" id="menu-item-0">
                    {{ $viewport['label'] }}
                  </button>
                  @endforeach
                </div>
              </div>
            </div>

            </div>

          </div>


          <!-- WORKSPACE -->
          <iframe x-ref="iframe" class="h-full w-full overflow-scroll" src="{{ $componentShowRoute }}" title="Component Detail">
          </iframe>


          <!-- BOTTOM BAR -->
          <button x-show="! bottomBarActive" @click="bottomBarActive = !bottomBarActive" class="absolute z-50 background-white border-2 bottom-0 bg-white rounded-t-md right-4 p-2 font-semibold text-gray-400">
            Show bar
          </button>

          <div x-show="bottomBarActive" x-transition class="h-[40svh] sticky bottom-0 overflow-auto bg-white">

            <div class="flex justify-between font-semibold text-gray-400 py-3 px-5 border-t-2 border-b-2 sticky top-0 bg-white">
              <div class="flex gap-8">
                <button @click="toggleTab('context')" :class="{ 'text-black': activeTab === 'context' }">Context</button>
                <button @click="toggleTab('code')" :class="{ 'text-black': activeTab === 'code' }">Code</button>
              </div>
              <button @click="bottomBarActive = !bottomBarActive">
                Hide bar
              </button>
            </div>

            <!-- CONTEXT TAB -->
            <div x-show="activeTab == 'context'" x-transition class="w-full overflow-scroll">

              @if($attributes && count($attributes->getAttributes()) > 0)
              <dl class="divide-y divide-gray-100">
                @foreach($attributes->getAttributes() as $attr => $value)
                <div class="px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                  <dt class="text-sm font-medium leading-6 text-gray-900">{{ $attr }}</dt>
                  <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $value }}</dd>
                </div>
                @endforeach
              </dl>
              @else
              <p class="p-4">No specific context attributes provided.</p>
              @endif
            </div>

            <!-- CODE TAB -->
            <div x-show="activeTab == 'code'" x-transition class="w-full overflow-scroll">
              <pre class="code text-[10px]">@foreach(preg_split("/((\r?\n)|(\r\n?))/", $componentCode) as $line)<code>{{ $line }}</code>@endforeach</pre>
            </div>

          </div>

        </div>
      </div>
    </main>

  </div>



</body>

</html>
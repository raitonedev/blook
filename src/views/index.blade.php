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


  <!--
  This example requires updating your template:

  ```
  <html class="h-full bg-white">
  <body class="h-full">
  ```
-->
  <div>
    <!-- Off-canvas menu for mobile, show/hide based on off-canvas menu state. -->
    <div class="relative z-50 lg:hidden" role="dialog" aria-modal="true">
      <!--
      Off-canvas menu backdrop, show/hide based on off-canvas menu state.

      Entering: "transition-opacity ease-linear duration-300"
        From: "opacity-0"
        To: "opacity-100"
      Leaving: "transition-opacity ease-linear duration-300"
        From: "opacity-100"
        To: "opacity-0"
    -->
      <div class="fixed inset-0 bg-gray-900/80"></div>

      <div class="fixed inset-0 flex">
        <!--
        Off-canvas menu, show/hide based on off-canvas menu state.

        Entering: "transition ease-in-out duration-300 transform"
          From: "-translate-x-full"
          To: "translate-x-0"
        Leaving: "transition ease-in-out duration-300 transform"
          From: "translate-x-0"
          To: "-translate-x-full"
      -->
        <div class="relative mr-16 flex w-full max-w-xs flex-1">
          <!--
          Close button, show/hide based on off-canvas menu state.

          Entering: "ease-in-out duration-300"
            From: "opacity-0"
            To: "opacity-100"
          Leaving: "ease-in-out duration-300"
            From: "opacity-100"
            To: "opacity-0"
        -->
          <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
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
              <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company">
            </div>
            <nav class="flex flex-1 flex-col">
              <ul role="list" class="flex flex-1 flex-col gap-y-7">
                <li>
                  <ul role="list" class="-mx-2 space-y-1">
                    <li>
                      <!-- Current: "bg-gray-50 text-indigo-600", Default: "text-gray-700 hover:text-indigo-600 hover:bg-gray-50" -->
                      <a href="#" class="bg-gray-50 text-indigo-600 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                        <svg class="h-6 w-6 shrink-0 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        Test
                      </a>
                    </li>
                    <li>
                      <a href="#" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                        <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                        Team
                      </a>
                    </li>
                    <li>
                      <a href="#" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                        <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                        </svg>
                        Projects
                      </a>
                    </li>
                    <li>
                      <a href="#" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                        <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                        Calendar
                      </a>
                    </li>
                    <li>
                      <a href="#" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                        <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" />
                        </svg>
                        Documents
                      </a>
                    </li>
                    <li>
                      <a href="#" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                        <svg class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                        </svg>
                        Reports
                      </a>
                    </li>
                  </ul>
                </li>
                <li>
                  <div class="text-xs font-semibold leading-6 text-gray-400">Your teams</div>
                  <ul role="list" class="-mx-2 mt-2 space-y-1">
                    <li>
                      <!-- Current: "bg-gray-50 text-indigo-600", Default: "text-gray-700 hover:text-indigo-600 hover:bg-gray-50" -->
                      <a href="#" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border text-[0.625rem] font-medium bg-white text-gray-400 border-gray-200 group-hover:border-indigo-600 group-hover:text-indigo-600">H</span>
                        <span class="truncate">Heroicons</span>
                      </a>
                    </li>
                    <li>
                      <a href="#" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border text-[0.625rem] font-medium bg-white text-gray-400 border-gray-200 group-hover:border-indigo-600 group-hover:text-indigo-600">T</span>
                        <span class="truncate">Tailwind Labs</span>
                      </a>
                    </li>
                    <li>
                      <a href="#" class="text-gray-700 hover:text-indigo-600 hover:bg-gray-50 group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold">
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border text-[0.625rem] font-medium bg-white text-gray-400 border-gray-200 group-hover:border-indigo-600 group-hover:text-indigo-600">W</span>
                        <span class="truncate">Workcation</span>
                      </a>
                    </li>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </div>

    <!-- Static sidebar for desktop -->
    <div class="hidden lg:fixed lg:inset-y-0 lg:z-40 lg:flex lg:w-72 lg:flex-col">
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
      <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden">
        <span class="sr-only">Open sidebar</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
      </button>
      <div class="flex-1 text-sm font-semibold leading-6 text-gray-900">Dashboard</div>
      <a href="#">
        <span class="sr-only">Your profile</span>
        <img class="h-8 w-8 rounded-full bg-gray-50" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
      </a>
    </div>

    <main class="lg:pl-72">
      <div>
        <div class="h-[100svh] w-full">

          <div class="h-[8svh] border-b border-gray-300 sticky top-0 bg-white">
            
            <!-- INFOBAR -->
            <div class="flex justify-between py-2 px-4">
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
          <iframe x-ref="iframe" class="h-[60svh] w-full overflow-scroll" src="{{ $componentShowRoute }}" title="Component Detail">
          </iframe>


          <!-- BOTTOM BAR -->
          <button x-show="! bottomBarActive" @click="bottomBarActive = !bottomBarActive" class="absolute z-50 background-white border-2 bottom-0 right-0 p-2 font-semibold text-gray-400">
            Show bar
          </button>
          <div x-show="bottomBarActive" class="h-[32svh] sticky bottom-0 overflow-auto">

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
            <div x-show="activeTab == 'context'" class="w-full overflow-scroll">

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
            <div x-show="activeTab == 'code'" class="w-full overflow-scroll">
              <pre class="code text-[10px]">@foreach(preg_split("/((\r?\n)|(\r\n?))/", $componentCode) as $line)<code>{{ $line }}</code>@endforeach</pre>
            </div>

          </div>

        </div>
      </div>
    </main>

  </div>



</body>

</html>
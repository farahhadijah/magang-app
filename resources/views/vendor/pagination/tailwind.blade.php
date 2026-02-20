@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}">

        {{-- Mobile --}}
        <div class="flex items-center justify-between gap-2 sm:hidden">

            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-200 rounded-md cursor-not-allowed">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-green-700 transition bg-white border border-green-300 rounded-md hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-500">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-green-700 transition bg-white border border-green-300 rounded-md hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-500">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-200 rounded-md cursor-not-allowed">
                    {!! __('pagination.next') !!}
                </span>
            @endif

        </div>

        {{-- Desktop --}}
        <div class="hidden sm:flex sm:items-center sm:justify-between">

            <div>
                <span class="inline-flex rounded-md shadow-sm">

                    {{-- Previous --}}
                    @if ($paginator->onFirstPage())
                        <span class="inline-flex items-center px-2 py-2 text-gray-400 bg-white border border-gray-200 cursor-not-allowed rounded-l-md">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                           class="inline-flex items-center px-2 py-2 text-green-700 transition bg-white border border-green-300 rounded-l-md hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($elements as $element)

                        @if (is_string($element))
                            <span class="inline-flex items-center px-4 py-2 -ml-px text-sm text-gray-500 bg-white border border-gray-200">
                                {{ $element }}
                            </span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)

                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="inline-flex items-center px-4 py-2 -ml-px text-sm font-semibold text-white bg-green-700 border border-green-700 cursor-default">
                                            {{ $page }}
                                        </span>
                                    </span>
                                @else
                                    <a href="{{ $url }}"
                                       class="inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-green-700 transition bg-white border border-green-300 hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-500">
                                        {{ $page }}
                                    </a>
                                @endif

                            @endforeach
                        @endif

                    @endforeach

                    {{-- Next --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                           class="inline-flex items-center px-2 py-2 -ml-px text-green-700 transition bg-white border border-green-300 rounded-r-md hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                    @else
                        <span class="inline-flex items-center px-2 py-2 -ml-px text-gray-400 bg-white border border-gray-200 cursor-not-allowed rounded-r-md">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                    @endif

                </span>
            </div>

        </div>
    </nav>
@endif
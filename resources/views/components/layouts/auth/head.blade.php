@props([
    'title',
])

<head>
    @stack('head_start')

    <meta charset="utf-8">
  

    <title>{!! $title !!}</title>

    <base href="{{ config('app.url') . '/' }}">

    {{-- <x-layouts.pwa.head /> --}}

    <link rel="stylesheet" href="{{ asset('css/app.css') }}" type="text/css">

    @stack('css')

    @stack('stylesheet')

    {{-- @livewireStyles --}}

    @stack('js')

    <script type="text/javascript"><!--
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>;

        var flash_notification = {!! (session()->has('flash_notification')) ? json_encode(session()->get('flash_notification')) : 'false' !!};
    //--></script>

    {{ session()->forget('flash_notification') }}

    @stack('scripts')

    @stack('head_end')
</head>


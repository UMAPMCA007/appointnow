@extends('layouts.app')
@section('title', env('APP_NAME').' - '.trans('messages.calendar'))

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-fw fa-calendar"></i> @lang('messages.calendar')
        </h1>
    </div>
    <!-- End Page Heading -->
    <div id="calendar"></div>
@endsection

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            const language = "{{ (session()->get("language")) }}";
            let calendarEl = document.getElementById('calendar');
            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                themeSystem: 'bootstrap',
                locale: language
            });
            calendar.render();
        });
    </script>
@endsection

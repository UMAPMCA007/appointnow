@extends('layouts.app')
@section('title', env('APP_NAME').' - '.trans('messages.calendar'))

@section('content')
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

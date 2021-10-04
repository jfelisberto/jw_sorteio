@if (isSet($messages) && count($messages) >= 1)

{{-- <div id="alert"> --}}
@foreach ($messages as $message)
@switch($message['level'])
    @case('success')
        @section('icon', 'fas fa-check-circle')
        @break
    @case('danger')
        @section('icon', 'fas fa-exclamation-circle')
        @break
    @case('warning')
        @section('icon', 'fas fa-exclamation-triangle')
        @break
    @default
        @section('icon', 'fas fa-info-circle')
@endswitch

{{--
<div class="row">
    <div class="col">
        <div id="alert-{{ $message['level'] }}" class="alert alert-{{ $message['level'] }} align-items-center alert-dismissible fade show" role="alert">
            <span class="icon">
                {{-- <i class="{{$message['icon']}}"></i> --} }
                <i class="@yield('icon')"></i>
            </span>
            <span class="text">
                {{$message['message']}}
            </span>
            <span class="btn-close" data-id="alert-{{ $message['level'] }}" aria-label="Close">
                <i class="fas fa-times fa-1x"></i>
            </span>
        </div>
    </div>
</div>
--}}
@endforeach
{{--</div> --}}

@section('javascriptMessage')
<!-- Script for messages -->
<script type="text/javascript">
    $(document).ready(function() {
        {{--
        $(document).on('click', '.btn-close', function(e) {
            e.preventDefault();
            $('#'+$(this).attr('data-id')).remove();
        });
        --}}
        setTimeout(function() {
            @foreach ($messages as $message)
            sa('{{$message['level']}}', '{{$message['title']}}', '{{$message['message']}}', 3000);
            @endforeach
        }, 100);
    });
</script>
@endsection

@endif

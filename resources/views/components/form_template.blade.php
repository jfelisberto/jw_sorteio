{{--
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
--}}
<form id="{{ $formID }}" action="{{ $route }}" method="POST" @if(isset($enctypeForm)) enctype="{{ $enctypeForm }}" @endif class="needs-validation no_enter" novalidate>
    @csrf
    @if(!empty($method)) @method($method) @endif
    <div class="form-group">
    @foreach ($fields as $key => $field)
        @if(in_array($field['visible'], $visibiles, true))
        <div class="row">
        @if ($field['type'] == 'hidden')
            <input type="hidden" id="@if(isSet($prefix)){{$prefix}}{{$key}}@else{{$key}}@endif" name="{{ $key }}" value="{{ $field['default'] }}" />
        @else
            <div class="col-sm-12 col-md-3 col-lg-4">
                <label for="{{ $key }}" class="col-form-label">{{ $field['label'] }}@if($field['notnull'] == 1 || $errors->any()) <span class="text-danger">*</span> @endif</label>
            </div>
            <div class="col-sm-12 col-md-9 col-lg-8">
            @switch($field['type'])
                @case('datalist')
                    <input type="hidden" id="@if(isSet($prefix)){{$prefix}}{{$key}}@else{{$key}}@endif" name="{{ $key }}" value="@if(!empty($result['id'])){{ $result['id'] }}@else{{ $field['default'] }}@endif" />
                    <input type="text" id="@if(isSet($prefix)){{$prefix}}{{$key}}_text @else{{$key}}_text @endif" name="{{ $key }}_text" value="@if(!empty($data[$key]))@if(!empty($result['name'])){{ $result['name'] }}@else{{ $data[$key] }}@endif @elseif(!empty(old($key))){{ old($key) }}@endif" class="form-control form-control-sm @if(isset($field['css'])) {{ $field['css'] }} @endif {{ $errors->has($key) ? ' was-validated is-invalid' : '' }} " @if(isset($field['readonly']))readonly @endif />
                @break

                @case('datetime')
                    <input type="datetime-local" id="@if(isSet($prefix)){{$prefix}}{{$key}}@else{{$key}}@endif" name="{{ $key }}" value="@if(!empty($data[$key])){{ $data[$key] }}@elseif(!empty(old($key))){{ old($key) }}@endif" class="form-control form-control-sm @if(isset($field['css'])) {{ $field['css'] }} @endif @if($field['notnull'] == 1 || $errors->any()) validate @endif {{ $errors->has($key) ? ' was-validated is-invalid' : '' }} " @if($field['notnull'] == 1 || $errors->any()) required @endif />
                @break

                @case('date')
                    <input type="date" id="@if(isSet($prefix)){{$prefix}}{{$key}}@else{{$key}}@endif" name="{{ $key }}" value="@if(!empty($data[$key])){{ $data[$key] }}@elseif(!empty(old($key))){{ old($key) }}@endif" class="form-control form-control-sm @if(isset($field['css'])) {{ $field['css'] }} @endif @if($field['notnull'] == 1 || $errors->any()) validate @endif {{ $errors->has($key) ? ' was-validated is-invalid' : '' }} " @if(!empty($field['min']))min="{{ $field['min'] }}"@endif @if(!empty($field['max']))max="{{ $field['max'] }}"@endif @if($field['notnull'] == 1 || $errors->any()) required @endif />
                @break

                @case('time')
                    <input type="time" id="@if(isSet($prefix)){{$prefix}}{{$key}}@else{{$key}}@endif" name="{{ $key }}" value="@if(!empty($data[$key])){{ $data[$key] }}@elseif(!empty(old($key))){{ old($key) }}@endif" class="form-control form-control-sm @if(isset($field['css'])) {{ $field['css'] }} @endif @if($field['notnull'] == 1 || $errors->any()) validate @endif {{ $errors->has($key) ? ' was-validated is-invalid' : '' }} " @if($field['notnull'] == 1 || $errors->any()) required @endif />
                @break

                @case('range')
                    <input type="range" id="@if(isSet($prefix)){{$prefix}}{{$key}}@else{{$key}}@endif" name="{{ $key }}" value="@if(!empty($data[$key])){{ $data[$key] }}@elseif(!empty(old($key))){{ old($key) }}@endif" class="form-range form-range-sm @if(isset($field['css'])) {{ $field['css'] }} @endif @if($field['notnull'] == 1 || $errors->any()) validate @endif {{ $errors->has($key) ? ' was-validated is-invalid' : '' }} " @if(!empty($field['min']))min="{{ $field['min'] }}"@endif @if(!empty($field['max']))max="{{ $field['max'] }}"@endif @if($field['notnull'] == 1 || $errors->any()) required @endif />
                @break

                @case('number')
                    <input type="number" id="@if(isSet($prefix)){{$prefix}}{{$key}}@else{{$key}}@endif" name="{{ $key }}" value="@if(!empty($data[$key])){{ $data[$key] }}@elseif(!empty(old($key))){{ old($key) }}@endif" class="form-control form-control-sm @if(isset($field['css'])) {{ $field['css'] }} @endif @if($field['notnull'] == 1 || $errors->any()) validate @endif {{ $errors->has($key) ? ' was-validated is-invalid' : '' }} " @if(!empty($field['min']))min="{{ $field['min'] }}"@endif @if(!empty($field['max']))max="{{ $field['max'] }}"@endif @if($field['notnull'] == 1 || $errors->any()) required @endif />
                @break

                @case('photo')
                    <div class="row">
                        <div class="col-6 text-left">
                            <img src="{{ $data['path'] }}" class="img-fluid img-thumbnail rounded takePicture" alt="Foto" id="@if(isSet($prefix)){{$prefix}}{{$key}}_picture @else{{$key}}_picture @endif" data-id="{{ $key }}"@if(!empty($field['relation'])) data-relation="{{ $field['relation'] }}"@endif width="300px" />
                        </div>
                        <div id="snapshotPhoto" class="col-6 text-right" hidden>
                            @yield('includeSnapshot')
                        </div>
                    </div>
                    <input type="hidden" id="@if(isSet($prefix)){{$prefix}}{{$key}}@else{{$key}}@endif" name="{{ $key }}" value="@if(!empty($data[$key])){{ $data[$key] }}@elseif(!empty(old($key))){{ old($key) }}@endif" />
                @break

                @case('html')
                @case('text')
                    <textarea id="@if(isSet($prefix)){{$prefix}}{{$key}}@else{{$key}}@endif" name="{{ $key }}" rows="3" class="form-control form-control-sm @if($field['notnull'] == 1 || $errors->any()) validate @endif" @if($field['notnull'] == 1 || $errors->any()) required @endif>@if(!empty($data[$key])){{ $data[$key] }}@elseif(!empty(old($key))){{ old($key) }}@endif</textarea><br />
                @break

                @case('select')
                @case('dataSelect')
                    <select id="@if(isSet($prefix)){{$prefix}}{{$key}}@else{{$key}}@endif" name="{{ $key }}" class="custom-select custom-select-sm select2 @if($field['notnull'] == 1 || $errors->any()) validate @endif" @if($field['notnull'] == 1 || $errors->any()) required @endif>
                    <option value="">Selecione uma opção</option>
                    @foreach ($field['arraykeyval'] as $optVal => $optText)
                    <option value="{{ $optVal }}"@if(!empty($data[$key]) && $data[$key] == $optVal) selected @elseif(old($key) == $optVal) selected @else @endif>{{ $optText }}</option>
                    @endforeach
                    </select>
                @break

                @default
                    <input type="text" id="@if(isSet($prefix)){{$prefix}}{{$key}}@else{{$key}}@endif" name="{{ $key }}" value="@if(!empty($data[$key])){{ $data[$key] }}@elseif(!empty(old($key))){{ old($key) }}@endif" class="form-control form-control-sm @if(isset($field['css'])) {{ $field['css'] }} @endif @if($field['notnull'] == 1 || $errors->any()) validate @endif {{ $errors->has($key) ? ' was-validated is-invalid' : '' }}" @if($field['notnull'] == 1 || $errors->any()) required @endif />
                @break

            @endswitch
            @if($field['notnull'] == 1 || $errors->any())
                <div class="invalid-feedback">
                    Preencha o campo {{ $field['label'] }}
                </div>
            @endif
            </div>
        @endif
        </div>
        @endif
    @endforeach
    </div>
</form>

@section('javascriptForm')
<!-- Script validate Form -->
<script src="{{ asset('plugins/jquery_validation/jquery.validate.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#{{$formID}}').submit(function() {
        if(validate_form('#{{$formID}}')) {
            return true;
        } else {
            return false;
        }
    });
});
</script>
@endsection

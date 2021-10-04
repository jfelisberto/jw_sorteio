
<form id="{{ $formID }}" class="form-horizontal needs-validation no_enter" novalidate>
  @csrf
  @if(!empty($method)) @method($method) @endif
  <input type="hidden" name="dataReturn" value="json" />
  <input type="hidden" id="modalFormTarget" name="target" />
  <input type="hidden" id="modalFieldID" name="id" />
  <div class="form-group">
  <span id="fieldsModalForm"></span>
  @foreach ($fields as $key => $field)
      @if(in_array($field['visible'], $visibiles, true))
      <div class="row">
      @if ($field['type'] == 'hidden')
          <input type="hidden" id="{{ $key }}" name="{{ $key }}" value="{{ $field['default'] }}" />
      @else
          <div class="col-sm-12 col-md-3 col-lg-4">
              <label for="{{ $key }}" class="col-form-label">{{ $field['label'] }}</label>
          </div>
          <div class="col-sm-12 col-md-9 col-lg-8">
          @switch($field['type'])
              @case('datalist')
                  <input type="hidden" id="{{ $key }}" name="{{ $key }}" value="@if(!empty($result['id'])){{ $result['id'] }}@else{{ $field['default'] }}@endif" />
                  <input type="text" id="{{ $key }}_text" name="{{ $key }}_text" value="@if(!empty($data[$key]))@if(!empty($result['name'])){{ $result['name'] }}@else{{ $data[$key] }}@endif @elseif(!empty(old($key))){{ old($key) }}@endif" class="form-control form-control-sm @if(isset($field['css'])) {{ $field['css'] }} @endif {{ $errors->has($key) ? ' was-validated is-invalid' : '' }} " readonly />
                  @if($errors->has($key))
                  <div class="invalid-feedback">
                      {{ $errors->first($key) }}
                  </div>
                  @endif
              @break

              @case('datetime')
                  <input type="datetime-local" id="{{ $key }}" name="{{ $key }}" value="@if(!empty($data[$key])){{ $data[$key] }}@elseif(!empty(old($key))){{ old($key) }}@endif" class="form-control form-control-sm @if(isset($field['css'])) {{ $field['css'] }} @endif {{ $errors->has($key) ? ' was-validated is-invalid' : '' }} " />
                  @if($errors->has($key))
                  <div class="invalid-feedback">
                  {{ $errors->first($key) }}
                  </div>
                  @endif
              @break

              @case('date')
                  <input type="date" id="{{ $key }}" name="{{ $key }}" value="@if(!empty($data[$key])){{ $data[$key] }}@elseif(!empty(old($key))){{ old($key) }}@endif" class="form-control form-control-sm @if(isset($field['css'])) {{ $field['css'] }} @endif {{ $errors->has($key) ? ' was-validated is-invalid' : '' }} " @if(!empty($field['min']))min="{{ $field['min'] }}"@endif @if(!empty($field['max']))max="{{ $field['max'] }}"@endif />
                  @if($errors->has($key))
                  <div class="invalid-feedback">
                  {{ $errors->first($key) }}
                  </div>
                  @endif
              @break

              @case('time')
                  <input type="time" id="{{ $key }}" name="{{ $key }}" value="@if(!empty($data[$key])){{ $data[$key] }}@elseif(!empty(old($key))){{ old($key) }}@endif" class="form-control form-control-sm @if(isset($field['css'])) {{ $field['css'] }} @endif {{ $errors->has($key) ? ' was-validated is-invalid' : '' }} " />
                  @if($errors->has($key))
                  <div class="invalid-feedback">
                  {{ $errors->first($key) }}
                  </div>
                  @endif
              @break

              @case('range')
                  <input type="range" id="{{ $key }}" name="{{ $key }}" value="@if(!empty($data[$key])){{ $data[$key] }}@elseif(!empty(old($key))){{ old($key) }}@endif" class="form-range form-range-sm @if(isset($field['css'])) {{ $field['css'] }} @endif {{ $errors->has($key) ? ' was-validated is-invalid' : '' }} " @if(!empty($field['min']))min="{{ $field['min'] }}"@endif @if(!empty($field['max']))max="{{ $field['max'] }}"@endif />
                  @if($errors->has($key))
                  <div class="invalid-feedback">
                  {{ $errors->first($key) }}
                  </div>
                  @endif
              @break

              @case('number')
                  <input type="number" id="{{ $key }}" name="{{ $key }}" value="@if(!empty($data[$key])){{ $data[$key] }}@elseif(!empty(old($key))){{ old($key) }}@endif" class="form-control form-control-sm @if(isset($field['css'])) {{ $field['css'] }} @endif {{ $errors->has($key) ? ' was-validated is-invalid' : '' }} " @if(!empty($field['min']))min="{{ $field['min'] }}"@endif @if(!empty($field['max']))max="{{ $field['max'] }}"@endif />
                  @if($errors->has($key))
                  <div class="invalid-feedback">
                  {{ $errors->first($key) }}
                  </div>
                  @endif
              @break

              @case('photo')
                  <div class="row">
                      <div class="col-6 text-left">
                          <img src="{{ $data['path'] }}" class="img-fluid img-thumbnail rounded takePicture" alt="Foto" id="{{ $key }}_picture" data-id="{{ $key }}"@if(!empty($field['relation'])) data-relation="{{ $field['relation'] }}"@endif width="300px" />
                      </div>
                      <div id="snapshotPhoto" class="col-6 text-right" hidden>
                          @yield('includeSnapshot')
                      </div>
                  </div>
                  <input type="hidden" id="{{ $key }}" name="{{ $key }}" value="@if(!empty($data[$key])){{ $data[$key] }}@elseif(!empty(old($key))){{ old($key) }}@endif" />
                  @if($errors->has($key))
                  <div class="invalid-feedback">
                  {{ $errors->first($key) }}
                  </div>
                  @endif
              @break

              @case('html')
              @case('text')
                  <textarea id="{{ $key }}" name="{{ $key }}" rows="3" class="form-control form-control-sm">@if(!empty($data[$key])){{ $data[$key] }}@elseif(!empty(old($key))){{ old($key) }}@endif</textarea><br />
              @break

              @default
                  @if(@count($field['arraykeyval']) >= 1)
                  <select id="{{ $key }}" name="{{ $key }}" class="form-control form-control-sm select2">
                  @foreach ($field['arraykeyval'] as $optVal => $optText)
                  <option value="{{ $optVal }}"@if(!empty($data[$key]) && $data[$key] == $optVal) selected @elseif(old($key) == $optVal) selected @else @endif>{{ $optText }}</option>
                  @endforeach
                  </select>
                  @else
                  <input type="text" id="{{ $key }}" name="{{ $key }}" value="@if(!empty($data[$key])){{ $data[$key] }}@elseif(!empty(old($key))){{ old($key) }}@endif" class="form-control form-control-sm @if(isset($field['css'])){{ $field['css'] }}@endif{{ $errors->has($key) ? ' was-validated is-invalid' : '' }}" />
                  @if($errors->has($key))
                  <div class="invalid-feedback">
                      {{ $errors->first($key) }}
                  </div>
                  @endif
                  @endif
              @break

          @endswitch
          </div>
      @endif
      </div>
      @endif
  @endforeach
  </div>
</form>


<div class="row text-right">
  <div class="col-sm-12 col-md-12 col-lg-12">
    <button type="submit" class="btn btn-primary btn-sm submitForm" data-form="{{ $formID }}"><i class="@if(isSet($faIcon)){{$faIcon}}@else{{'fas fa-save'}}@endif"></i> @if(isSet($confirmButton)){{$confirmButton}}@else{{"Salvar"}}@endif</button>
    @if (isSet($backToPage))
    &nbsp;&nbsp;
    <a href="{{route($backToPage)}}" class="btn btn-secondary btn-sm"><i class="fas fa-ban"></i> Cancelar</a>
    @endif
  </div>
</div>

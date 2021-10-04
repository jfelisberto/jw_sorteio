
<!-- Modal -->
<div class="modal fade" id="{{ $modalID }}" data-bs-backdrop="static" data-bs-keyboard="false" data-backdrop="static" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable @if(isset($modalSize)) {{ $modalSize }} @else modal-lg  modal-xl @endif">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Modal title</h5>
        <button type="button" class="btn-close close" @if(isset($formID)) data-id="{{ $formID }}" @endif data-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        @hasSection('includeContent')
            @yield('includeContent')
        @else
            @include($includeContent)
        @endif
      </div>

      <div class="modal-footer">
        <div class="row">
          <div class="col-auto">
            @if(isset($formID))
            <button type="buttom" id="saveFormModal" class="btn btn-primary btn-sm saveFormModal" data-form="{{ $formID }}" data-url="{{ $url }}"><i class="fas fa-save"></i> Salvar</button>
            <button type="buttom" class="btn btn-warning btn-sm resetForm" data-form="{{ $formID }}"><i class="fas fa-save"></i> Limpar </button>
            @endif
            <button type="buttom" class="btn btn-secondary btn-sm dismiss-modal" @if(isset($formID)) data-id="{{ $formID }}" @endif data-dismiss="modal" aria-label="Close"><i class="fas fa-ban"></i> Cancelar</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

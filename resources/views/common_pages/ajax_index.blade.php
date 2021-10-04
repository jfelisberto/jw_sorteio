@extends('layouts.app', ['current'=>$here])

@section('headTitle', $headTitle.' - Lista')

@section('content')
  <div class="card border w-100">
    <div class="card-body table-responsive">
      <table id="tblData" class="table table-striped table-hover table-responsive-lg">
        <thead>
          <tr>
            <th scope="col" class="w-5 p-3">Registro</th>
            @foreach ($fields as $key => $field)
            <th scope="col" class="w-auto p-3">{{ $field['label']}} </th>
            @endforeach
            <th scope="col" class="text-center w-10 p-3">Ação</th>
          </tr>
        </thead>

        <tbody>
        </tbody>

        <tfoot>
          <tr>
            <th scope="col" class="w-5 p-3">Registro</th>
            @foreach ($fields as $key => $field)
            <th scope="col" class="w-auto p-3">{{ $field['label']}} </th>
            @endforeach
            <th scope="col" class="text-center w-10 p-3">Ação</th>
          </tr>
        </tfoot>
      </table>

    </div>
  </div>

  @include('components.modal_template', ['modalID'=>'mod'.$here, 'includeContent'=>'components.form_modal','modalSize'=>'modal-sm','formID'=>'form_'.$here,'method'=>'PUT','url'=>$here,'visibiles'=>[5]])
@endsection

@section('javascript')
  <!-- Script for DataTable -->
  @include($here.'.ajax_index_js')
  <script type="text/javascript">
    function delete_item(id) {
        $('#fieldsAjaxForm').html(generate_input(id,{'type':'hidden','name':'id'})+'\n'+generate_input('json', {'type':'hidden','name':'dataReturn'})+'\n'+generate_input('DELETE', {'type':'hidden','name':'_method'}));
        sendForm('#sendAjaxForm', '{{ $here }}/' + id, 'use_function#remove_item', true, true, false, 'POST');
    }

    function remove_item(data, status, sweetReturn) {
        console.log(data);
        location.reload();
    }

    function register_close_item(data, status, sweetReturn) {
        console.log(data);
        location.reload();
    }

    $(document).ready(function() {
        $(document).on('click', '.delete', function() {
            id = $(this).attr('data-id');
            sad('warning', 'Cuidado', 'Tem certeza de que deseja excluir o registro de: ' + $(this).attr('data-name') + '?', null, null, 'delete_item', id);
        });
        @if($newReg)
        $(".dataTables_toolbar").append('<div class="col-sm-2 col-md-1"><a href="{{ route($routes['create'])}}" class="btn btn-outline-success btn-sm stretched-link" title="Novo registro" style="float: right;"><i class="fas fa-plus-square"></i> Novo</a></div>');
        @endif
        $('select[name="tblData_length"]').removeClass('form-select form-select-sm').addClass('custom-select custom-select-sm');
    });
  </script>
@endsection

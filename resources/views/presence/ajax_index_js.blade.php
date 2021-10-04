<script type="text/javascript">
    var table = '';
    $(document).ready(function() {
        $('#tblData tfoot .text').each(function() {
            var title = $(this).text();
            $(this).html('<input type="text" class="form-control form-control-sm" placeholder="Pesquisar" />')
        });

        var table = $('#tblData').DataTable({
            "processing": true,
            "ajax": {
                "url": '{{ route($routes['index']) }}' + '?dataReturn=json',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            },
            "deferRender": true,
            "autoWidth": false,
            "select": false,
            "order": [[ 5, "desc" ]],
            "rowid": 'staffId',
            "columnDefs": [
                {
                    targets: [6],
                    sortable: false
                },
                {"render": function(data, type, row) {
                    var id = row.id;
                    content = row.id;
                    return content;
                }, 'sClass': 'text-center', "targets": 0},
                {"render": function(data, type, row) {
                    content = row.match;
                    return content;
                }, 'sClass': 'text-left', "targets": 1},
                {"render": function(data, type, row) {
                    content = row.player;
                    return content;
                }, 'sClass': 'text-center', "targets": 2},
                {"render": function(data, type, row) {
                    content = row.team;
                    return content;
                }, 'sClass': 'text-center', "targets": 3},
                {"render": function(data, type, row) {
                    content = row.position;
                    return content;
                }, 'sClass': 'text-center', "targets": 4},
                {"render": function(data, type, row) {
                    content = '<span id="confirmed_at_ln_' + row.id + '" hidden>' + row.confirmed_at + '</span>';
                    content += '<span id="confirmed_at_vw_' + row.id + '">' + row.confirmed_at_vw + '</span>';
                    return content;
                }, 'sClass': 'text-center', "targets": 5},
                {"render": function(data, type, row) {
                    if(checkIsNull(row.isDeleted) && checkIsNull(row.date_out)) {
                        content = '<a href="' + basePath + '{{ $here }}/' + row.id + '/edit" class="btn btn-primary btn-sm" title="Editar o registro"><i class="far fa-edit"></i> Editar</a>&nbsp;&nbsp;';
                        if(checkIsNull(row.confirmed_at)) {
                            content += '<button type="button" id="confirmbtn_' + row.id + '" class="btn btn-warning btn-sm confirmPresence" title="Confirmar presença na partida" data-id="' + row.id + '" data-name="' + row.name + '"><i class="fas fa-check"></i> Confirmar</button>&nbsp;&nbsp;';
                        }
                        content += '<button type="button" class="btn btn-danger btn-sm delete" title="Apagar o registro" data-id="' + row.id + '" data-name="' + row.name + '"><i class="far fa-trash-alt"></i> Apagar</button>';
                    } else {
                        content = '<b class="text-danger">' + row.isDeleted + '</b><br />';
                    }
                    return content;
                }, 'sClass': 'text-center', "targets": 6},
            ]
        });

        table.buttons(0, null).container().appendTo(table.table().container(), '#tblData_wrapper .col-md-6:eq(0)');

        $('#tblData').on('click', 'tr', function() {
            var trid = table.row(this).id();
            console.log('Clicked row id '+trid);
        });

        $(document).on('click', '.confirmPresence', function() {
            id = $(this).attr('data-id');
            $('#fieldsAjaxForm').html(generate_input(id,{'type':'hidden','name':'id'})+'\n'+generate_input('json', {'type':'hidden','name':'dataReturn'})+'\n'+generate_input('now', {'type':'hidden','name':'confirmed_at'})+'\n'+generate_input('PUT', {'type':'hidden','name':'_method'}));
            sendForm('#sendAjaxForm', '{{ $here }}/confirm/' + id, 'use_function#confirm_presence', true, true, false, 'POST');
        });
    });

    function confirm_presence(data, status, sweetReturn) {
        if (status === 'success') {
            $('#confirmbtn_' + data.id).attr('hidden', 'hidden');
            $('#confirmed_at_ln_' + data.id).html(data.confirmed_at);
            $('#confirmed_at_vw_' + data.id).html(data.confirmed_at_vw);
        }
    }
</script>

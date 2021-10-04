<script type="text/javascript">
    var table = '';
    var cssRow = ' w-auto p-3'
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
            "order": [[ 2, "asc" ]],
            "rowid": 'staffId',
            "columnDefs": [
                {
                    targets: [3,4,7],
                    sortable: false
                },
                {"render": function(data, type, row) {
                    var id = row.id;
                    content = row.id;
                    return content;
                }, 'sClass':'text-center w-5 p-3', "targets": 0},
                {"render": function(data, type, row) {
                    content = row.name;
                    return content;
                }, 'sClass':'text-left' + cssRow, "targets": 1},
                {"render": function(data, type, row) {
                    content = '<span hidden>' + row.match_at + '</span>';
                    content += row.match_at_vw;
                    return content;
                }, 'sClass': 'text-center' + cssRow, "targets": 2},
                {"render": function(data, type, row) {
                    content = row.location;
                    return content;
                }, 'sClass': 'text-center rowAddress' + cssRow, "targets": 3},
                {"render": function(data, type, row) {
                    content = row.address;
                    return content;
                }, 'sClass': 'text-center rowAddress' + cssRow, "targets": 4},
                {"render": function(data, type, row) {
                    content = '<span hidden>' + row.created_team_at + '</span>';
                    content += row.created_team_at_vw;
                    return content;
                }, 'sClass': 'text-center' + cssRow, "targets": 5},
                {"render": function(data, type, row) {
                    content = '<span id="closed_at_ln_' + row.id + '" hidden>' + row.closed_at + '</span>';
                    content += '<span id="closed_at_vw_' + row.id + '">' + row.closed_at_vw + '</span>';
                    return content;
                }, 'sClass': 'text-center' + cssRow, "targets": 6},
                {"render": function(data, type, row) {
                    if(checkIsNull(row.isDeleted) && checkIsNull(row.date_out)) {
                        content = '<a href="' + basePath + '{{ $here }}/' + row.id + '/edit" class="btn btn-primary btn-sm" title="Editar o registro"><i class="far fa-edit"></i> Editar</a>&nbsp;&nbsp;';
                        if(checkIsNull(row.closed_at)) {
                            content += '<button type="button" id="closeMatchbtn_' + row.id + '" class="btn btn-warning btn-sm closedMatch" title="Encerrar a partida" data-id="' + row.id + '" data-name="' + row.name + '"><i class="fas fa-door-closed"></i> Encerrar</button>&nbsp;&nbsp;';
                        }
                        content += '<button type="button" class="btn btn-danger btn-sm delete" title="Apagar o registro" data-id="' + row.id + '" data-name="' + row.name + '"><i class="far fa-trash-alt"></i> Apagar</button>';
                    } else {
                        content = '<b class="text-danger">' + row.isDeleted + '</b><br />';
                    }
                    return content;
                }, 'sClass': 'text-center w-10 p-3', "targets": 7},
            ]
        });

        table.buttons(0, null).container().appendTo(table.table().container(), '#tblData_wrapper .col-md-6:eq(0)');

        $('#tblData').on('click', 'tr', function() {
            var trid = table.row(this).id();
            console.log('Clicked row id '+trid);
        } );

        $(document).on('click', '.closedMatch', function() {
            id = $(this).attr('data-id');
            $('#fieldsAjaxForm').html(generate_input(id,{'type':'hidden','name':'id'})+'\n'+generate_input('json', {'type':'hidden','name':'dataReturn'})+'\n'+generate_input('now', {'type':'hidden','name':'closed_at'})+'\n'+generate_input('PUT', {'type':'hidden','name':'_method'}));
            sendForm('#sendAjaxForm', '{{ $here }}/closed/' + id, 'use_function#confirm_closed', true, true, false, 'POST');
        });
    });

    function confirm_closed(data, status, sweetReturn) {
        if (status === 'success') {
            $('#closeMatchbtn_' + data.id).attr('hidden', 'hidden');
            $('#closed_at_ln_' + data.id).html(data.closed_at);
            $('#closed_at_vw_' + data.id).html(data.closed_at_vw);
        }
    }
</script>

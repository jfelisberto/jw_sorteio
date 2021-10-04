<script type="text/javascript">
    var table = '';
    $(document).ready(function() {
        $('#tblData tfoot .text').each(function() {
            var title = $(this).text();
            $(this).html('<input type="text" class="form-control form-control-sm" placeholder="Pesquisar" />')
        });

        table = $('#tblData').DataTable({
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
            "order": [[ 1, "asc" ]],
            "rowid": 'staffId',
            "columnDefs": [
                {
                    targets: [5],
                    sortable: false
                },
                {"render": function(data, type, row) {
                    var id = row.id;
                    content = row.id;
                    return content;
                }, 'sClass': 'text-center', "targets": 0},
                {"render": function(data, type, row) {
                    content = row.username;
                    return content;
                }, 'sClass': 'text-left', "targets": 1},
                {"render": function(data, type, row) {
                    content = row.name;
                    return content;
                }, 'sClass': 'text-left', "targets": 2},
                {"render": function(data, type, row) {
                    content = row.nivel_data;
                    return content;
                }, 'sClass': 'text-center', "targets": 3},
                {"render": function(data, type, row) {
                    content = row.position;
                    return content;
                }, 'sClass': 'text-center', "targets": 4},
                {"render": function(data, type, row) {
                    if(checkIsNull(row.isDeleted) && checkIsNull(row.date_out)) {
                        content = '<a href="' + basePath + '{{ $here }}/' + row.id + '/edit" class="btn btn-primary btn-sm" title="Editar o registro"><i class="far fa-edit"></i> Editar</a>&nbsp;&nbsp;';
                        content += '<button type="button" class="btn btn-danger btn-sm delete" title="Apagar o registro" data-id="' + row.id + '" data-name="' + row.name + '"><i class="far fa-trash-alt"></i> Apagar</button>';
                    } else {
                        content = '<b class="text-danger">' + row.isDeleted + '</b><br />';
                    }
                    return content;
                }, 'sClass': 'text-center', "targets": 5},
            ]
        });

        table.buttons(0, null).container().appendTo(table.table().container(), '#tblData_wrapper .col-md-3:eq(0)');

        $('#tblData').on('click', 'tr', function() {
            var trid = table.row(this).id();
            console.log('Clicked row id '+trid);
        } );

    });
</script>

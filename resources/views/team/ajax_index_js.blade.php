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
            "order": [[ 1, "asc" ]],
            "rowid": 'staffId',
            "columnDefs": [
                {
                    targets: [10],
                    sortable: false
                },
                {"render": function(data, type, row) {
                    var id = row.id;
                    content = row.id;
                    return content;
                }, 'sClass': 'text-center', "targets": 0},
                {"render": function(data, type, row) {
                    content = row.name;
                    return content;
                }, 'sClass': 'text-left', "targets": 1},
                {"render": function(data, type, row) {
                    content = row.match;
                    return content;
                }, 'sClass': 'text-left', "targets": 2},
                {"render": function(data, type, row) {
                    content = row.attacker_name;
                    return content;
                }, 'sClass': 'text-center', "targets": 3},
                {"render": function(data, type, row) {
                    content = row.midfield_left_name;
                    return content;
                }, 'sClass': 'text-center', "targets": 4},
                {"render": function(data, type, row) {
                    content = row.midfield_right_name;
                    return content;
                }, 'sClass': 'text-center', "targets": 5},
                {"render": function(data, type, row) {
                    content = row.wing_left_name;
                    return content;
                }, 'sClass': 'text-center', "targets": 6},
                {"render": function(data, type, row) {
                    content = row.wing_right_name;
                    return content;
                }, 'sClass': 'text-center', "targets": 7},
                {"render": function(data, type, row) {
                    content = row.defender_name;
                    return content;
                }, 'sClass': 'text-center', "targets": 8},
                {"render": function(data, type, row) {
                    content = row.goalkeeper_name;
                    return content;
                }, 'sClass': 'text-center', "targets": 9},
                {"render": function(data, type, row) {
                    if(checkIsNull(row.isDeleted)) {
                        if (checkIsNull(row.match_deleted_at) && checkIsNull(row.match_closed_at)) {
                            content = '<button type="button" class="btn btn-danger btn-sm delete" title="Apagar o registro" data-id="' + row.id + '" data-name="' + row.name + '"><i class="far fa-trash-alt"></i> Apagar</button>';
                        } else {
                            content = '<button type="button" class="btn btn-light btn-sm">Partida encerrada</button>';
                        }
                    } else {
                        content = '<b class="text-danger">' + row.isDeleted + '</b><br />';
                    }
                    return content;
                }, 'sClass': 'text-center', "targets": 10},
            ]
        });

        table.buttons(0, null).container().appendTo(table.table().container(), '#tblData_wrapper .col-md-6:eq(0)');

        $('#tblData').on('click', 'tr', function() {
            var trid = table.row(this).id();
            console.log('Clicked row id '+trid);
        } );
    });
</script>

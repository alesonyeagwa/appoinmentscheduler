<div class="row my-3">
</div>
<div id="table">
    <bootstrap-table :columns="columns" :data="data" :options="options"></bootstrap-table>
</div>

<script>
    new Vue({
        el: '#table',
        components: {
            'BootstrapTable': BootstrapTable
        },
        data: {
            columns: [
                {
                    title: 'Name',
                    field: 'name'
                },
                {
                    field: 'email',
                    title: 'Email'
                },
                {
                    field: 'phone',
                    title: 'Phone'
                },
                {
                    field: 'stat',
                    title: 'Status'
                },
                {
                    field: 'view',
                    title: 'View'
                }
            ],
            data: [],
            options: {
                search: true,
                //showColumns: true,
                //sidePagination: 'server',
                pagination: true,
                url: '<?= base_url('user/get_users') ?>'
            }
        }
    })
</script>
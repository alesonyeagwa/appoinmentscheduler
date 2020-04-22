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
                    title: 'Agent name',
                    field: 'agentName'
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
                    field: 'profession',
                    title: 'Profession'
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
                url: '<?= base_url('agent/get_agents') ?>'
            }
        }
    })
</script>
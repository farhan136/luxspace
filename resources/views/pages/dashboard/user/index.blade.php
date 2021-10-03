<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User') }}
        </h2>
    </x-slot>

    <x-slot name="script">
        <script type="text/javascript">
            //ajax datatable
            var datatable = $('#crudtable').DataTable({
                ajax: {
                    url: '{!! url()->current() !!}'
                },
                columns: [
                    {data: 'DT_RowIndex', name:'DT_RowIndex'},//fungsinya sama seperti loop-iteration pada laravel
                    {data:'name', name:'name'},
                    {data:'email', name:'email'},
                    {data:'roles', name:'roles'},
                    {
                        data:'action', 
                        name:'action', 
                        width:'25%',
                        orderable: false,
                        searchable:false
                    },
                ]
            })
        </script>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="shadow overflow-hidden sm-rounded-md">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <table id="crudtable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Roles</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

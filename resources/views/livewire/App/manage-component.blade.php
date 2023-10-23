<div class="p-4 flex flex-col lg:flex-row">
    <section class="w-full mb-4 lg:w-1/2">
        <h3 class="text-lg w-full py-1 bg-slate-700 rounded-t text-gray-300 text-center">Users</h3>
        <table class="table-fixed w-full">
            <thead>
                <tr>
                    <th>ID</th>
                    <th class="w-1/4">Name</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Store</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->role }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->store->name }} <span class="text-gray-300">({{ $user->store_role }})</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>

    <section></section>


</div>

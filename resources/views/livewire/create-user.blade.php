<div class="grid grid-cols-10 gap-6 bg-white shadow-lg rounded-xl p-6">
    {{-- ✅ Left Column - Create User Form --}}
    <div class="col-span-3 space-y-6 bg-gray-50 p-4 rounded-lg shadow">
        <h2 class="text-lg font-semibold text-gray-800">Create User</h2>

        @if (session()->has('success'))
            <div class="p-3 bg-green-200 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit.prevent="createUser" class="space-y-4">
            {{-- ✅ Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" wire:model="name" id="name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- ✅ Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" wire:model="email" id="email"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- ✅ Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" wire:model="password" id="password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- ✅ Role Selection --}}
            <div>
                <label for="role_id" class="block text-sm font-medium text-gray-700">Role</label>
                <select wire:model="role_id" id="role_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Select Role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- ✅ Submit Button --}}
            <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition">
                Create User
            </button>
        </form>
    </div>

    {{-- ✅ Right Column - User List --}}
    <div class="col-span-7 space-y-6">
        <h2 class="text-lg font-semibold text-gray-800">User List</h2>

        @if ($users->isEmpty())
            <p class="text-gray-500">No users available.</p>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Role</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-800">{{ $user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $user->roles->isNotEmpty() ? $user->roles->pluck('name')->join(', ') : 'No Role' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button wire:click="editUser({{ $user->id }})"
                                        class="text-blue-500 hover:text-blue-700 px-2">✏️ Edit</button>
                                    <button wire:click="deleteUser({{ $user->id }})" 
                                        onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                                        class="text-red-500 hover:text-red-700 px-2">🗑️ Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    
                </table>
                
            </div>
        @endif
    </div>
</div>

@extends('base')

@section('body')
<div class="min-h-screen flex flex-col bg-gray-100 -mt-5">
    <!-- Navbar -->
    <nav class="bg-indigo-600 p-4 shadow-md text-white">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-lg font-semibold">User Role Management</h1>
            <div>
                <span>Welcome, {{ Auth::user()->email }}</span>
            </div>
        </div>
    </nav>

    <div class="container mx-auto flex flex-1 mt-6">
        <!-- Sidebar -->
        <aside class="w-1/4 bg-white p-6 rounded-lg shadow-md mr-6">
            <ul class="space-y-4">
                <li><a href="/dashboard" class="block text-indigo-600 hover:underline">Dashboard</a></li>
                <li><a href="/user_role_settings" class="block text-indigo-600 font-bold hover:underline">Manage User Roles</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold mb-4">Manage User Roles</h2>

            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-2">Role Descriptions</h3>
                <ul class="list-disc list-inside text-gray-700">
                    <li><strong>Admin:</strong> Has administrative access over organizational entities.</li>
                    <li><strong>User:</strong> Has restrictive access to organizational entities.</li>
                </ul>
            </div>

            <!-- User Role Table -->
            <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-4 border-b text-left text-gray-600">Email</th>
                        <th class="py-2 px-4 border-b text-left text-gray-600">Role</th>
                        <th class="py-2 px-4 border-b text-center text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($organizationUsers as $user)
                        <tr class="border-b">
                            <td class="py-2 px-4">{{ $user->email }}</td>
                            <td class="py-2 px-4">{{ $user->roles[0] }}</td>
                            <td class="py-2 px-4 text-center">
                                <button onclick="openRoleEditForm('{{ $user->id }}', '{{ $user->roles[0] }}')"
                                        class="px-4 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                    Edit Role
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Hidden Role Edit Form -->
            <div id="roleEditFormContainer" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                    <h3 class="text-lg font-semibold mb-4">Edit User Role</h3>
                    <form id="roleEditForm" onsubmit="submitRoleEditForm(event)">
                        <div>
                            <label for="userRole" class="block text-gray-600 font-medium">Select Role</label>
                            <select id="userRole" name="role" required
                                    class="mt-1 block w-full px-4 py-2 border rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="ROLE_USER">User</option>
                                <option value="ROLE_ADMIN">Admin</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-end mt-4 space-x-4">
                            <button type="button" onclick="closeRoleEditForm()"
                                    class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    let editingUserId = null;

    function openRoleEditForm(userId, currentRole) {
        editingUserId = userId;
        document.getElementById('userRole').value = currentRole;
        document.getElementById('roleEditFormContainer').classList.remove('hidden');
    }

    function closeRoleEditForm() {
        editingUserId = null;
        document.getElementById('roleEditFormContainer').classList.add('hidden');
    }

    async function submitRoleEditForm(event) {
        event.preventDefault();
        const selectedRole = document.getElementById('userRole').value;

        try {
            const response = await fetch(`/api/edit_user_roles?userId=${editingUserId}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ role: selectedRole }),
            });

            const data = await response.json();
            if (response.ok && data.success) {
                alert('User role updated successfully!');
                location.reload();
            } else {
                throw new Error(data.error || 'Unknown error occurred.');
            }
        } catch (error) {
            alert(`Error: ${error.message}`);
        } finally {
            closeRoleEditForm();
        }
    }
</script>
@endsection

<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Component
{
    public $name, $email, $password, $role_id;
    public $user_id; // For editing
    public $users = [];
    public $roles = [];

    public function mount()
    {
        $this->loadUsers();
        $this->roles = Role::where('name', '!=', 'admin')->get(); // ✅ Exclude admin role
    }

    public function loadUsers()
    {
        $this->users = User::where('id', '!=', Auth::id()) // ✅ Exclude logged-in user
            ->with('roles')
            ->get();
    }

    public function createUser()
    {
        $this->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $this->user_id,
            'password' => $this->user_id ? 'nullable|min:6' : 'required|min:6',
            'role_id' => 'required'
        ]);

        if ($this->user_id) {
            // ✅ Update User
            $user = User::findOrFail($this->user_id);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password ? Hash::make($this->password) : $user->password,
            ]);

            // Update Role
            $role = Role::find($this->role_id);
            if ($role) {
                $user->syncRoles([$role]); // ✅ Sync new role
            }

            session()->flash('success', 'User updated successfully!');
        } else {
            // ✅ Create New User
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            $role = Role::find($this->role_id);
            if ($role) {
                $user->assignRole($role);
            }

            session()->flash('success', 'User created successfully!');
        }

        $this->resetForm();
        $this->loadUsers();
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        session()->flash('success', 'User deleted successfully!');
        $this->loadUsers();
    }

    public function resetForm()
    {
        $this->reset(['name', 'email', 'password', 'role_id', 'user_id']);
    }

    public function render()
    {
        return view('livewire.create-user');
    }
}

<?php
/*Management component for admins to promote/demote/delete users.*/

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class UserManagement extends Component
{
    use WithPagination;

    public string $search = '';

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setRole(User $user, string $role)
    {
        // Don't let an admin demote themselves (safety)
        if ($user->id === Auth::id()) {
            session()->flash('error', 'You cannot change your own role.');
            return;
        }

        if (!in_array($role, ['member', 'trainer', 'admin'])) {
            return;
        }

        $user->update(['role' => $role]);
        session()->flash('success', "Role for {$user->name} updated to {$role}.");
    }

    public function deleteUser(User $user)
    {
        if ($user->id === Auth::id()) {
            session()->flash('error', 'You cannot delete yourself.');
            return;
        }

        $user->delete();
        session()->flash('success', "User {$user->name} has been deleted.");
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        return view('livewire.admin.user-management', [
            'users' => $users,
        ])->layout('components.layouts.app', ['title' => 'User Management']);
    }
}

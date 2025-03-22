<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Task;
use App\Models\User;
use App\Jobs\SendTaskNotification;
use App\Jobs\AssignTaskJob;

class TaskManager extends Component
{
    use WithFileUploads;

    public $taskId;
    public $title;
    public $description;
    public $assigned_to;
    public $status = 'pending';
    public $isEditing = false; // ✅ Track edit mode

    public function getTasksProperty()
    {
        return Task::all();
    }

    public function getUsersProperty()
    {
        return User::all();
    }

    // ✅ Create a new task
    public function createTask()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $task = Task::create([
            'title' => $this->title,
            'description' => $this->description,
            'assigned_to' => $this->assigned_to,
            'status' => $this->status,
        ]);

        if ($this->assigned_to) {
            $this->assignTask($task->id, $this->assigned_to);
        }

        session()->flash('task_created', 'Task created successfully!');
        $this->resetForm();
        $this->dispatch('taskUpdated');
    }

    // ✅ Load task data for editing
    public function editTask($taskId)
    {
        $task = Task::findOrFail($taskId);

        $this->taskId = $task->id;
        $this->title = $task->title;
        $this->description = $task->description;
        $this->assigned_to = $task->assigned_to;
        $this->isEditing = true;
    }

    // ✅ Update an existing task
    public function updateTask()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $task = Task::findOrFail($this->taskId);
        $task->update([
            'title' => $this->title,
            'description' => $this->description,
            'assigned_to' => $this->assigned_to,
        ]);

        // ✅ If assigned user changed, send notification
        if ($task->wasChanged('assigned_to') && $this->assigned_to) {
            $this->assignTask($task->id, $this->assigned_to);
        }

        session()->flash('task_updated', 'Task updated successfully!');
        $this->resetForm();
        $this->dispatch('taskUpdated');
    }

    public function assignTask($taskId, $userId)
    {
        $task = Task::findOrFail($taskId);
        $user = User::findOrFail($userId);

        AssignTaskJob::dispatch($task, $user);
        SendTaskNotification::dispatch($user, $task, 'task_assigned');

        session()->flash('task_assigned', 'Task assigned & notification sent!');
        $this->dispatch('taskUpdated');
    }

    public function updateStatus($taskId, $status)
    {
        $task = Task::findOrFail($taskId);
        $task->status = $status;
        $task->save();

        if ($task->assigned_to) {
            $user = User::findOrFail($task->assigned_to);
            SendTaskNotification::dispatch($user, $task, 'status_update');
        }

        session()->flash('success', 'Task status updated and notification sent!');
        $this->dispatch('taskUpdated');
    }

    public function deleteTask($taskId)
    {
        $task = Task::findOrFail($taskId);
        $task->delete();

        session()->flash('success', 'Task deleted successfully!');
        $this->dispatch('taskUpdated');
    }

    // ✅ Reset form fields after update
    private function resetForm()
    {
        $this->taskId = null;
        $this->title = '';
        $this->description = '';
        $this->assigned_to = null;
        $this->isEditing = false;
    }

    public function render()
    {
        return view('livewire.task-manager');
    }
}

<?php

namespace App\Jobs;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use App\Mail\TaskAssignedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class AssignTaskJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $task;
    public $user;

    public function __construct(Task $task, User $user)
    {
        $this->task = $task;
        $this->user = $user;
    }

    public function handle()
    {
        $this->task->update(['assigned_to' => $this->user->id]);

        Mail::to($this->user->email)->send(new TaskAssignedMail($this->task));
    }
}

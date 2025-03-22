<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskStatusUpdatedMail; // ✅ Make sure this exists

class SendTaskNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $task;
    public $notificationType;

    public function __construct($user, $task, $notificationType)
    {
        $this->user = $user;
        $this->task = $task;
        $this->notificationType = $notificationType;
    }

    public function handle()
    {
        if ($this->notificationType === 'status_update') {
            Mail::to($this->user->email)->send(new TaskStatusUpdatedMail($this->task));
        }
    }
}

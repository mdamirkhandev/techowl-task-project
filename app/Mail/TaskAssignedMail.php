<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class TaskAssignedMail extends Mailable
{
    public $task;

    public function __construct($task)
    {
        $this->task = $task;
    }

    public function build()
    {
        return $this->subject('New Task Assigned')
            ->view('emails.task_assigned')
            ->with(['task' => $this->task]);
    }
}

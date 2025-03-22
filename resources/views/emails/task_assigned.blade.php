<!DOCTYPE html>
<html>
<head>
    <title>New Task Assigned</title>
</head>
<body>
    <h3>Hello, {{ $task->user->name }}</h3>
    <p>A new task <strong>{{ $task->title }}</strong> has been assigned to you.<strong>{{ ucfirst($task->status) }}</strong></p>
    <p>Description: {{ $task->description }}</p>
    <p>Thank you!</p>
</body>
</html>


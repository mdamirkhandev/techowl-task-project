<!DOCTYPE html>
<html>
<head>
    <title>Task Status Updated</title>
</head>
<body>
    <h3>Hello, {{ $task->user->name }}</h3>
    <p>The status of your task <strong>{{ $task->title }}</strong> has been updated to <strong>{{ ucfirst($task->status) }}</strong>.</p>
    <p>Thank you!</p>
</body>
</html>

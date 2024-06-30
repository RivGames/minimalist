<?php

declare(strict_types=1);

use App\Enums\TaskLimit;

$startCommandText = <<<END
👋 Welcome to Minimalist!

Your sleek and simple task manager, right here on Telegram. Manage your tasks effortlessly and stay organized with Minimalist.

To get started, use the commands below:
**/today**: Manage today's tasks 📅
**/tomorrow**: Plan for tomorrow 🗓️
**/future**: Organize tasks for future dates 📆

🔔 We recommend starting with the /today command to see what's on your agenda for today.

Here's what you can do:
**➕ Add Tasks**: Create new tasks with a simple command.
**👀 View Tasks**: See your tasks for today, tomorrow, and the future.
**🗑️ Delete Tasks**: Remove tasks you no longer need.
**✅ Complete Tasks**: Mark tasks as completed and track your progress.

Type /today to see your tasks for today or use the menu buttons below.

For help with commands, type /help.

Let's keep things simple and productive! 🌟
END;

$viewHeaderNoTasks = <<<END
📅 *Today's Tasks*

There are no today's tasks yet.


END;

$viewHeader = <<<END
📅 *Today's Tasks*


END;

$todayText = <<<END
📅 *Today's Tasks*

Here you can manage all your tasks for today. Use the buttons below to quickly add, complete, view, or delete your tasks.
END;


return [
    'commands' => [
        'start' => $startCommandText,
        'add' => [
            'step' => [
                '1' => "Great\n Let's add a new task to your to do list for today\n What's the name of the task? Example",
            ],
        ],
        'view' => [
            'header' => $viewHeader,
            'header-no-tasks' => $viewHeaderNoTasks,
        ],
        'complete' => [
            'specify-task-number' => 'Please specify the task number you want to complete.'
        ],
        'today' => $todayText,
    ],
    'errors' => [
        'validation' => [
            'invalid' => [
                'title' => "Error. Min 3. Max 25.",
                'task-number-format' => 'Invalid format number.',
                'task-not-found' => 'Task with number %s does not exist or you entered more than once.',
            ],
        ],
        'business' => [
            'user-exceeded-daily-limit' => sprintf('Sorry, but you exceeded your limit of %d tasks per day. Want more? Buy Premium! /premium', TaskLimit::DefaultUser->value),
        ]
    ],
];

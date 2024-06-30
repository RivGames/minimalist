<?php

return [
    // Add Task
    'ask-task-title' => "(_QID: 1_) Great\n Let's add a new task to your to do list for today\n What's the name of the task? Example",
    're-ask-title' => '(_QID: 2_) Error. Min 3. Max 25.',
    // Complete task
    'ask-task-numbers-to-complete' => '(_QID: 3_) Please specify the numbers of the tasks you want to complete. Separate multiple task numbers with a comma. For example: `1, 3, 5`',
    // Delete task
    'ask-task-numbers-to-delete' => '(_QID: 4_) Please specify the numbers of the tasks you want to delete. Separate multiple task numbers with a comma. For example: `1, 3, 5`'
];

// AddTaskValidator [ 'title' => [1, 2] ]
// CompleteTaskValidator [ 'taskNumber' => [3] ]
// DeleteTaskValidator [ 'taskNumber' => [4] ]

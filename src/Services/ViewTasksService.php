<?php

declare(strict_types=1);

namespace App\Services;

use App\DataTransferObjects\ReplyMarkups\InlineKeyboardButtonDTO;
use App\DataTransferObjects\ReplyMarkups\InlineKeyboardMarkupDTO;
use App\Enums\Emoji;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ViewTasksService extends AbstractService
{
    /**
     * @param string $date
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \JsonException
     */
    public function run(string $date): void
    {
        $this->telegramService->sendMessage(
            $this->generateText($date),
            $_SESSION['chat_id'],
            InlineKeyboardMarkupDTO::make([
                InlineKeyboardButtonDTO::make('➕ Add Task', callback_data: $date . '/add'),
                InlineKeyboardButtonDTO::make('✅ Complete Task', callback_data: $date . '/complete'),
                [
                    InlineKeyboardButtonDTO::make('🗑️ Delete Task', callback_data: $date . '/delete'),
                ],
            ])
        );
    }

    /**
     * @param string $date
     * @return string
     */
    private function generateText(string $date): string
    {
        $tasks = Task::byChatId($_SESSION['chat_id'])
            ->byDate(new Carbon($date))
            ->orderBy('index')
            ->get();

        return $this->generateTaskBody($tasks);
    }

    /**
     * @param Collection $tasks
     * @return string
     */
    private function generateTaskBody(Collection $tasks): string
    {
        $body = '';
        if ($tasks->count() === 0) {
            return $this->translator->trans('commands.view.header-no-tasks');
        }
        foreach ($tasks as $task) {
            $body .= $this->generateTaskLine($task);
        }

        return $this->translator->trans('commands.view.header') . $body . $this->getFooter();
    }

    /**
     * @param int $index
     * @param Task $task
     * @return string
     */
    private function generateTaskLine(Task $task): string
    {
        $format = "*%s %s* \n";
        if ($task->is_completed) {
            $format = "~*%s %s*~ \n";
        }
        return sprintf($format, Emoji::getEmojiNumberByIndex($task->index), $task->title);
    }

    private function getFooter(): string
    {
        // TODO: replace with call to an GrowthMindset's API
        return "\n_Excuses make today easy, but they make tomorrow hard. Discipline makes today hard, but it makes tomorrow easy. \n(c) Karl Niilo_";
    }
}

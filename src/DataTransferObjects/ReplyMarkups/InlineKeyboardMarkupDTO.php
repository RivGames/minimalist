<?php

declare(strict_types=1);

namespace App\DataTransferObjects\ReplyMarkups;

use ReflectionClass;

/**
 * @property InlineKeyboardButtonDTO[] $inline_keyboard
 */
class InlineKeyboardMarkupDTO implements AbstractReplyMarkup
{
    public function __construct(public array $inlineKeyboard = [])
    {
    }

    public static function make(array $inlineKeyboard): InlineKeyboardMarkupDTO
    {
        return new self($inlineKeyboard);
    }

    /**
     * @return array[]
     */
    public function toArray(): array
    {
        $data = [];
        $level1 = [];
        foreach ($this->inlineKeyboard as $item) {
            if ($item instanceof InlineKeyboardButtonDTO) {
                $keyboardButtonData = [];
                $this->extractKeyboardButtonProperties($item, $keyboardButtonData);
                $level1[] = $keyboardButtonData;
            } else {
                $levelN = [];
                foreach ($item as $keyboardButton) {
                    $keyboardButtonData = [];
                    $this->extractKeyboardButtonProperties($keyboardButton, $keyboardButtonData);
                    $levelN[] = $keyboardButtonData;
                }
                $data[] = $levelN;
            }
        }
        if (!empty($level1)) {
            $data[] = $level1;
        }
        $data = array_filter($data);
        usort($data, function ($a, $b) {
            return count($a) <= count($b) ? 1 : 0;
        });

        return ['inline_keyboard' => $data];
    }

    /**
     * @param InlineKeyboardButtonDTO $keyboardButton
     * @param array $keyboardButtonData
     * @return void
     */
    private function extractKeyboardButtonProperties(InlineKeyboardButtonDTO $keyboardButton, array &$keyboardButtonData): void
    {
        $reflection = new ReflectionClass($keyboardButton);
        foreach ($reflection->getProperties() as $reflectionProperty) {
            if (!empty($value = $keyboardButton->{$reflectionProperty->name})) {
                $keyboardButtonData[$reflectionProperty->name] = $value;
            }
        }
    }
}

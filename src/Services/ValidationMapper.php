<?php

declare(strict_types=1);

namespace App\Services;

class ValidationMapper
{
    /**
     * @param string $question
     * @return array
     */
    public function run(string $question): array
    {
        $questionId = $this->getQuestionId($question);
        $questionsMapping = require(BASE_PATH . '/resources/questions-mapping.php');
        foreach ($questionsMapping as $validatorClass => $item) {
            foreach ($item as $method => $questionsIds) {
                if (in_array($questionId, $questionsIds)) {
                    return [$validatorClass, $method];
                }
            }
        }

        return [];
    }

    /**
     * @param string $question
     * @return false|mixed
     */
    private function getQuestionId(string $question): int
    {
        preg_match_all('/QID: (?<questionId>\d)/', $question, $matches);

        return (int)current($matches['questionId']);
    }
}

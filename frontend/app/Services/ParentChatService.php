<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ChatMessage;

final class ParentChatService
{
    public function reply(string $question, array $context = [], ?int $childId = null): array
    {
        $sessionId = $_SESSION['parent_chat_session_id'] ?? ('chat-' . bin2hex(random_bytes(6)));
        $_SESSION['parent_chat_session_id'] = $sessionId;

        $historyModel = new ChatMessage();
        $historyModel->create($childId, $sessionId, 'user', $question);
        $history = $historyModel->history($sessionId, 10);

        $systemPrompt = (string) env('AI_PARENT_SYSTEM_PROMPT', current_language() === 'kk'
            ? 'Сіз ата-аналарға арналған AI-көмекшісіз.'
            : 'Вы — AI-помощник для родителей.');
        $contextText = $this->contextText($context);
        $messages = [];
        if ($contextText !== '') {
            $prefix = current_language() === 'kk'
                ? "Бала және соңғы бағалау контексті:\n"
                : "Контекст по ребёнку и последней оценке:\n";
            $messages[] = ['role' => 'user', 'content' => $prefix . $contextText];
        }
        foreach ($history as $item) {
            $messages[] = ['role' => $item['role'], 'content' => $item['message']];
        }

        $reply = (new AiClient())->chat($messages, $systemPrompt);
        if (!$reply) {
            throw new \RuntimeException(tr('chat.temporary_unavailable'));
        }

        $historyModel->create($childId, $sessionId, 'assistant', $reply);
        return ['session_id' => $sessionId, 'reply' => $reply];
    }

    private function contextText(array $context): string
    {
        if ($context === []) {
            return '';
        }

        $isKk = current_language() === 'kk';
        $lines = [];
        if (($context['childName'] ?? '') !== '') {
            $lines[] = ($isKk ? 'Бала: ' : 'Ребёнок: ') . $context['childName'];
        }
        if (($context['diagnosis'] ?? '') !== '') {
            $lines[] = ($isKk ? 'Диагноз: ' : 'Диагноз: ') . $context['diagnosis'];
        }
        if (($context['score'] ?? '') !== '') {
            $lines[] = ($isKk ? 'Жалпы балл: ' : 'Общий балл: ') . $context['score'];
        }
        if (!empty($context['problemSounds'])) {
            $label = $isKk ? 'Проблемалық дыбыстар: ' : 'Проблемные звуки: ';
            $lines[] = $label . implode(', ', array_map(fn($item) => $item['sound'] ?? '', $context['problemSounds']));
        }
        return implode("\n", $lines);
    }

}

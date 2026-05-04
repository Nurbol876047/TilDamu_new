<?php

declare(strict_types=1);

namespace App\Services;

final class AiClient
{
    private HttpClient $http;

    public function __construct()
    {
        $this->http = new HttpClient();
    }

    public function isConfigured(): bool
    {
        return (string) env('AI_API_KEY', '') !== ''
            && ($this->resolveChatEndpoint() !== '' || $this->resolveWhisperEndpoint() !== '' || $this->resolveSpeechAnalysisEndpoint() !== '');
    }

    public function analyzeSpeech(string $filePath, string $expectedWord, array $child = []): array
    {
        if (!$this->isConfigured()) {
            throw new \RuntimeException('AI не настроен. Проверьте .env и API-ключ.');
        }

        $direct = $this->resolveSpeechAnalysisEndpoint();
        if ($direct !== '') {
            $directResult = $this->analyzeSpeechDirect($direct, $filePath, $expectedWord, $child);
            return $this->normalizeSpeechPayload($directResult, $expectedWord);
        }

        $transcript = $this->transcribe($filePath);
        if ($transcript === '') {
            throw new \RuntimeException('AI не вернул транскрипт аудио.');
        }

        $analysis = $this->analyzeTranscript($transcript, $expectedWord, $child);
        $analysis['transcript'] = $analysis['transcript'] ?? $transcript;
        return $this->normalizeSpeechPayload($analysis, $expectedWord);
    }

    public function chat(array $messages, string $systemPrompt): ?string
    {
        $endpoint = $this->resolveChatEndpoint();
        if ($endpoint === '' || (string) env('AI_API_KEY', '') === '') {
            return null;
        }

        if ($this->isGenApiEndpoint($endpoint)) {
            $payload = [
                'is_sync' => true,
                'messages' => array_merge([
                    ['role' => 'system', 'content' => $systemPrompt],
                ], $messages),
                'temperature' => 0.3,
                'response_format' => ['type' => 'text'],
            ];
            $response = $this->http->postJson($endpoint, $payload, $this->authHeaders(), (int) env('AI_CHAT_TIMEOUT', 90));
        } else {
            $payload = [
                'model' => (string) env('AI_CHAT_MODEL', 'gpt-4o-mini'),
                'messages' => array_merge([
                    ['role' => 'system', 'content' => $systemPrompt],
                ], $messages),
                'temperature' => 0.3,
            ];
            $response = $this->http->postJson($endpoint, $payload, $this->authHeaders(), (int) env('AI_CHAT_TIMEOUT', 90));
        }

        if (($response['status'] ?? 0) < 200 || ($response['status'] ?? 0) >= 300) {
            return null;
        }
        $decoded = json_decode((string) $response['body'], true);
        return $this->extractText($decoded);
    }

    private function analyzeTranscript(string $transcript, string $expectedWord, array $child): array
    {
        $systemPrompt = 'Ты — AI-логопедический ассистент. Верни только JSON без markdown и комментариев. ' .
            'Схема ответа: {"score":int 0..100,"confidence":int 0..100,"diagnosis":string,"summary":string,"strengths":[string],"recommendations":[string],"problem_sounds":[{"sound":string,"severity":string,"correct":int}],"transcript":string}. ' .
            'Не выдумывай медицинский диагноз сверх предварительной оценки и не ставь окончательный диагноз.';

        $childName = (string) ($child['full_name'] ?? $child['name'] ?? 'Ребенок');
        $childAge = (string) ($child['age'] ?? '');
        $prompt = "Контрольное слово: {$expectedWord}.\nРаспознанный текст: {$transcript}.\nРебенок: {$childName}.\nВозраст: {$childAge}.\n" .
            'Сравни произношение с ожидаемым словом и дай предварительную оценку артикуляции. Ответ строго JSON.';

        $content = $this->chat([['role' => 'user', 'content' => $prompt]], $systemPrompt);
        if (!$content) {
            throw new \RuntimeException('Чат-модель не вернула ответ для анализа речи.');
        }
        $parsed = $this->decodeJsonFromText($content);
        if (!is_array($parsed)) {
            throw new \RuntimeException('AI вернул непонятный формат анализа речи.');
        }
        return $parsed;
    }

    private function analyzeSpeechDirect(string $endpoint, string $filePath, string $expectedWord, array $child): array
    {
        // Для совместимых speech-endpoint используем multipart.
        $fields = [
            'audio' => new \CURLFile($filePath),
            'expected_word' => $expectedWord,
            'child_name' => (string) ($child['full_name'] ?? $child['name'] ?? 'Ребенок'),
            'child_age' => (string) ($child['age'] ?? ''),
            'language' => current_language(),
            'is_sync' => 'true',
        ];
        $response = $this->http->postMultipart($endpoint, $fields, $this->authHeaders(), (int) env('AI_AUDIO_TIMEOUT', 90));
        if (($response['status'] ?? 0) < 200 || ($response['status'] ?? 0) >= 300) {
            throw new \RuntimeException($this->providerErrorMessage($response, 'Ошибка прямого AI-анализа речи.'));
        }
        $decoded = json_decode((string) $response['body'], true);
        if (!is_array($decoded)) {
            throw new \RuntimeException('AI вернул неподдерживаемый ответ прямого анализа речи.');
        }
        return $decoded;
    }

    private function transcribe(string $filePath): string
    {
        $endpoint = $this->resolveWhisperEndpoint();
        if ($endpoint === '' || (string) env('AI_API_KEY', '') === '') {
            return '';
        }

        if ($this->isGenApiEndpoint($endpoint)) {
            $response = $this->http->postMultipart($endpoint, [
                'audio_url' => new \CURLFile($filePath),
                'task' => 'transcribe',
                'language' => current_language() === 'kk' ? 'kk' : 'ru',
                'is_sync' => 'true',
            ], $this->authHeaders(), (int) env('AI_AUDIO_TIMEOUT', 90));
        } else {
            $response = $this->http->postMultipart($endpoint, [
                'file' => new \CURLFile($filePath),
                'model' => (string) env('AI_AUDIO_MODEL', 'whisper-1'),
                'language' => current_language() === 'kk' ? 'kk' : 'ru',
            ], $this->authHeaders(), (int) env('AI_AUDIO_TIMEOUT', 90));
        }

        if (($response['status'] ?? 0) < 200 || ($response['status'] ?? 0) >= 300) {
            throw new \RuntimeException($this->providerErrorMessage($response, 'Ошибка транскрибации аудио.'));
        }

        $decoded = json_decode((string) $response['body'], true);
        if (!is_array($decoded)) {
            throw new \RuntimeException('AI вернул неподдерживаемый ответ транскрибации.');
        }
        return trim((string) ($this->extractText($decoded) ?? ''));
    }

    private function authHeaders(): array
    {
        $key = (string) env('AI_API_KEY', '');
        if ($key === '') {
            return [];
        }
        return ['Authorization: Bearer ' . $key];
    }

    private function resolveChatEndpoint(): string
    {
        $custom = trim((string) (env('AI_CHAT_ENDPOINT', '') ?: env('TEXT_BOT_CHAT_ENDPOINT', '') ?: env('AI_TEXT_ENDPOINT', '')));
        if ($custom !== '') {
            return $this->normalizeEndpoint($custom);
        }

        $base = $this->baseUrl();
        return $base !== '' ? $base . '/chat/completions' : '';
    }

    private function resolveSpeechAnalysisEndpoint(): string
    {
        $custom = trim((string) (env('AI_SPEECH_ANALYSIS_ENDPOINT', '') ?: env('AI_WHISPER_ANALYZE_ENDPOINT', '') ?: env('WHISPER_ANALYZE_ENDPOINT', '')));
        return $custom !== '' ? $this->normalizeEndpoint($custom) : '';
    }

    private function resolveWhisperEndpoint(): string
    {
        $custom = trim((string) (env('AI_WHISPER_ENDPOINT', '') ?: env('AI_TRANSCRIBE_ENDPOINT', '') ?: env('WHISPER_ENDPOINT', '')));
        if ($custom !== '') {
            return $this->normalizeEndpoint($custom);
        }

        $base = $this->baseUrl();
        if ($base === '') {
            return '';
        }

        return str_contains($base, 'gen-api.ru') ? $base . '/networks/whisper' : $base . '/audio/transcriptions';
    }

    private function baseUrl(): string
    {
        return rtrim(trim((string) env('AI_API_BASE_URL', '')), '/');
    }

    private function normalizeEndpoint(string $endpoint): string
    {
        return preg_replace('#(?<!:)/{2,}#', '/', trim($endpoint)) ?: trim($endpoint);
    }

    private function isGenApiEndpoint(string $endpoint): bool
    {
        return str_contains($endpoint, 'gen-api.ru/api/v1/networks/') || trim((string) env('AI_PROVIDER_NAME', '')) === 'gen-api';
    }

    private function extractText(mixed $data): ?string
    {
        if (is_string($data)) {
            $trim = trim($data);
            return $trim !== '' ? $trim : null;
        }

        if (!is_array($data)) {
            return null;
        }

        $candidates = [
            $data['text'] ?? null,
            $data['transcript'] ?? null,
            $data['output_text'] ?? null,
            $data['result']['text'] ?? null,
            $data['result']['transcript'] ?? null,
            $data['data']['text'] ?? null,
            $data['response']['text'] ?? null,
            $data['choices'][0]['message']['content'] ?? null,
            $data['choices'][0]['text'] ?? null,
            $data['message']['content'] ?? null,
        ];
        foreach ($candidates as $candidate) {
            if (is_string($candidate) && trim($candidate) !== '') {
                return trim($candidate);
            }
            if (is_array($candidate)) {
                $nested = $this->extractText($candidate);
                if ($nested !== null) {
                    return $nested;
                }
            }
        }

        foreach ($data as $value) {
            $nested = $this->extractText($value);
            if ($nested !== null) {
                return $nested;
            }
        }
        return null;
    }

    private function decodeJsonFromText(string $text): ?array
    {
        $text = trim($text);
        $decoded = json_decode($text, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        if (preg_match('/```(?:json)?\s*(\{.*\}|\[.*\])\s*```/su', $text, $m)) {
            $decoded = json_decode($m[1], true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        if (preg_match('/(\{(?:[^{}]|(?1))*\})/su', $text, $m)) {
            $decoded = json_decode($m[1], true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }
        return null;
    }


    private function providerErrorMessage(array $response, string $default): string
    {
        $decoded = json_decode((string) ($response['body'] ?? ''), true);
        $status = (int) ($response['status'] ?? 0);
        $error = '';
        if (is_array($decoded)) {
            $error = (string) ($decoded['error'] ?? $decoded['message'] ?? $decoded['detail'] ?? '');
            if ($error === '' && isset($decoded['errors_validation']) && is_array($decoded['errors_validation'])) {
                $error = json_encode($decoded['errors_validation'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '';
            }
        }
        if ($error === '') {
            $error = trim((string) ($response['error'] ?? ''));
        }
        return $status > 0 ? ($default . ' HTTP ' . $status . ($error !== '' ? ': ' . $error : '')) : ($default . ($error !== '' ? ': ' . $error : ''));
    }

    private function normalizeSpeechPayload(array $payload, string $expectedWord): array
    {
        $payload['score'] = max(0, min(100, (int) ($payload['score'] ?? $payload['overall_score'] ?? 0)));
        $payload['confidence'] = max(0, min(100, (int) ($payload['confidence'] ?? 0)));
        $payload['diagnosis'] = trim((string) ($payload['diagnosis'] ?? ''));
        $payload['summary'] = trim((string) ($payload['summary'] ?? $payload['ai_summary'] ?? ''));
        $payload['strengths'] = array_values(array_filter((array) ($payload['strengths'] ?? []), 'is_string'));
        $payload['recommendations'] = array_values(array_filter((array) ($payload['recommendations'] ?? []), 'is_string'));
        $problemSounds = $payload['problem_sounds'] ?? $payload['problematicSounds'] ?? [];
        if (!is_array($problemSounds)) {
            $problemSounds = [];
        }
        $normalizedSounds = [];
        foreach ($problemSounds as $sound) {
            if (!is_array($sound)) {
                continue;
            }
            $normalizedSounds[] = [
                'sound' => (string) ($sound['sound'] ?? mb_strtoupper(mb_substr($expectedWord, 0, 1))),
                'severity' => (string) ($sound['severity'] ?? 'средняя'),
                'correct' => max(0, min(100, (int) ($sound['correct'] ?? 0))),
            ];
        }
        $payload['problem_sounds'] = $normalizedSounds;
        if ($payload['score'] <= 0) {
            throw new \RuntimeException('AI вернул неполный анализ речи.');
        }
        return $payload;
    }
}

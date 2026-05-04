from dataclasses import dataclass, field
from difflib import SequenceMatcher
from pathlib import Path
import re

from app.core.config import settings


@dataclass(frozen=True)
class SpeechRecognitionResult:
    provider: str
    raw_transcript: str
    recognized_word: str
    dictionary_match: str | None
    dictionary_confidence: int
    steps: list[str] = field(default_factory=list)


class SpeechToTextService:
    """Speech-to-text facade.

    Today it uses mock output. Later the same method can route audio to
    OpenAI Whisper or Google Speech-to-Text without changing the API layer.
    """

    def transcribe(
        self,
        file_path: str,
        expected_word: str,
        candidate_words: list[str],
        attempt_number: str,
    ) -> SpeechRecognitionResult:
        steps = [
            f"1) Аудио файл сохранен: {file_path}",
            f"2) Из базы загружено слов: {len(candidate_words)}",
        ]

        provider = settings.stt_provider
        try:
            raw_transcript = self._transcribe_raw(file_path, expected_word, attempt_number)
        except NotImplementedError as exc:
            provider = "mock"
            steps.append(f"3) {exc}. Использован mock STT.")
            raw_transcript = self._mock_transcribe(expected_word, attempt_number)
        else:
            steps.append(f"3) STT provider={provider} вернул текст: {raw_transcript}")

        matched_word, confidence = self._match_dictionary_word(raw_transcript, candidate_words)
        recognized_word = self._recognized_for_analysis(raw_transcript, matched_word, confidence)

        if matched_word:
            steps.append(
                f"4) Словарь БД: ближайшее слово '{matched_word}', confidence={confidence}%"
            )
        else:
            steps.append("4) Словарь БД: подходящее слово не найдено")
        steps.append(f"5) Для анализа используется recognized_word='{recognized_word}'")

        return SpeechRecognitionResult(
            provider=provider,
            raw_transcript=raw_transcript,
            recognized_word=recognized_word,
            dictionary_match=matched_word,
            dictionary_confidence=confidence,
            steps=steps,
        )

    def _transcribe_raw(self, file_path: str, expected_word: str, attempt_number: str) -> str:
        if settings.stt_provider == "mock":
            return self._mock_transcribe(expected_word, attempt_number)
        if settings.stt_provider == "openai_whisper":
            return self._transcribe_with_openai_whisper(file_path)
        if settings.stt_provider == "google":
            return self._transcribe_with_google(file_path)
        return self._mock_transcribe(expected_word, attempt_number)

    def _mock_transcribe(self, expected_word: str, attempt_number: str) -> str:
        attempt = attempt_number.strip().lower()
        if attempt in {"x3", "3", "attempt3"}:
            return expected_word
        if attempt in {"x2", "2", "attempt2"}:
            return self._make_light_mock_error(expected_word)
        return self._make_mock_error(expected_word)

    def _make_mock_error(self, word: str) -> str:
        if word == "мама":
            return "мана"

        substitutions = {
            "м": "н",
            "р": "л",
            "қ": "к",
            "ғ": "г",
            "ш": "с",
            "ж": "з",
            "д": "т",
        }
        for source, target in substitutions.items():
            if source in word:
                return word.replace(source, target, 1)
        return word[:-1] if len(word) > 2 else word

    def _make_light_mock_error(self, word: str) -> str:
        if len(word) <= 3:
            return word
        return word[:-1] + word[-1]

    def _transcribe_with_openai_whisper(self, file_path: str) -> str:
        # TODO: connect OpenAI Whisper/Transcriptions API here.
        # Contract: input file_path -> raw recognized text.
        raise NotImplementedError("OpenAI Whisper provider is prepared but not configured yet")

    def _transcribe_with_google(self, file_path: str) -> str:
        # TODO: connect Google Cloud Speech-to-Text here.
        # Contract: input file_path -> raw recognized text.
        raise NotImplementedError("Google Speech-to-Text provider is prepared but not configured yet")

    def _match_dictionary_word(
        self,
        raw_transcript: str,
        candidate_words: list[str],
    ) -> tuple[str | None, int]:
        normalized_raw = self._normalize(raw_transcript)
        if not normalized_raw or not candidate_words:
            return None, 0

        candidates = [(word, self._normalize(word)) for word in candidate_words]
        for word, normalized_word in candidates:
            if normalized_raw == normalized_word:
                return word, 100

        transcript_words = set(normalized_raw.split())
        for word, normalized_word in candidates:
            if normalized_word in transcript_words:
                return word, 95

        best_word = None
        best_score = 0
        for word, normalized_word in candidates:
            score = round(SequenceMatcher(None, normalized_raw, normalized_word).ratio() * 100)
            if score > best_score:
                best_word = word
                best_score = score

        if best_score < 45:
            return None, best_score
        return best_word, best_score

    def _recognized_for_analysis(
        self,
        raw_transcript: str,
        dictionary_match: str | None,
        confidence: int,
    ) -> str:
        normalized_raw = self._normalize(raw_transcript)
        if not normalized_raw:
            return dictionary_match or ""
        if dictionary_match and confidence >= 95:
            return dictionary_match

        first_token = normalized_raw.split()[0]
        return first_token

    def _normalize(self, text: str) -> str:
        lowered = text.lower().replace("ё", "е")
        cleaned = re.sub(r"[^0-9a-zа-яәғқңөұүһі\s-]+", " ", lowered, flags=re.IGNORECASE)
        return re.sub(r"\s+", " ", cleaned).strip()


speech_to_text_service = SpeechToTextService()

from difflib import SequenceMatcher


class AnalysisService:
    substitution_map = {
        "м": "н",
        "р": "л",
        "қ": "к",
        "ғ": "г",
        "ш": "с",
        "ж": "з",
        "д": "т",
    }

    def analyze(self, expected_word: str, recognized_word: str | None = None) -> dict[str, str | int]:
        recognized = recognized_word or self._generate_mock_error(expected_word)
        accuracy = self._calculate_accuracy(expected_word, recognized)

        return {
            "expected_word": expected_word,
            "recognized_word": recognized,
            "accuracy": accuracy,
            "mistake_type": self._mistake_type(expected_word, recognized),
            "risk_level": self._risk_level(accuracy),
            "recommendation": self._recommendation(expected_word, recognized),
        }

    def _generate_mock_error(self, word: str) -> str:
        if word == "мама":
            return "мана"

        for source, target in self.substitution_map.items():
            if source in word:
                return word.replace(source, target, 1)

        if len(word) > 2:
            return word[:-1]
        return word

    def _calculate_accuracy(self, expected: str, recognized: str) -> int:
        if expected == "мама" and recognized == "мана":
            return 78
        if expected == recognized:
            return 96

        ratio = SequenceMatcher(None, expected.lower(), recognized.lower()).ratio()
        score = round(ratio * 100)
        return max(35, min(92, score))

    def _mistake_type(self, expected: str, recognized: str) -> str:
        if expected == recognized:
            return "қате жоқ"
        if len(expected) != len(recognized):
            return "дыбыс түсіру"
        return "дыбыс ауыстыру"

    def _risk_level(self, accuracy: int) -> str:
        if accuracy > 85:
            return "төмен"
        if accuracy >= 60:
            return "орташа"
        return "жоғары"

    def _recommendation(self, expected: str, recognized: str) -> str:
        if expected == recognized:
            return "Дыбысты бекітуге арналған қысқа қайталау жаттығуларын жалғастыру"
        if expected == "мама" and recognized == "мана":
            return "М және Н дыбыстарын жаттықтыру"

        changed = self._changed_sounds(expected, recognized)
        if changed:
            return f"{changed} дыбыстарын айыру және қайталау жаттығуларын орындау"
        return "Сөзді буынға бөліп, баяу қайталау жаттығуын орындау"

    def _changed_sounds(self, expected: str, recognized: str) -> str:
        pairs: list[str] = []
        for expected_char, recognized_char in zip(expected, recognized, strict=False):
            if expected_char != recognized_char:
                pairs.extend([expected_char.upper(), recognized_char.upper()])
                break
        return " және ".join(pairs)


analysis_service = AnalysisService()


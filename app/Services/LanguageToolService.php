<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use League\CommonMark\CommonMarkConverter;

class LanguageToolService
{
    protected string $apiUrl = 'https://speller.yandex.net/services/spellservice.json/checkText';

    public function checkGrammar(string $text): ?array
    {
        $cleanText = $this->stripMarkdown($text);

        if (empty(trim($cleanText))) {
            return null;
        }

        $params = [
            'text' => $cleanText,
            'lang' => '',
            'options' => 0
        ];

        try {
            $response = Http::get($this->apiUrl, $params);

            if ($response->successful()) {
                return $response->json();
            }

            return [
                'error' => 'API request failed',
                'status' => $response->status(),
                'message' => $response->body()
            ];

        } catch (\Exception $e) {
            return [
                'error' => 'Grammar check failed',
                'message' => $e->getMessage()
            ];
        }
    }

    protected function stripMarkdown(string $text): string
    {
        $converter = new CommonMarkConverter();
        return strip_tags($converter->convert($text)->getContent());
    }
}

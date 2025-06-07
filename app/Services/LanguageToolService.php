<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use League\CommonMark\CommonMarkConverter;

class LanguageToolService
{
    protected string $apiUrl = 'https://api.languagetool.org/v2/check';

    public function checkGrammar(string $text, string $lang = 'auto'): ?array
    {
        $cleanText = $this->stripMarkdown($text);
        try {
            $response = Http::asForm()->withHeaders([
                'Accept' => 'application/json',
            ])->post($this->apiUrl, [
                'text' => $cleanText,
                'language' => $lang,
            ]);
            return $response->json();
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

<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class LlmParsingService
{
    private static $groqApiKey = null;
    private static $httpClient = null;

    /**
     * Initialize the service with API configuration
     */
    private static function init()
    {
        if (self::$httpClient === null) {
            self::$httpClient = new Client([
                'timeout' => 30,
                'verify' => false // For development - remove in production
            ]);
            
            // You can set this in your .env file as GROQ_API_KEY
            self::$groqApiKey = env('GROQ_API_KEY');
        }
    }

    /**
     * Extract candidate data using LLM with fallback to regex
     * 
     * @param string $text Raw text from CV/Resume
     * @return array Extracted candidate data
     */
    public static function extractCandidateData(string $text): array
    {
        self::init();
        
        // Try LLM extraction first (only if API key is available)
        if (self::$groqApiKey && !empty(trim(self::$groqApiKey))) {
            try {
                return self::extractWithGroq($text);
            } catch (\Exception $e) {
                Log::warning('LLM extraction failed, falling back to regex: ' . $e->getMessage());
            }
        } else {
            Log::info('No Groq API key found, using regex extraction');
        }
        
        // Fallback to regex-based extraction
        return self::extractWithRegex($text);
    }

    /**
     * Extract data using Groq LLM API
     */
    private static function extractWithGroq(string $text): array
    {
        $prompt = self::buildExtractionPrompt($text);
        
        // Get available models dynamically
        $models = self::getAvailableModels();
        
        if (empty($models)) {
            // Fallback to known model names if API call fails
            $models = [
                'llama3-8b-8192',
                'llama3-70b-8192',  
                'llama-3.1-8b-instant',
                'mixtral-8x7b-32768'
            ];
        }
        
        $lastError = null;
        
        foreach ($models as $model) {
            try {
                $response = self::$httpClient->post('https://api.groq.com/openai/v1/chat/completions', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . self::$groqApiKey,
                        'Content-Type' => 'application/json'
                    ],
                    'json' => [
                        'model' => $model,
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => 'You are a precise data extraction specialist. Extract candidate information from CVs/resumes and return ONLY valid JSON. If information is not found, use null.'
                            ],
                            [
                                'role' => 'user', 
                                'content' => $prompt
                            ]
                        ],
                        'temperature' => 0.1,
                        'max_tokens' => 1000,
                        'response_format' => ['type' => 'json_object']
                    ]
                ]);

                $data = json_decode($response->getBody()->getContents(), true);
                
                if (!isset($data['choices'][0]['message']['content'])) {
                    throw new \Exception('Invalid response format from Groq API');
                }
                
                $extractedJson = $data['choices'][0]['message']['content'];
                
                // Clean up the JSON response (remove any markdown formatting)
                $extractedJson = preg_replace('/```json\s*/', '', $extractedJson);
                $extractedJson = preg_replace('/\s*```/', '', $extractedJson);
                $extractedJson = trim($extractedJson);
                
                $result = json_decode($extractedJson, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::warning('JSON parsing failed for response: ' . substr($extractedJson, 0, 200));
                    throw new \Exception('Invalid JSON response: ' . json_last_error_msg());
                }
                
                // Validate that we have the expected structure
                if (!is_array($result)) {
                    throw new \Exception('Response is not a valid array structure');
                }
                
                Log::info('Successfully extracted data using model: ' . $model);
                return $result;
                
            } catch (\Exception $e) {
                $lastError = $e;
                Log::warning("Model {$model} failed: " . $e->getMessage());
                continue;
            }
        }
        
        throw new \Exception('All models failed. Last error: ' . $lastError->getMessage());
    }

    /**
     * Get list of available models from Groq API
     */
    private static function getAvailableModels(): array
    {
        try {
            $response = self::$httpClient->get('https://api.groq.com/openai/v1/models', [
                'headers' => [
                    'Authorization' => 'Bearer ' . self::$groqApiKey,
                    'Content-Type' => 'application/json'
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            
            if (isset($data['data']) && is_array($data['data'])) {
                $modelNames = array_column($data['data'], 'id');
                Log::info('Available Groq models: ' . implode(', ', $modelNames));
                return $modelNames;
            }
            
        } catch (\Exception $e) {
            Log::warning('Failed to fetch available models: ' . $e->getMessage());
        }
        
        return [];
    }

    /**
     * Build the extraction prompt for LLM
     */
    private static function buildExtractionPrompt(string $text): string
    {
        return "Extract the following information from this CV/Resume text. Return as JSON with these exact field names:

REQUIRED FIELDS:
- name: Full name of the candidate
- email: Email address  
- phone: Phone number (with country code if available)
- city: Current city
- country: Current country
- nationality: Nationality or citizenship (e.g., \"British\", \"American\")
- date_of_birth: Date of birth in YYYY-MM-DD format (if available)
- gender: Gender (Male/Female/Other/null)
- current_company: Current or most recent company/employer
- current_job_title: Current or most recent job title/position
- languages: Comma-separated list of languages (e.g., \"English, Spanish, French\")
- skills: Comma-separated list of technical skills and competencies
- work_experiences: Brief summary of work experience (2-3 sentences max)

RULES:
1. Extract information accurately from the text
2. For languages and skills, provide comma-separated values
3. If information is not found or unclear, use null
4. Normalize names to proper case (e.g., \"John Smith\")
5. Clean phone numbers (remove extra spaces/characters)
6. For current company/title, pick the most recent from experience section
7. For work_experiences, provide a concise summary of professional background
8. For date_of_birth, convert any date format to YYYY-MM-DD

TEXT TO ANALYZE:
" . substr($text, 0, 4000) . "

Return only the JSON object:";
    }

    /**
     * Fallback regex-based extraction
     */
    private static function extractWithRegex(string $text): array
    {
        return [
            'name' => self::extractName($text),
            'email' => self::extractEmail($text),
            'phone' => self::extractPhone($text),
            'city' => self::extractCity($text),
            'country' => self::extractCountry($text),
            'nationality' => self::extractNationality($text),
            'date_of_birth' => self::extractDateOfBirth($text),
            'gender' => self::extractGender($text),
            'current_company' => self::extractCurrentCompany($text),
            'current_job_title' => self::extractCurrentJobTitle($text),
            'languages' => self::extractLanguages($text),
            'skills' => self::extractSkills($text),
            'work_experiences' => self::extractWorkExperiences($text),
        ];
    }

    // Regex extraction methods (improved versions)
    private static function extractName(string $text): ?string
    {
        $patterns = [
            '/^([A-Z][a-z]+ [A-Z][a-z]+(?:\s[A-Z][a-z]+)?)/m', // First line pattern
            '/(?:Name|Full Name)[:\s]+([A-Z][a-z]+ [A-Z][a-z]+(?:\s[A-Z][a-z]+)?)/i', // Labeled pattern
            '/^([A-Z\s]{10,40})$/m', // ALL CAPS name (10-40 chars)
            '/([A-Z][a-z]+(?:\s[A-Z][a-z]+){1,3})(?:\s*\n|\s*$)/m' // Name followed by newline/end
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $name = trim($matches[1]);
                // Validate it's a reasonable name (not too short, not too long, has spaces)
                if (strlen($name) >= 5 && strlen($name) <= 50 && str_word_count($name) >= 2) {
                    return $name;
                }
            }
        }
        return null;
    }

    private static function extractEmail(string $text): ?string
    {
        if (preg_match('/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Z|a-z]{2,}\b/', $text, $matches)) {
            return strtolower(trim($matches[0]));
        }
        return null;
    }

    private static function extractPhone(string $text): ?string
    {
        $patterns = [
            '/(?:Phone|Tel|Mobile|Cell)[:\s]+([+\d\s\-\(\)]{10,20})/i',
            '/\+?[\d\s\-\(\)]{10,20}/'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                return trim($matches[1] ?? $matches[0]);
            }
        }
        return null;
    }

    private static function extractCity(string $text): ?string
    {
        if (preg_match('/([A-Za-z\s]+),\s*[A-Za-z]{2,}/m', $text, $matches)) {
            return trim(explode(',', $matches[1])[0]);
        }
        return null;
    }

    private static function extractCountry(string $text): ?string
    {
        if (preg_match('/,\s*([A-Za-z\s]+)$/m', $text, $matches)) {
            return trim($matches[1]);
        }
        return null;
    }

    private static function extractGender(string $text): ?string
    {
        if (preg_match('/(?:Gender|Sex)[:\s]+(Male|Female|M|F|Other)/i', $text, $matches)) {
            return $matches[1];
        }
        return null;
    }

    private static function extractCurrentCompany(string $text): ?string
    {
        $patterns = [
            '/(?:Company|Employer)[:\s]+([A-Za-z0-9\s&.,\-]+)/i',
            '/(?:EXPERIENCE)\s*\n.*?\n([A-Za-z0-9\s&.,\-]+)/i'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                return trim($matches[1]);
            }
        }
        return null;
    }

    private static function extractCurrentJobTitle(string $text): ?string
    {
        $patterns = [
            '/(?:Position|Title|Role)[:\s]+([A-Za-z0-9\s\-\/&.,]+)/i',
            '/(?:EXPERIENCE)\s*\n([A-Za-z0-9\s\-\/&.,]+)\s*\n/i'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                return trim($matches[1]);
            }
        }
        return null;
    }

    private static function extractLanguages(string $text): ?string
    {
        if (preg_match('/(?:Languages?)[:\s]+([A-Za-z\s,;\/\-\(\)]+)/i', $text, $matches)) {
            return trim($matches[1]);
        }
        return null;
    }

    private static function extractSkills(string $text): ?string
    {
        if (preg_match('/(?:Skills?|Technical\s+Skills?)[:\s]+([A-Za-z0-9\s,;\/\-\(\).#+]+)/i', $text, $matches)) {
            return trim($matches[1]);
        }
        return null;
    }

    private static function extractNationality(string $text): ?string
    {
        // Look for nationality patterns
        if (preg_match('/(?:Nationality|Citizenship)[:\s]+([A-Za-z\s]+)/i', $text, $matches)) {
            return trim($matches[1]);
        }
        return null;
    }

    private static function extractDateOfBirth(string $text): ?string
    {
        // Look for date of birth patterns
        $patterns = [
            '/(?:Date\s+of\s+Birth|DOB|Born)[:\s]+(\d{1,2}[\/-]\d{1,2}[\/-]\d{4})/i',
            '/(?:Date\s+of\s+Birth|DOB|Born)[:\s]+(\d{4}[\/-]\d{1,2}[\/-]\d{1,2})/i',
            '/(?:Date\s+of\s+Birth|DOB|Born)[:\s]+(\d{1,2}[\s]\w+[\s]\d{4})/i'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $text, $matches)) {
                $dateString = trim($matches[1]);
                // Try to convert to YYYY-MM-DD format
                $date = \DateTime::createFromFormat('d/m/Y', $dateString) ?: 
                        \DateTime::createFromFormat('m/d/Y', $dateString) ?: 
                        \DateTime::createFromFormat('Y-m-d', $dateString) ?: 
                        \DateTime::createFromFormat('d M Y', $dateString);
                        
                if ($date) {
                    return $date->format('Y-m-d');
                }
                return $dateString; // Return original if can't parse
            }
        }
        return null;
    }

    private static function extractWorkExperiences(string $text): ?string
    {
        // Look for experience section and extract a summary
        if (preg_match('/(?:Experience|Work\s+Experience|Employment)[:\s]+(.{0,500})/i', $text, $matches)) {
            $experience = trim($matches[1]);
            // Take first few sentences up to 200 characters
            $experience = substr($experience, 0, 200);
            if (strlen($experience) == 200) {
                $experience = substr($experience, 0, strrpos($experience, ' ')) . '...';
            }
            return $experience;
        }
        return null;
    }
}

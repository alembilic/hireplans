<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Element\TextRun;

class DocumentParsingService
{
    /**
     * Parse document (PDF/DOCX) and extract candidate information
     * 
     * @param string $filePath The path to the document file
     * @param string $fileExtension The file extension (pdf, docx)
     * @return array Parsed candidate data
     */
    public static function parseCandidateData(string $filePath, string $fileExtension): array
    {
        try {
            // Extract text based on file type
            $text = self::extractTextFromDocument($filePath, $fileExtension);
            
            if (empty($text)) {
                return ['error' => 'Could not extract text from document'];
            }

            // Parse the extracted text for candidate information using LLM
            $candidateData = LlmParsingService::extractCandidateData($text);
            
            // Clean and format the extracted data
            $candidateData = self::cleanCandidateData($candidateData);
            
            return [
                'success' => true,
                'count' => 1,
                'candidates' => [$candidateData],
                'extracted_text' => substr($text, 0, 500) . '...' // First 500 chars for debugging
            ];
            
        } catch (\Exception $e) {
            Log::error('Document parsing error: ' . $e->getMessage());
            return ['error' => 'Error parsing document: ' . $e->getMessage()];
        }
    }
    
    /**
     * Extract text from document based on file type
     * 
     * @param string $filePath
     * @param string $fileExtension
     * @return string
     */
    private static function extractTextFromDocument(string $filePath, string $fileExtension): string
    {
        switch (strtolower($fileExtension)) {
            case 'pdf':
                return self::extractTextFromPdf($filePath);
            case 'docx':
            case 'doc':
                return self::extractTextFromDocx($filePath);
            default:
                throw new \InvalidArgumentException("Unsupported file type: {$fileExtension}");
        }
    }
    
    /**
     * Extract text from PDF file
     * 
     * @param string $filePath
     * @return string
     */
    private static function extractTextFromPdf(string $filePath): string
    {
        try {
            $parser = new PdfParser();
            $pdf = $parser->parseFile($filePath);
            
            $text = '';
            $pages = $pdf->getPages();
            
            foreach ($pages as $page) {
                $text .= $page->getText() . "\n";
            }
            
            return $text;
        } catch (\Exception $e) {
            Log::error('PDF text extraction error: ' . $e->getMessage());
            throw new \Exception('Could not extract text from PDF: ' . $e->getMessage());
        }
    }
    
    /**
     * Extract text from DOCX file
     * 
     * @param string $filePath
     * @return string
     */
    private static function extractTextFromDocx(string $filePath): string
    {
        try {
            $phpWord = IOFactory::load($filePath);
            $text = '';
            
            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if (method_exists($element, 'getText')) {
                        $text .= $element->getText() . "\n";
                    } elseif ($element instanceof TextRun) {
                        foreach ($element->getElements() as $textElement) {
                            if (method_exists($textElement, 'getText')) {
                                $text .= $textElement->getText();
                            }
                        }
                        $text .= "\n";
                    }
                }
            }
            
            return $text;
        } catch (\Exception $e) {
            Log::error('DOCX text extraction error: ' . $e->getMessage());
            throw new \Exception('Could not extract text from DOCX: ' . $e->getMessage());
        }
    }

    /**
     * Clean and format candidate data
     * 
     * @param array $candidate Raw candidate data
     * @return array Cleaned candidate data
     */
    private static function cleanCandidateData(array $candidate): array
    {
        // Normalize gender values
        if (isset($candidate['gender']) && $candidate['gender']) {
            $gender = strtolower($candidate['gender']);
            if (in_array($gender, ['m', 'male', 'man'])) {
                $candidate['gender'] = 'Male';
            } elseif (in_array($gender, ['f', 'female', 'woman'])) {
                $candidate['gender'] = 'Female';
            } elseif (in_array($gender, ['other', 'o', 'non-binary', 'nb'])) {
                $candidate['gender'] = 'Other';
            } else {
                $candidate['gender'] = 'Prefer not to say';
            }
        }
        
        // Format languages and skills (ensure comma-separated format)
        if (isset($candidate['languages']) && $candidate['languages']) {
            $languages = preg_split('/[;,|]/', $candidate['languages']);
            $languages = array_map('trim', $languages);
            $languages = array_filter($languages);
            $candidate['languages'] = implode(', ', $languages);
        }
        
        if (isset($candidate['skills']) && $candidate['skills']) {
            $skills = preg_split('/[;,|]/', $candidate['skills']);
            $skills = array_map('trim', $skills);
            $skills = array_filter($skills);
            $candidate['skills'] = implode(', ', $skills);
        }
        
        // Clean up name field
        if (isset($candidate['name']) && $candidate['name']) {
            $candidate['name'] = ucwords(strtolower($candidate['name']));
        }
        
        // Clean up email field
        if (isset($candidate['email']) && $candidate['email']) {
            $candidate['email'] = strtolower(trim($candidate['email']));
        }
        
        // Ensure all expected fields exist with empty string defaults
        $expectedFields = [
            'name', 'email', 'phone', 'city', 'country', 'nationality', 'date_of_birth',
            'gender', 'current_company', 'current_job_title', 'languages', 'skills', 'work_experiences'
        ];
        
        foreach ($expectedFields as $field) {
            if (!isset($candidate[$field]) || $candidate[$field] === null || $candidate[$field] === '') {
                $candidate[$field] = '';
            }
        }
        
        return $candidate;
    }
}
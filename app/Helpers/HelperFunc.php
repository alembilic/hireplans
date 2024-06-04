<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use App\Models\Candidate;
use App\Models\Employer;
use App\Models\Job;
use App\Models\User;
use Orchid\Attachment\Models\Attachment;
use stdClass;

class HelperFunc
{
    /**
     * Generate a random reference number.
     *
     * @return string
     */
    public static function generateReferenceNumber(string $type = 'candidate')
    {
        // $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $letters = 'ABCDEGKRTVWYZ';
        $numbers = '123456789';
        $characters = $letters . $numbers;

        switch ($type) {
            case 'candidate':
                $targetClass = Candidate::class;
                $prefix = 'C-';
                break;
            case 'employer':
                $targetClass = Employer::class;
                $prefix = 'E-';
                break;
            case 'job':
                $targetClass = Job::class;
                $prefix = 'J-';
                break;
            case 'application':
                $prefix = 'A-';
                break;
            default:
                $prefix = 'U-';
                break;
        }

        do {
            // Ensure at least one letter and one number
            $referenceNumber = '';
            // $referenceNumber .= $numbers[rand(0, strlen($numbers) - 1)];
            for ($i = 0; $i < 3; $i++) {
                $referenceNumber .= $numbers[rand(0, strlen($numbers) - 1)];
            }
            $referenceNumber .= $letters[rand(0, strlen($letters) - 1)];

            // Fill the remaining 4 characters with random letters or numbers
            for ($i = 0; $i < 4; $i++) {
                $referenceNumber .= $characters[rand(0, strlen($characters) - 1)];
            }

            // Shuffle the resulting string (excluding the 'C-' prefix)
            $referenceNumber = $prefix . str_shuffle($referenceNumber);
        } while ($targetClass::where($type.'_ref', $referenceNumber)->exists());

        return $referenceNumber;
    }

    public static function getAttachmentInfo(Attachment $attachment): stdClass
    {
        $info = [
            'id' => $attachment->id,
            'size' => $attachment->size,
            'mime' => $attachment->mime,
            'text' => $attachment->original_name,
            'url' => $attachment->getRelativeUrlAttribute(),
        ];

        return (object) $info;
    }

    public static function getJobTypes(): array
    {
        return [
            'full_time' => 'Full Time',
            'part_time' => 'Part Time',
            'contract' => 'Contract',
            'temporary' => 'Temporary',
            'internship' => 'Internship',
            'volunteer' => 'Volunteer',
            // 'remote' => 'Remote',
            'other' => 'Other',
        ];
    }

    public static function getJobCategories(): array
    {
        return [
            'engineering' => 'Engineering',
            'marketing' => 'Marketing',
            'sales' => 'Sales',
            'customer_service' => 'Customer Service',
            'design' => 'Design',
            'product' => 'Product',
            'data' => 'Data',
            'finance' => 'Finance',
            'legal' => 'Legal',
            'hr' => 'HR',
            'it' => 'IT',
            'operations' => 'Operations',
            'other' => 'Other',
        ];
    }

    public static function getExperienceLevels(): array
    {
        return [
            'entry' => 'Entry Level',
            'mid' => 'Mid Level',
            'senior' => 'Senior Level',
        ];
    }

    public static function generateUniqueJobSlug(string $title): string
    {
        // Convert the title to a slug
        $slug = Str::slug($title);

        // Check for existing slugs
        $originalSlug = $slug;
        $count = 1;

        while (Job::where('slug', $slug)->exists()) {
            // Append the count to the slug if it already exists
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}

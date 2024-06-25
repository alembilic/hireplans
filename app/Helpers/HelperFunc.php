<?php

namespace App\Helpers;

use App\Models\JobApplication;
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
                $targetClass = JobApplication::class;
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

    public static function renderAttachmentsLinks($attachmentsInfo): array
    {
        // return array_map(function ($attachment) {
        //     $url = htmlspecialchars((string) $attachment->url);
        //     return '<a href="'.$url.'" target="_blank">'.$attachment->text.'</a>';
        // }, $attachmentsInfo);
        $result = [];
        foreach ($attachmentsInfo as $attachment) {
            $url = htmlspecialchars((string) $attachment->url);
            $result[$attachment->id] = '<a href="'.$url.'" target="_blank">'.$attachment->text.'</a>';
        }
        return $result;
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

    public static function getUserCvs($user = null): array
    {
        $result = [];

        if (!$user) {
            $user = auth()->user();
        }

        $user->load(['candidate']);

        if (!$user->candidate
            || !$user->candidate->load('attachment')) {
            return [];
        }

        $cvs = $user->candidate->getCvAttachmentsInfo();

        foreach ($cvs as $cv) {
            $result[$cv->id] = $cv->text;
        }

        return $result;
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

    public static function feedbackScoreOptions(): array
    {
        return [
            1 => 'Poor',
            2 => 'Fair',
            3 => 'Good',
            4 => 'Very Good',
            5 => 'Outstanding',
        ];
    }
    public static function feedbackDisclosureOptions(): array
    {
        return [
            1 => 'Maybe',
            2 => 'Yes',
            3 => 'No',
            4 => 'Prefer not to answer',
        ];
    }

    public static function getApplicationStatus(JobApplication $jobApplication)
    {
        $status = '';

        switch ($jobApplication->status) {
            case 0:
                $status = '<span class="btn-warning px-2 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">Pending</span>';
                break;
            case 1:
                $status = '<span class="btn-success px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Accepted</span>';
                break;
            case 2:
                $status = '<span class="btn-danger px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Rejected</span>';
                break;
            default:
                # code...
                break;
        }

        return $status;
    }
}

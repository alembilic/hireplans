<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Candidate;

class ActivityService
{
    /**
     * Log a new activity for a candidate.
     */
    public static function log(
        Candidate $candidate,
        string $activityType,
        string $title,
        ?string $description = null,
        ?array $metadata = null,
        ?int $createdBy = null
    ): Activity {
        return Activity::create([
            'candidate_id' => $candidate->id,
            'activity_type' => $activityType,
            'title' => $title,
            'description' => $description,
            'metadata' => $metadata,
            'created_by' => $createdBy,
        ]);
    }

    /**
     * Log profile created activity.
     */
    public static function profileCreated(Candidate $candidate): Activity
    {
        return self::log(
            $candidate,
            Activity::TYPE_PROFILE_CREATED,
            'Profile Created',
            'Candidate profile was successfully created.'
        );
    }

    /**
     * Log job application activity.
     */
    public static function jobApplied(Candidate $candidate, $jobApplication): Activity
    {
        return self::log(
            $candidate,
            Activity::TYPE_JOB_APPLIED,
            'Applied to Job',
            "Applied for position: {$jobApplication->job->title} at {$jobApplication->job->employer->name}",
            [
                'job_id' => $jobApplication->job_id,
                'application_id' => $jobApplication->id,
                'job_title' => $jobApplication->job->title,
                'employer_name' => $jobApplication->job->employer->name,
                'application_ref' => $jobApplication->application_ref,
            ]
        );
    }

    /**
     * Log application status change activity.
     */
    public static function applicationStatusChanged(Candidate $candidate, $jobApplication, $oldStatus, $newStatus, ?int $createdBy = null): Activity
    {
        $statusEnum = \App\Enums\JobApplicationStatus::fromValue($newStatus);
        $oldStatusEnum = \App\Enums\JobApplicationStatus::fromValue($oldStatus);
        
        return self::log(
            $candidate,
            Activity::TYPE_APPLICATION_STATUS_CHANGED,
            'Application Status Updated',
            "Application status changed from '{$oldStatusEnum->label()}' to '{$statusEnum->label()}' for {$jobApplication->job->title}",
            [
                'job_id' => $jobApplication->job_id,
                'application_id' => $jobApplication->id,
                'job_title' => $jobApplication->job->title,
                'employer_name' => $jobApplication->job->employer->name,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'old_status_label' => $oldStatusEnum->label(),
                'new_status_label' => $statusEnum->label(),
            ],
            $createdBy
        );
    }

    /**
     * Log meeting scheduled activity.
     */
    public static function meetingScheduled(Candidate $candidate, $meeting, ?int $createdBy = null): Activity
    {
        $scheduledTime = $meeting->scheduled_at ? $meeting->scheduled_at->format('M j, Y g:i A') : 'Unknown time';
        
        return self::log(
            $candidate,
            Activity::TYPE_MEETING_SCHEDULED,
            'Meeting Scheduled',
            "Meeting '{$meeting->title}' scheduled for {$scheduledTime}",
            [
                'meeting_id' => $meeting->id,
                'meeting_title' => $meeting->title,
                'meeting_type' => $meeting->type,
                'scheduled_at' => $meeting->scheduled_at ? $meeting->scheduled_at->toISOString() : null,
                'job_id' => $meeting->job_id,
                'job_title' => $meeting->job->title ?? null,
            ],
            $createdBy
        );
    }

    /**
     * Log meeting updated activity.
     */
    public static function meetingUpdated(Candidate $candidate, $meeting, ?int $createdBy = null): Activity
    {
        return self::log(
            $candidate,
            Activity::TYPE_MEETING_UPDATED,
            'Meeting Updated',
            "Meeting '{$meeting->title}' was updated",
            [
                'meeting_id' => $meeting->id,
                'meeting_title' => $meeting->title,
                'meeting_type' => $meeting->type,
                'scheduled_at' => $meeting->scheduled_at->toISOString(),
                'job_id' => $meeting->job_id,
                'job_title' => $meeting->job->title ?? null,
            ],
            $createdBy
        );
    }

    /**
     * Log meeting status changed activity.
     */
    public static function meetingStatusChanged(Candidate $candidate, $meeting, string $oldStatus, string $newStatus, ?int $createdBy = null): Activity
    {
        return self::log(
            $candidate,
            Activity::TYPE_MEETING_STATUS_CHANGED,
            'Meeting Status Changed',
            "Meeting '{$meeting->title}' status changed from " . ucfirst($oldStatus) . " to " . ucfirst($newStatus),
            [
                'meeting_id' => $meeting->id,
                'meeting_title' => $meeting->title,
                'meeting_type' => $meeting->type,
                'scheduled_at' => $meeting->scheduled_at->toISOString(),
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'job_id' => $meeting->job_id,
                'job_title' => $meeting->job->title ?? null,
            ],
            $createdBy
        );
    }

    /**
     * Log note added activity.
     */
    public static function noteAdded(Candidate $candidate, string $note, int $createdBy): Activity
    {
        return self::log(
            $candidate,
            Activity::TYPE_NOTE_ADDED,
            'Note Added',
            $note,
            null,
            $createdBy
        );
    }

    /**
     * Log profile updated activity.
     */
    public static function profileUpdated(Candidate $candidate, ?int $createdBy = null): Activity
    {
        return self::log(
            $candidate,
            Activity::TYPE_PROFILE_UPDATED,
            'Profile Updated',
            'Candidate profile information was updated.',
            null,
            $createdBy
        );
    }

    /**
     * Log document uploaded activity.
     */
    public static function documentUploaded(Candidate $candidate, string $documentType, string $fileName, ?int $createdBy = null): Activity
    {
        return self::log(
            $candidate,
            Activity::TYPE_DOCUMENT_UPLOADED,
            'Document Uploaded',
            "New {$documentType} uploaded: {$fileName}",
            [
                'document_type' => $documentType,
                'file_name' => $fileName,
            ],
            $createdBy
        );
    }

    /**
     * Log reference requested activity.
     */
    public static function referenceRequested(Candidate $candidate, $reference, ?int $createdBy = null): Activity
    {
        return self::log(
            $candidate,
            Activity::TYPE_REFERENCE_REQUESTED,
            'Reference Requested',
            "Reference request sent to {$reference->name} at {$reference->company}",
            [
                'reference_id' => $reference->id,
                'reference_name' => $reference->name,
                'reference_email' => $reference->email,
                'reference_company' => $reference->company,
            ],
            $createdBy
        );
    }

    /**
     * Log reference completed activity.
     */
    public static function referenceCompleted(Candidate $candidate, $reference): Activity
    {
        return self::log(
            $candidate,
            Activity::TYPE_REFERENCE_COMPLETED,
            'Reference Completed',
            "Reference completed by {$reference->name} at {$reference->company}",
            [
                'reference_id' => $reference->id,
                'reference_name' => $reference->name,
                'reference_email' => $reference->email,
                'reference_company' => $reference->company,
                'completed_at' => $reference->completed_at->toISOString(),
            ]
        );
    }
} 
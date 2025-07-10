<?php

namespace App\Orchid\Layouts\Job;

use Orchid\Screen\Layouts\View;

class JobPipelineApplications extends View
{
    /**
     * @var string
     */
    protected $template = 'layouts.job.pipeline-applications';

    /**
     * View constructor.
     */
    public function __construct()
    {
        parent::__construct($this->template);
    }
} 
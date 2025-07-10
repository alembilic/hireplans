<?php

namespace App\Orchid\Layouts\Job;

use Orchid\Screen\Layouts\View;

class JobPipelineTabs extends View
{
    /**
     * @var string
     */
    protected $template = 'layouts.job.pipeline-tabs';

    /**
     * View constructor.
     */
    public function __construct()
    {
        parent::__construct($this->template);
    }
} 
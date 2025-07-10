<?php

namespace App\Orchid\Layouts\Job;

use Orchid\Screen\Layouts\View;

class JobPipelineLayout extends View
{
    /**
     * @var string
     */
    protected $template = 'layouts.job.pipeline-layout';

    /**
     * View constructor.
     */
    public function __construct()
    {
        parent::__construct($this->template);
    }
} 
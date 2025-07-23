<?php

namespace App\Livewire;

use App\Models\Candidate;
use App\Models\Activity;
use App\Services\ActivityService;
use Livewire\Component;

class CandidateProfileView extends Component
{
    public Candidate $candidate;
    public $cvLinks = [];
    public $otherDocumentsLinks = [];
    public $newSkill = '';
    public $newLanguage = '';
    public $showSkillInput = false;
    public $showLanguageInput = false;
    public $showActivity = false;
    public $showNoteInput = false;
    public $newNote = '';
    public $activities;

    public function mount(Candidate $candidate, $cvLinks = [], $otherDocumentsLinks = [])
    {
        $candidate->load(['user', 'attachment', 'jobApplications.job.employer']);
        $this->candidate = $candidate;
        $this->cvLinks = $cvLinks;
        $this->otherDocumentsLinks = $otherDocumentsLinks;
        $this->loadActivities();
    }

    public function addSkill()
    {
        $this->validate([
            'newSkill' => 'required|string|max:255'
        ]);

        $skill = trim($this->newSkill);
        
        // Get current skills
        $currentSkills = $this->candidate->skills ? explode(',', $this->candidate->skills) : [];
        $currentSkills = array_map('trim', $currentSkills);
        
        // Check if skill already exists
        if (in_array($skill, $currentSkills)) {
            $this->addError('newSkill', 'This skill already exists');
            return;
        }
        
        // Add new skill
        $currentSkills[] = $skill;
        $this->candidate->skills = implode(', ', $currentSkills);
        $this->candidate->save();
        
        // Reset input
        $this->newSkill = '';
        $this->showSkillInput = false;
        
        session()->flash('success', 'Skill added successfully');
    }

    public function removeSkill($skill)
    {
        $skill = trim($skill);
        
        // Get current skills
        $currentSkills = $this->candidate->skills ? explode(',', $this->candidate->skills) : [];
        $currentSkills = array_map('trim', $currentSkills);
        
        // Remove the skill
        $currentSkills = array_filter($currentSkills, function($s) use ($skill) {
            return $s !== $skill;
        });
        
        // Update candidate
        $this->candidate->skills = $currentSkills ? implode(', ', $currentSkills) : null;
        $this->candidate->save();
        
        session()->flash('success', 'Skill removed successfully');
    }

    public function addLanguage()
    {
        $this->validate([
            'newLanguage' => 'required|string|max:255'
        ]);

        $language = trim($this->newLanguage);
        
        // Get current languages
        $currentLanguages = $this->candidate->languages ? explode(',', $this->candidate->languages) : [];
        $currentLanguages = array_map('trim', $currentLanguages);
        
        // Check if language already exists
        if (in_array($language, $currentLanguages)) {
            $this->addError('newLanguage', 'This language already exists');
            return;
        }
        
        // Add new language
        $currentLanguages[] = $language;
        $this->candidate->languages = implode(', ', $currentLanguages);
        $this->candidate->save();
        
        // Reset input
        $this->newLanguage = '';
        $this->showLanguageInput = false;
        
        session()->flash('success', 'Language added successfully');
    }

    public function removeLanguage($language)
    {
        $language = trim($language);
        
        // Get current languages
        $currentLanguages = $this->candidate->languages ? explode(',', $this->candidate->languages) : [];
        $currentLanguages = array_map('trim', $currentLanguages);
        
        // Remove the language
        $currentLanguages = array_filter($currentLanguages, function($l) use ($language) {
            return $l !== $language;
        });
        
        // Update candidate
        $this->candidate->languages = $currentLanguages ? implode(', ', $currentLanguages) : null;
        $this->candidate->save();
        
        session()->flash('success', 'Language removed successfully');
    }

    public function toggleSkillInput()
    {
        $this->showSkillInput = !$this->showSkillInput;
        $this->newSkill = '';
        $this->resetErrorBag('newSkill');
    }

    public function toggleLanguageInput()
    {
        $this->showLanguageInput = !$this->showLanguageInput;
        $this->newLanguage = '';
        $this->resetErrorBag('newLanguage');
    }

    public function getSkillsArrayProperty()
    {
        return $this->candidate->skills ? array_map('trim', explode(',', $this->candidate->skills)) : [];
    }

    public function getLanguagesArrayProperty()
    {
        return $this->candidate->languages ? array_map('trim', explode(',', $this->candidate->languages)) : [];
    }

    public function toggleActivity()
    {
        $this->showActivity = !$this->showActivity;
        if ($this->showActivity) {
            $this->loadActivities();
        }
    }

    public function loadActivities()
    {
        $this->activities = $this->candidate->activities()
            ->with(['createdBy'])
            ->get();
    }

    public function toggleNoteInput()
    {
        $this->showNoteInput = !$this->showNoteInput;
        $this->newNote = '';
        $this->resetErrorBag('newNote');
    }

    public function addNote()
    {
        $this->validate([
            'newNote' => 'required|string|max:1000'
        ]);

        ActivityService::noteAdded($this->candidate, trim($this->newNote), auth()->id());

        $this->newNote = '';
        $this->showNoteInput = false;
        $this->loadActivities();
        
        session()->flash('success', 'Note added successfully');
    }

    public function render()
    {
        return view('livewire.candidate-profile-view');
    }
}

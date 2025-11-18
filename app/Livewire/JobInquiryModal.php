<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use App\Mail\JobInquiryMail;

class JobInquiryModal extends Component
{
    public $email = '';
    public $company = '';
    public $position = '';
    public $location = '';
    public $details = '';
    public $showModal = false;

    protected $listeners = ['openModal'];

    protected $rules = [
        'email' => 'required|email|max:255',
        'company' => 'required|string|max:255',
        'position' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'details' => 'required|string|max:2000',
    ];

    protected $messages = [
        'email.required' => 'Please provide your email address.',
        'email.email' => 'Please provide a valid email address.',
        'company.required' => 'Please provide your company name.',
        'position.required' => 'Please specify the position you are hiring for.',
        'location.required' => 'Please provide the job location.',
        'details.required' => 'Please provide details about the position.',
    ];

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['email', 'company', 'position', 'location', 'details']);
        $this->resetValidation();
    }

    public function submitInquiry()
    {
        $validated = $this->validate();

        try {
            // Send email to company
            Mail::to(env('COMPANY_EMAIL', 'cv@hireplans.com'))
                ->send(new JobInquiryMail($validated));

            // Success message
            session()->flash('inquiry_success', 'Thank you! Your inquiry has been submitted. We will contact you soon.');
            
            // Close modal and reset form
            $this->closeModal();
        } catch (\Exception $e) {
            // Error handling
            session()->flash('inquiry_error', 'There was an error submitting your inquiry. Please try again or contact us directly.');
            \Log::error('Job Inquiry Submission Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.job-inquiry-modal');
    }
}

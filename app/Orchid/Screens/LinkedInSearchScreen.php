<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Illuminate\Http\Request;
use App\Services\LinkedInSearchService;
use Orchid\Support\Facades\Toast;
use App\Orchid\Layouts\LinkedInSearchResultsLayout;
use Orchid\Screen\Actions\Link;

class LinkedInSearchScreen extends Screen
{
    /**
     * @var LinkedInSearchService
     */
    private $linkedInService;

    public function __construct(LinkedInSearchService $linkedInService)
    {
        $this->linkedInService = $linkedInService;
    }

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Request $request): iterable
    {
        $searchQuery = $request->get('search_query', '');
        $location = $request->get('location', '');
        $title = $request->get('title', '');
        $results = [];
        $totalCount = 0;
        $searchTime = '';

        // Always search - if no criteria provided, show all candidates
        $filters = array_filter([
            'location' => $location,
            'title' => $title,
        ]);

        // Debug: Let's see what we're searching for
        \Log::info('LinkedIn Search Query Method:', [
            'searchQuery' => $searchQuery,
            'location' => $location, 
            'title' => $title,
            'filters' => $filters
        ]);

        $searchResults = $this->linkedInService->searchCandidates($searchQuery, $filters);
        
        // Debug: Let's see the results count
        \Log::info('LinkedIn Search Results Count:', [
            'total_count' => $searchResults['total_count'] ?? 0,
            'results_count' => count($searchResults['results'] ?? [])
        ]);

        $results = $searchResults['results'] ?? [];
        $totalCount = $searchResults['total_count'] ?? 0;
        $searchTime = $searchResults['search_time'] ?? '';

        return [
            'search_results' => $results,
            'search_query' => $searchQuery,
            'location' => $location,
            'title' => $title,
            'total_count' => $totalCount,
            'search_time' => $searchTime,
            'has_searched' => true, // Always show results area
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'LinkedIn Candidate Search';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Search LinkedIn')
                ->icon('bs.search')
                ->method('search'),
            
            Link::make('Back to Candidates')
                ->icon('bs.arrow-left')
                ->route('platform.candidates.list')
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('search_query')
                    ->title('Search Keywords')
                    ->placeholder('Try: engineer, manager, python, react, or leave blank to see all')
                    ->help('Enter job titles, skills, or keywords. Try "engineer" or "manager" or leave blank to see all candidates.')
                    ->horizontal(),

                Input::make('location')
                    ->title('Location')
                    ->placeholder('e.g. San Francisco, New York, Remote')
                    ->help('Filter candidates by location')
                    ->horizontal(),

                Input::make('title')
                    ->title('Job Title')
                    ->placeholder('e.g. Senior Developer, Marketing Manager')
                    ->help('Filter by specific job titles')
                    ->horizontal(),
            ])->title('Search Filters'),

            // Search Results Section  
            Layout::view('platform.linkedin-search.search-info'),

            LinkedInSearchResultsLayout::class,
        ];
    }

    /**
     * Handle search action
     *
     * @param Request $request
     */
    public function search(Request $request)
    {
        $searchQuery = $request->get('search_query', '');
        $location = $request->get('location', '');
        $title = $request->get('title', '');

        // Debug: Let's see what's being submitted
        \Log::info('Search form submission:', [
            'all_request' => $request->all(),
            'search_query' => $searchQuery,
            'location' => $location,
            'title' => $title,
        ]);

        // Debug: Let's see all the form data being submitted
        \Log::info('Complete form submission debug:', [
            'all_input' => $request->all(),
            'method' => $request->method(),
            'search_query_direct' => $request->input('search_query'),
            'location_direct' => $request->input('location'),
            'title_direct' => $request->input('title'),
        ]);

        if (empty($searchQuery) && empty($location) && empty($title)) {
            Toast::warning('Please enter at least one search criteria');
            return;
        }

        // Store search parameters in session to persist them
        session()->put('linkedin_search_params', [
            'search_query' => $searchQuery,
            'location' => $location,
            'title' => $title,
        ]);

        Toast::success('Searching LinkedIn for candidates...');
        
        // Redirect to the same screen with search parameters
        return redirect()->route('platform.linkedin.search', [
            'search_query' => $searchQuery,
            'location' => $location,
            'title' => $title,
        ]);
    }

    /**
     * Get candidate details
     *
     * @param Request $request
     */
    public function getCandidateDetails(Request $request)
    {
        $profileId = $request->get('profile_id');
        
        if (!$profileId) {
            Toast::error('Profile ID is required');
            return;
        }

        $candidateDetails = $this->linkedInService->getCandidateDetails($profileId);
        
        if (!$candidateDetails) {
            Toast::error('Candidate not found');
            return;
        }

        // Store candidate details in session for modal display
        session()->put('linkedin_candidate_details', $candidateDetails);
        
        Toast::success('Candidate details loaded');
    }

    /**
     * Add candidate to pipeline
     *
     * @param Request $request
     */
    public function addToPipeline(Request $request)
    {
        $profileData = $request->get('profile_data');
        
        if (!$profileData) {
            Toast::error('Profile data is missing');
            return;
        }

        try {
            // Check if user already exists
            $existingUser = \App\Models\User::where('email', $profileData['profile_url'])->first(); // Using profile_url as unique identifier since we don't have email
            
            if ($existingUser && $existingUser->candidate) {
                Toast::warning('Candidate already exists in the system');
                return redirect()->route('platform.candidates.view', $existingUser->candidate->id);
            }

            // Create new user from LinkedIn data
            $user = \App\Models\User::create([
                'name' => $profileData['name'],
                'email' => str_replace(['https://linkedin.com/in/', 'https://www.linkedin.com/in/'], '', $profileData['profile_url']) . '@linkedin.temp', // Temporary email
                'password' => \Illuminate\Support\Facades\Hash::make('temp_password_' . uniqid()),
            ]);

            // Create candidate profile
            $candidate = \App\Models\Candidate::create([
                'user_id' => $user->id,
                'candidate_ref' => 'LI_' . strtoupper(substr(md5($profileData['id']), 0, 8)),
                'current_company' => $profileData['company'],
                'current_job_title' => $profileData['title'],
                'skills' => implode(', ', $profileData['skills']),
                'notes' => "Imported from LinkedIn:\n" . 
                          "LinkedIn URL: {$profileData['profile_url']}\n" .
                          "Headline: {$profileData['headline']}\n" .
                          "Summary: {$profileData['summary']}\n" .
                          "Connections: {$profileData['connections']}\n" .
                          "Relevance Score: {$profileData['relevance_score']}%"
            ]);

            Toast::success('Candidate successfully added to pipeline');
            return redirect()->route('platform.candidates.view', $candidate->id);

        } catch (\Exception $e) {
            Toast::error('Failed to add candidate: ' . $e->getMessage());
        }
    }

    /**
     * Send message to candidate
     *
     * @param Request $request
     */
    public function sendMessage(Request $request)
    {
        $profileId = $request->get('profile_id');
        
        if (!$profileId) {
            Toast::error('Profile ID is required');
            return;
        }

        // In a real implementation, this would integrate with LinkedIn messaging API
        // For now, we'll simulate the action
        Toast::info('Message functionality coming soon. This would typically open LinkedIn messaging or integrate with your messaging system.');
        
        // Could redirect to a compose message screen or modal
        // return redirect()->route('platform.messaging.compose', ['profile_id' => $profileId]);
    }

    /**
     * Get the permissions required to access this screen.
     *
     * @return iterable|null
     */
    public function permission(): ?iterable
    {
        return [
            'platform.systems.users',
        ];
    }
}

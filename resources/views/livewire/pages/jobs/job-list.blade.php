<div>
    <!-- Search & Filters Section -->
    <div class="search-filters mb-8" style="background-color: #FFFFFF; border: 1px solid #EAEAEA; border-radius: 0.75rem; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
        <!-- Form Fields Row -->
        <div class="d-flex flex-wrap gap-3 align-items-end mb-3">
            <!-- Search Input -->
            <div class="flex-fill w-100" style="min-width: 280px; max-width: 320px;">
                <label style="font-weight: 600; color: #4B4B4B; margin-bottom: 0.5rem; display: block; font-size: 0.875rem;">Search Jobs</label>
                <input type="text" wire:model.defer="search" wire:keydown.enter="searchJobs" 
                       placeholder="Job title, company, or keywords..." 
                       class="form-control" 
                       style="padding: 0.875rem 1rem; border: 1px solid #EAEAEA; border-radius: 0.375rem; font-size: 0.9375rem; width: 100%;">
            </div>
            
            <!-- Location Input -->
            <div class="flex-grow-1 w-100" style="min-width: 200px; max-width: 240px;">
                <label style="font-weight: 600; color: #4B4B4B; margin-bottom: 0.5rem; display: block; font-size: 0.875rem;">Location</label>
                <input type="text" wire:model.defer="location" wire:keydown.enter="searchJobs" 
                       placeholder="City or country..." 
                       class="form-control" 
                       style="padding: 0.875rem 1rem; border: 1px solid #EAEAEA; border-radius: 0.375rem; font-size: 0.9375rem; width: 100%;">
            </div>
            
            <!-- Job Type Select -->
            <div class="flex-grow-1 w-100" style="min-width: 200px; max-width: 240px;">
                <label style="font-weight: 600; color: #4B4B4B; margin-bottom: 0.5rem; display: block; font-size: 0.875rem;">Job Type</label>
                <select wire:model.defer="job_type" wire:change="searchJobs" 
                        class="form-select" 
                        style="padding: 0.875rem 1rem; border: 1px solid #EAEAEA; border-radius: 0.375rem; font-size: 0.9375rem; width: 100%;">
                    <option value="">All Types</option>
                    @foreach($jobTypes as $jobType)
                        <option value="{{ $jobType }}">{{ $jobType }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons Row -->
        <div class="gap-2 justify-content-center justify-content-md-start flex items-end">
            <div class="">
            <button wire:click="searchJobs" 
                    style="background-color: #F4C542; color: #0A0A0A; border: none; padding: 0.875rem 1.5rem; border-radius: 0.375rem; font-weight: 600; font-size: 0.9375rem; cursor: pointer; transition: all 0.2s ease;"
                    onmouseover="this.style.backgroundColor='#D4A017'" 
                    onmouseout="this.style.backgroundColor='#F4C542'">
                Search
            </button>
            <button wire:click="resetFilters" 
                    style="background-color: transparent; color: #4B4B4B; border: 1px solid #EAEAEA; padding: 0.625rem 1rem; border-radius: 0.375rem; font-weight: 500; font-size: 0.875rem; cursor: pointer; transition: all 0.2s ease;"
                    onmouseover="this.style.backgroundColor='#FAFAFA'" 
                    onmouseout="this.style.backgroundColor='transparent'">
                Reset
            </button>
        </div>
        </div>
    </div>

    <!-- Jobs Results Section -->
    <div class="jobs-results">
        @if($jobs->count() > 0)
            <!-- Results Count -->
            <div class="mb-4">
                <p style="color: #4B4B4B; font-size: 0.9375rem; margin: 0;">
                    Showing {{ $jobs->firstItem() }} - {{ $jobs->lastItem() }} of {{ $jobs->total() }} jobs
                </p>
            </div>
            
            <!-- Job Cards Grid -->
            <div class="grid grid-cols-1 gap-5 mb-6">
                @foreach($jobs as $job)
                    <div class="col-12">
                        <div class="job-card" 
                             style="background-color: #FFFFFF; border: 1px solid #EAEAEA; border-radius: 0.75rem; padding: 2rem; transition: all 0.3s ease; cursor: pointer;"
                             onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 10px 15px -3px rgba(0, 0, 0, 0.1)'; this.style.borderColor='#F4C542';"
                             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 6px -1px rgba(0, 0, 0, 0.1)'; this.style.borderColor='#EAEAEA';"
                             onclick="window.location.href='{{ route('jobs.details', $job->id) }}'">
                            
                            <div class="row align-items-center">
                                <!-- Job Info with Logo -->
                                <div class="col-md-9 col-lg-9">
                                    <div class="d-flex flex-column flex-md-row align-items-center align-items-md-start gap-3">
                                        <!-- Company Logo -->
                                        <div class="flex-shrink-0 align-self-stretch d-flex align-items-start">
                                            @if($job->employer->logo)
                                                <img src="{{ $job->employer->logo }}" alt="{{ $job->employer->name }}" 
                                                     style="width: 60px; min-height: 60px; max-height: 100%; height: auto; border-radius: 0.5rem; object-fit: cover; border: 1px solid #EAEAEA;">
                                            @else
                                                <div style="width: 60px; min-height: 60px; height: auto; background-color: #F4C542; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center;">
                                                    <span style="font-weight: 700; color: #0A0A0A; font-size: 1.25rem;">{{ substr($job->employer->name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Job Details -->
                                        <div class="flex-grow-1 text-start">
                                    <h3 style="font-size: 1.375rem; font-weight: 700; color: #0A0A0A; margin-bottom: 0.5rem; line-height: 1.3;">
                                        {{ $job->title }}
                                    </h3>
                                    
                                    <p style="font-size: 1rem; font-weight: 600; color: #4B4B4B; margin-bottom: 0.75rem;">
                                        {{ $job->employer->name }}
                                    </p>
                                    
                                    <div class="job-details" style="margin-bottom: 1rem;">
                                         <div class="flex flex-wrap gap-2 justify-start">
                                            <span style="background-color: #FFFBF0; color: #D4A017; padding: 0.375rem 0.75rem; border-radius: 0.375rem; font-size: 0.8125rem; font-weight: 600; margin-right: 0.25rem; margin-bottom: 0.25rem;">
                                                <i class="bi bi-geo-alt-fill me-1"></i>{{ $job->location }}
                                            </span>
                                            @if($job->salary)
                                                <span style="background-color: #F0FDF4; color: #059669; padding: 0.375rem 0.75rem; border-radius: 0.375rem; font-size: 0.8125rem; font-weight: 600; margin-right: 0.25rem; margin-bottom: 0.25rem;">
                                                    <i class="bi bi-cash-coin me-1"></i>{{ $job->salary }}
                                                </span>
                                            @endif
                                            @if($job->job_type)
                                                <span style="background-color: #EFF6FF; color: #2563EB; padding: 0.375rem 0.75rem; border-radius: 0.375rem; font-size: 0.8125rem; font-weight: 600; margin-right: 0.25rem; margin-bottom: 0.25rem;">
                                                    <i class="bi bi-briefcase-fill me-1"></i>{{ $job->job_type }}
                                                </span>
                                            @endif
                                            @if($job->experience_level)
                                                <span style="background-color: #FDF2F8; color: #BE185D; padding: 0.375rem 0.75rem; border-radius: 0.375rem; font-size: 0.8125rem; font-weight: 600; margin-right: 0.25rem; margin-bottom: 0.25rem;">
                                                    <i class="bi bi-graph-up-arrow me-1"></i>{{ $job->experience_level }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if($job->details)
                                        <p style="color: #4B4B4B; font-size: 0.9375rem; margin-top: 0.5rem; margin-bottom: 0; line-height: 1.5;">
                                            {{ Str::limit(strip_tags($job->details), 120) }}
                                        </p>
                                    @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Action & Date -->
                                <div class="col-md-3 col-lg-3 text-md-end">
                                    <div class="mb-3">
                                        <small style="color: #6B7280; font-size: 0.8125rem;">
                                            Posted {{ $job->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    
                                    <a href="{{ route('jobs.details', $job->id) }}" 
                                       style="display: inline-block; background-color: #F4C542; color: #0A0A0A; padding: 0.75rem 1.5rem; border-radius: 0.375rem; font-weight: 600; font-size: 0.9375rem; text-decoration: none; transition: all 0.2s ease;"
                                       onmouseover="this.style.backgroundColor='#D4A017'" 
                                       onmouseout="this.style.backgroundColor='#F4C542'">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Custom Pagination -->
            @if($jobs->hasPages())
                <div class="custom-pagination d-flex flex-nowrap justify-content-center align-items-center gap-2 flex" style="margin-top: 2rem; overflow-x: auto;">
                    <!-- Previous Button -->
                    @if($jobs->onFirstPage())
                        <button disabled class="pagination-btn pagination-btn-disabled">
                            <svg width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                            </svg>
                            Previous
                        </button>
                    @else
                        <button wire:click="previousPage" class="pagination-btn pagination-btn-default">
                            <svg width="16" height="16" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                            </svg>
                            Previous
                        </button>
                    @endif

                    <!-- Page Numbers -->
                    @php
                        $currentPage = $jobs->currentPage();
                        $lastPage = $jobs->lastPage();
                        $start = max(1, $currentPage - 2);
                        $end = min($lastPage, $currentPage + 2);
                    @endphp

                    @if($start > 1)
                        <button wire:click="goToPage(1)" class="pagination-btn pagination-btn-number">1</button>
                        @if($start > 2)
                            <span class="pagination-dots">...</span>
                        @endif
                    @endif

                    @for($i = $start; $i <= $end; $i++)
                        @if($i == $currentPage)
                            <button class="pagination-btn pagination-btn-active">{{ $i }}</button>
                        @else
                            <button wire:click="goToPage({{ $i }})" class="pagination-btn pagination-btn-number">{{ $i }}</button>
                        @endif
                    @endfor

                    @if($end < $lastPage)
                        @if($end < $lastPage - 1)
                            <span class="pagination-dots">...</span>
                        @endif
                        <button wire:click="goToPage({{ $lastPage }})" class="pagination-btn pagination-btn-number">{{ $lastPage }}</button>
                    @endif

                    <!-- Next Button -->
                    @if($jobs->hasMorePages())
                        <button wire:click="nextPage" class="pagination-btn pagination-btn-default">
                            Next
                            <svg width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </button>
                    @else
                        <button disabled class="pagination-btn pagination-btn-disabled">
                            Next
                            <svg width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </button>
                    @endif
                </div>
                
                <!-- Page Info -->
                <div class="pagination-info text-center mt-3">
                    <span style="color: #6B7280; font-size: 0.875rem;">
                        Showing {{ $jobs->firstItem() ?? 0 }} to {{ $jobs->lastItem() ?? 0 }} of {{ $jobs->total() }} results
                    </span>
                </div>

                <style>
                    .pagination-btn {
                        padding: 0.5rem 0.75rem;
                        border: 1px solid #EAEAEA;
                        background-color: #FFFFFF;
                        color: #4B4B4B;
                        font-weight: 500;
                        border-radius: 0.375rem;
                        transition: all 0.2s ease;
                        cursor: pointer;
                        display: flex;
                        align-items: center;
                        gap: 0.375rem;
                        font-size: 0.875rem;
                        text-decoration: none;
                        min-width: 2.5rem;
                        justify-content: center;
                        white-space: nowrap;
                        flex-shrink: 0;
                    }

                    .pagination-btn-default:hover {
                        background-color: #F4C542;
                        border-color: #D4A017;
                        color: #0A0A0A;
                        transform: translateY(-1px);
                        box-shadow: 0 2px 4px rgba(244, 197, 66, 0.2);
                    }

                    .pagination-btn-number:hover {
                        background-color: #F4C542;
                        border-color: #D4A017;
                        color: #0A0A0A;
                        transform: translateY(-1px);
                        box-shadow: 0 2px 4px rgba(244, 197, 66, 0.2);
                    }

                    .pagination-btn-active {
                        background-color: #F4C542;
                        border-color: #D4A017;
                        color: #0A0A0A;
                        font-weight: 600;
                        box-shadow: 0 2px 4px rgba(244, 197, 66, 0.3);
                    }

                    .pagination-btn-disabled {
                        background-color: #F9FAFB;
                        border-color: #E5E7EB;
                        color: #9CA3AF;
                        cursor: not-allowed;
                    }

                    .pagination-btn-disabled:hover {
                        background-color: #F9FAFB;
                        border-color: #E5E7EB;
                        color: #9CA3AF;
                        transform: none;
                        box-shadow: none;
                    }

                    .pagination-dots {
                        color: #9CA3AF;
                        padding: 0.5rem 0.25rem;
                        font-weight: 500;
                    }

                    .pagination-info {
                        font-family: 'Inter', sans-serif;
                    }

                    /* Mobile responsive styles for search-filters */
                    @media (max-width: 768px) {
                        .search-filters {
                            background-color: transparent !important;
                            border: none !important;
                            padding: 0 !important;
                            box-shadow: none !important;
                        }
                    }
                </style>
            @endif
        @else
            <!-- No Results State -->
            <div class="text-center py-8" style="background-color: #FFFFFF; border: 1px solid #EAEAEA; border-radius: 0.75rem; padding: 4rem 2rem;">
                <div style="font-size: 3rem; color: #D1D5DB; margin-bottom: 1rem;">
                    <i class="bi bi-search"></i>
                </div>
                <h3 style="font-size: 1.25rem; font-weight: 600; color: #4B4B4B; margin-bottom: 0.5rem;">
                    No jobs found
                </h3>
                <p style="color: #6B7280; margin-bottom: 1.5rem;">
                    Try adjusting your search criteria or browse all available positions.
                </p>
                <button wire:click="resetFilters" 
                        style="background-color: #F4C542; color: #0A0A0A; border: none; padding: 0.75rem 1.5rem; border-radius: 0.375rem; font-weight: 600; cursor: pointer;">
                    View All Jobs
                </button>
            </div>
        @endif
    </div>
</div>

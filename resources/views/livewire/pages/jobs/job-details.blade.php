<div class="py-8" style="background-color: #FAFAFA; min-height: calc(100vh - 73px);">
    <div class="container">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="{{ route('jobs.listings', ['page' => session('page', 1)]) }}" 
               style="color: #F4C542; text-decoration: none; font-weight: 500; font-size: 0.95rem;"
               onmouseover="this.style.color='#D4A017'" 
               onmouseout="this.style.color='#F4C542'">
                <i class="bi bi-arrow-left me-2"></i>Back to Job Listings
            </a>
        </div>

        <!-- Job Details Card -->
        <div style="background: #FFFFFF; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); padding: 2.5rem; margin-bottom: 2rem;">
            <!-- Job Header -->
            <div class="row align-items-start mb-4">
                <div class="col-12">
                    <div class="d-flex align-items-center mb-4">
                        @if($job->employer && $job->employer->logo)
                            <div style="width: 80px; height: 80px; border-radius: 12px; overflow: hidden; margin-right: 1.5rem; flex-shrink: 0; display: flex; align-items: center; justify-content: center; background: #F8F9FA;">
                                <img src="{{ $job->employer->logo }}" alt="{{ $job->employer->name ?? 'Company' }} Logo" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @else
                            <div style="width: 80px; height: 80px; border-radius: 12px; background: linear-gradient(135deg, #F4C542 0%, #D4A017 100%); display: flex; align-items: center; justify-content: center; margin-right: 1.5rem; flex-shrink: 0;">
                                <span style="color: #0A0A0A; font-weight: 600; font-size: 1.5rem;">
                                    {{ substr($job->employer->name ?? 'Company', 0, 1) }}
                                </span>
                            </div>
                        @endif
                        <div style="flex: 1;">
                            <h1 style="font-size: 2.25rem; font-weight: 700; color: #0A0A0A; margin-bottom: 0.5rem; line-height: 1.2;">
                                {{ $job->title }}
                            </h1>
                            <h2 style="font-size: 1.25rem; font-weight: 600; color: #4B4B4B; margin-bottom: 0;">
                                {{ $job->employer->name ?? 'Company Name' }}
                            </h2>
                        </div>
                    </div>

                    <!-- Job Details Tiles -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-5">
                        <x-job-detail-tile 
                            icon="bi-geo-alt-fill"
                            label="Location"
                            :value="$job->location ?? 'Not Specified'" />

                        <x-job-detail-tile 
                            icon="bi-cash-coin"
                            label="Salary"
                            :value="$job->salary ?? 'Not Specified'" />

                        <x-job-detail-tile 
                            icon="bi-briefcase-fill"
                            label="Job Type"
                            :value="$job->job_type ?? 'Not Specified'" />

                        <x-job-detail-tile 
                            icon="bi-award-fill"
                            label="Experience Level"
                            :value="$job->experience_level ?? 'Not Specified'" />

                        @if($job->category)
                        <x-job-detail-tile 
                            icon="bi-tag-fill"
                            label="Category"
                            :value="$job->category" />
                        @endif

                        <x-job-detail-tile 
                            icon="bi-calendar-event"
                            label="Posted On"
                            :value="$job->created_at->format('d M Y')" />
                    </div>

                    <!-- Job Description -->
                    <div class="mt-8">
                        <h3 style="font-size: 1.5rem; font-weight: 700; color: #0A0A0A; margin-bottom: 1.5rem;">Job Description</h3>
                        <div style="font-size: 1rem; line-height: 1.7; color: #4B4B4B;">
                            {!! $job->details !!}
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-3 justify-content-center mt-5 pt-4">
                        @if (!auth()->check())
                            <a href="{{ route('login') }}" 
                               style="background: linear-gradient(135deg, #F4C542 0%, #D4A017 100%); color: #0A0A0A; padding: 0.875rem 2rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 1rem; border: none; transition: all 0.2s ease; box-shadow: 0 2px 8px rgba(244, 197, 66, 0.3);"
                               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(244, 197, 66, 0.4)'"
                               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(244, 197, 66, 0.3)'">
                                <i class="bi bi-person-plus me-2"></i>Login/Register to Apply
                            </a>
                        @elseif ($job->candidateProfileRequired())
                            <a href="{{ route('platform.profile') }}" 
                               style="background: linear-gradient(135deg, #F4C542 0%, #D4A017 100%); color: #0A0A0A; padding: 0.875rem 2rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 1rem; border: none; transition: all 0.2s ease; box-shadow: 0 2px 8px rgba(244, 197, 66, 0.3);"
                               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(244, 197, 66, 0.4)'"
                               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(244, 197, 66, 0.3)'">
                                <i class="bi bi-person-gear me-2"></i>Complete Profile to Apply
                            </a>
                        @elseif ($job->canApply())
                            <a href="{{ route('platform.job_application.create', ['job' => $job]) }}" target="_blank"
                               style="background: linear-gradient(135deg, #F4C542 0%, #D4A017 100%); color: #0A0A0A; padding: 0.875rem 2rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 1rem; border: none; transition: all 0.2s ease; box-shadow: 0 2px 8px rgba(244, 197, 66, 0.3);"
                               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(244, 197, 66, 0.4)'"
                               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(244, 197, 66, 0.3)'">
                                <i class="bi bi-send-fill me-2"></i>Apply Now
                            </a>
                        @endif
                        
                        <a href="{{ route('jobs.listings', ['page' => session('page', 1)]) }}" 
                           style="background: #FFFFFF; color: #4B4B4B; padding: 0.875rem 2rem; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 1rem; border: 2px solid #E5E7EB; transition: all 0.2s ease;"
                           onmouseover="this.style.borderColor='#D4A017'; this.style.color='#0A0A0A'"
                           onmouseout="this.style.borderColor='#E5E7EB'; this.style.color='#4B4B4B'">
                            <i class="bi bi-arrow-left me-2"></i>Back to Listings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


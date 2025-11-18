<div>
    <!-- Trigger Button (hidden, will be triggered programmatically) -->
    
    <!-- Modal Overlay -->
    @if($showModal)
    <div class="modal-overlay" wire:click="closeModal" style="
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.75);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 1rem;
        overflow-y: auto;
    ">
        <!-- Modal Content -->
        <div wire:click.stop class="modal-content" style="
            background: white;
            border-radius: 12px;
            max-width: 600px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            position: relative;
        ">
            <!-- Modal Header -->
            <div style="
                padding: 2rem 2rem 1.5rem;
                border-bottom: 1px solid #EAEAEA;
            ">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <h2 style="
                            margin: 0 0 0.5rem 0;
                            font-size: 1.75rem;
                            font-weight: 700;
                            color: #0A0A0A;
                        ">Post a Job</h2>
                        <p style="
                            margin: 0;
                            color: #6B7280;
                            font-size: 0.9375rem;
                        ">Tell us about the position you're looking to fill</p>
                    </div>
                    <button wire:click="closeModal" type="button" style="
                        background: none;
                        border: none;
                        font-size: 1.5rem;
                        color: #9CA3AF;
                        cursor: pointer;
                        padding: 0;
                        width: 32px;
                        height: 32px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border-radius: 6px;
                        transition: all 0.2s ease;
                    " onmouseover="this.style.backgroundColor='#F3F4F6'; this.style.color='#0A0A0A';" 
                       onmouseout="this.style.backgroundColor='transparent'; this.style.color='#9CA3AF';">
                        ×
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <form wire:submit.prevent="submitInquiry" style="padding: 2rem;">
                <!-- Email -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="email" style="
                        display: block;
                        font-size: 0.875rem;
                        font-weight: 600;
                        color: #4B4B4B;
                        margin-bottom: 0.5rem;
                    ">Your Email <span style="color: #DC2626;">*</span></label>
                    <input wire:model="email" type="email" id="email" required style="
                        width: 100%;
                        padding: 0.75rem 1rem;
                        border: 1px solid #EAEAEA;
                        border-radius: 0.375rem;
                        font-size: 0.9375rem;
                        transition: all 0.2s ease;
                        background-color: #FFFFFF;
                        color: #0A0A0A;
                    " onfocus="this.style.outline='2px solid #F4C542'; this.style.outlineOffset='0'; this.style.borderColor='#F4C542';" 
                       onblur="this.style.outline='none'; this.style.borderColor='#EAEAEA';"
                       placeholder="your.email@company.com" />
                    @error('email')
                        <div style="color: #DC2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Company -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="company" style="
                        display: block;
                        font-size: 0.875rem;
                        font-weight: 600;
                        color: #4B4B4B;
                        margin-bottom: 0.5rem;
                    ">Company Name <span style="color: #DC2626;">*</span></label>
                    <input wire:model="company" type="text" id="company" required style="
                        width: 100%;
                        padding: 0.75rem 1rem;
                        border: 1px solid #EAEAEA;
                        border-radius: 0.375rem;
                        font-size: 0.9375rem;
                        transition: all 0.2s ease;
                        background-color: #FFFFFF;
                        color: #0A0A0A;
                    " onfocus="this.style.outline='2px solid #F4C542'; this.style.outlineOffset='0'; this.style.borderColor='#F4C542';" 
                       onblur="this.style.outline='none'; this.style.borderColor='#EAEAEA';"
                       placeholder="Your Company Name" />
                    @error('company')
                        <div style="color: #DC2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Position -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="position" style="
                        display: block;
                        font-size: 0.875rem;
                        font-weight: 600;
                        color: #4B4B4B;
                        margin-bottom: 0.5rem;
                    ">Position / Role <span style="color: #DC2626;">*</span></label>
                    <input wire:model="position" type="text" id="position" required style="
                        width: 100%;
                        padding: 0.75rem 1rem;
                        border: 1px solid #EAEAEA;
                        border-radius: 0.375rem;
                        font-size: 0.9375rem;
                        transition: all 0.2s ease;
                        background-color: #FFFFFF;
                        color: #0A0A0A;
                    " onfocus="this.style.outline='2px solid #F4C542'; this.style.outlineOffset='0'; this.style.borderColor='#F4C542';" 
                       onblur="this.style.outline='none'; this.style.borderColor='#EAEAEA';"
                       placeholder="e.g., Senior Software Engineer" />
                    @error('position')
                        <div style="color: #DC2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Location -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="location" style="
                        display: block;
                        font-size: 0.875rem;
                        font-weight: 600;
                        color: #4B4B4B;
                        margin-bottom: 0.5rem;
                    ">Location <span style="color: #DC2626;">*</span></label>
                    <input wire:model="location" type="text" id="location" required style="
                        width: 100%;
                        padding: 0.75rem 1rem;
                        border: 1px solid #EAEAEA;
                        border-radius: 0.375rem;
                        font-size: 0.9375rem;
                        transition: all 0.2s ease;
                        background-color: #FFFFFF;
                        color: #0A0A0A;
                    " onfocus="this.style.outline='2px solid #F4C542'; this.style.outlineOffset='0'; this.style.borderColor='#F4C542';" 
                       onblur="this.style.outline='none'; this.style.borderColor='#EAEAEA';"
                       placeholder="e.g., Riyadh, Saudi Arabia" />
                    @error('location')
                        <div style="color: #DC2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Details -->
                <div style="margin-bottom: 1.5rem;">
                    <label for="details" style="
                        display: block;
                        font-size: 0.875rem;
                        font-weight: 600;
                        color: #4B4B4B;
                        margin-bottom: 0.5rem;
                    ">Position Details <span style="color: #DC2626;">*</span></label>
                    <textarea wire:model="details" id="details" required rows="5" style="
                        width: 100%;
                        padding: 0.75rem 1rem;
                        border: 1px solid #EAEAEA;
                        border-radius: 0.375rem;
                        font-size: 0.9375rem;
                        transition: all 0.2s ease;
                        background-color: #FFFFFF;
                        color: #0A0A0A;
                        font-family: inherit;
                        resize: vertical;
                    " onfocus="this.style.outline='2px solid #F4C542'; this.style.outlineOffset='0'; this.style.borderColor='#F4C542';" 
                       onblur="this.style.outline='none'; this.style.borderColor='#EAEAEA';"
                       placeholder="Please provide details about the position, required qualifications, responsibilities, etc."></textarea>
                    @error('details')
                        <div style="color: #DC2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem;">
                    <button type="button" wire:click="closeModal" style="
                        padding: 0.75rem 1.5rem;
                        background-color: #FFFFFF;
                        color: #4B4B4B;
                        border: 1px solid #EAEAEA;
                        border-radius: 0.375rem;
                        font-weight: 600;
                        font-size: 0.9375rem;
                        cursor: pointer;
                        transition: all 0.2s ease;
                    " onmouseover="this.style.backgroundColor='#F3F4F6'; this.style.borderColor='#D1D5DB';" 
                       onmouseout="this.style.backgroundColor='#FFFFFF'; this.style.borderColor='#EAEAEA';">
                        Cancel
                    </button>
                    <button type="submit" style="
                        padding: 0.75rem 2rem;
                        background-color: #F4C542;
                        color: #0A0A0A;
                        border: none;
                        border-radius: 0.375rem;
                        font-weight: 600;
                        font-size: 0.9375rem;
                        cursor: pointer;
                        transition: all 0.2s ease;
                    " onmouseover="this.style.backgroundColor='#D4A017';" 
                       onmouseout="this.style.backgroundColor='#F4C542';">
                        Submit Inquiry
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Success/Error Messages -->
    @if(session()->has('inquiry_success'))
        <div style="
            position: fixed;
            top: 2rem;
            right: 2rem;
            background-color: #10B981;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 10000;
            max-width: 400px;
        ">
            <p style="margin: 0; font-weight: 600;">{{ session('inquiry_success') }}</p>
        </div>
    @endif

    @if(session()->has('inquiry_error'))
        <div style="
            position: fixed;
            top: 2rem;
            right: 2rem;
            background-color: #DC2626;
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 10000;
            max-width: 400px;
        ">
            <p style="margin: 0; font-weight: 600;">{{ session('inquiry_error') }}</p>
        </div>
    @endif
</div>

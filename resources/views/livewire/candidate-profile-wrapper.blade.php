@livewire('candidate-profile-view', [
    'candidate' => $candidate,
    'cvLinks' => $cv_links ?? [],
    'otherDocumentsLinks' => $other_documents_links ?? []
]) 
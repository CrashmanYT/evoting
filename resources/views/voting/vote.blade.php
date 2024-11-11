<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('vote.store') }}">
        @csrf

        <!-- NIS -->
        <div>
            <x-input-label for="nis" :value="__('NIS')" />
            <x-text-input id="nis" class="block mt-1 w-full" type="text" name="nis" :value="old('email')" required autofocus autocomplete="username" />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($candidates as $candidate)
                <div class="p-4 border rounded-lg shadow-sm">
                    <label for="candidate_{{ $candidate->id }}" class="flex items-center space-x-2">
                        <input type="checkbox" id="candidate_{{ $candidate->id }}" name="candidate_ids[]" value="{{ $candidate->id }}"
                               class="form-checkbox" {{ old('candidate_ids') && in_array($candidate->id, old('candidate_ids', [])) ? 'checked' : '' }}>
                        <span class="flex items-center">
                            <img src="{{ asset('storage/' . $candidate->photo) }}" alt="{{ $candidate->name }}" class="h-12 w-12 rounded-full mr-2">
                            <span>{{ $candidate->number }}. {{ $candidate->name }}</span>
                        </span>
                    </label>
                </div>
            @endforeach
        </div>

        <x-primary-button class="ms-3">
            {{ __('Vote') }}
        </x-primary-button>
        </div>
    </form>
</x-guest-layout>

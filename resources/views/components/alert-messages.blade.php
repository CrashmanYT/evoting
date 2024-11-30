@if(session('success'))
    <x-bladewind::alert type="success">
        {{ session('success') }}
    </x-bladewind::alert>
@endif

@if(session('error'))
    <x-bladewind::alert type="error">
        {{ session('error') }}
    </x-bladewind::alert>
@endif

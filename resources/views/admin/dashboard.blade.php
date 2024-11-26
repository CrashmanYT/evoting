<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistik Umum -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <!-- Total Peserta -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500 dark:text-gray-400 text-sm">Total Peserta</div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $totalParticipants }}</div>
                    </div>
                </div>

                <!-- Sudah Voting -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500 dark:text-gray-400 text-sm">Sudah Voting</div>
                        <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $totalVoted }}</div>
                    </div>
                </div>

                <!-- Belum Voting -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500 dark:text-gray-400 text-sm">Belum Voting</div>
                        <div class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $totalNotVoted }}</div>
                    </div>
                </div>

                <!-- Tingkat Partisipasi -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-gray-500 dark:text-gray-400 text-sm">Tingkat Partisipasi</div>
                        <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $participationRate }}%</div>
                    </div>
                </div>
            </div>

            <!-- Hasil Voting -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-8">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        Hasil Voting
                    </h3>
                    <div class="overflow-x-auto">
                        <x-bladewind::table
                            divider="thin"
                            striped="true"
                            has_shadow="true"
                            compact="true">
                            <x-slot name="header">
                                <th class="text-left">Nama Kandidat</th>
                                <th class="text-left">Jumlah Suara</th>
                                <th class="text-left">Persentase</th>
                                <th class="text-left">Progress</th>
                            </x-slot>
                            @foreach($votingResults as $result)
                                <tr>
                                    <td>{{ $result['name'] }}</td>
                                    <td>{{ $result['votes_count'] }}</td>
                                    <td>{{ $result['percentage'] }}%</td>
                                    <td class="w-64">
                                        <x-bladewind::progress-bar 
                                            percentage="{{ $result['percentage'] }}"
                                            color="blue"
                                            show_percentage="false"
                                            size="thin"
                                        />
                                    </td>
                                </tr>
                            @endforeach
                        </x-bladewind::table>
                    </div>

                    <div class="mt-8">
                        <canvas id="votingChart" class="w-full"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

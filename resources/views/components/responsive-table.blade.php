@props(['data', 'columns', 'actions' => []])

<div class="overflow-x-auto">
    <x-bladewind::table 
        searchable="true" 
        paginated="true"
        per_page="10"
        :include_columns="$columns" 
        :data="$data"
        :action_icons="$actions" 
        has_border="true" 
        no_data_message="Tidak ada data ditemukan" 
        divider="thin"
        rows_per_page_text="Baris per halaman"
        go_to_page_text="Ke halaman"
        of_text="dari"
        showing_text="Menampilkan"
        entries_text="data"
    >
    </x-bladewind::table>
</div>

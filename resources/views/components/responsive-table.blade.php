@props(['data', 'columns', 'actions' => []])

<div class="overflow-x-auto">
    <x-bladewind::table 
        searchable="true" 
        :include_columns="$columns" 
        :data="$data"
        :action_icons="$actions" 
        has_border="true" 
        no_data_message="No data found" 
        divider="thin">
    </x-bladewind::table>
</div>

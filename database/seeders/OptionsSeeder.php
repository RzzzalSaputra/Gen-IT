<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Option;
use Illuminate\Support\Facades\DB;

class OptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Material Types in English
        $materialTypes = [
            ['type' => 'material_type', 'value' => 'Training'],
            ['type' => 'material_type', 'value' => 'Workshop'],
            ['type' => 'material_type', 'value' => 'Journal'],
            ['type' => 'material_type', 'value' => 'Documentation'],
            ['type' => 'material_type', 'value' => 'Tutorial'],
        ];
        
        // Layout Types
        $layoutTypes = [
            ['type' => 'layout', 'value' => 'Text Only'],
            ['type' => 'layout', 'value' => 'Text with Image'],
            ['type' => 'layout', 'value' => 'Text with File'],
            ['type' => 'layout', 'value' => 'File Only'],
        ];
        
        // Combine all options
        $allOptions = array_merge($materialTypes, $layoutTypes);
        
        // Create all options with timestamps
        foreach ($allOptions as $option) {
            Option::updateOrCreate(
                ['type' => $option['type'], 'value' => $option['value']],
                array_merge($option, ['created_at' => now(), 'updated_at' => now()])
            );
        }
        
        $this->command->info('All options have been seeded successfully!');
    }
}
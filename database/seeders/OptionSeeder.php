<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    public function run(): void
    {
        $options = [
            // Basic app options
            ['type' => 'contact_status', 'value' => 'pending'],
            ['type' => 'contact_status', 'value' => 'responded'],
            ['type' => 'user_role', 'value' => 'admin'],
            ['type' => 'user_role', 'value' => 'user'],
            
            // Post layout options
            ['type' => 'post_layout', 'value' => 'default'],
            ['type' => 'post_layout', 'value' => 'text+gambar'],
            
            ['type' => 'gallery_type', 'value' => 'image'],
            ['type' => 'gallery_type', 'value' => 'video'],
            
            // Layout types
            ['type' => 'layout', 'value' => 'Text Only'],
            ['type' => 'layout', 'value' => 'Text with Image'],
            ['type' => 'layout', 'value' => 'Video Content'],
            ['type' => 'layout', 'value' => 'File Only'],
            
            // Material types
            ['type' => 'material_type', 'value' => 'Training'],
            ['type' => 'material_type', 'value' => 'Workshop'],
            ['type' => 'material_type', 'value' => 'Journal'],
            ['type' => 'material_type', 'value' => 'Documentation'],
            ['type' => 'material_type', 'value' => 'Tutorial'],
            
            // School types
            ['type' => 'school_type', 'value' => 'SMA'],
            ['type' => 'school_type', 'value' => 'SMK'],
            ['type' => 'school_type', 'value' => 'University'],

            // Job types
            ['type' => 'job_type', 'value' => 'Full Time'],
            ['type' => 'job_type', 'value' => 'Part Time'],
            ['type' => 'job_type', 'value' => 'Internship'],
            ['type' => 'job_type', 'value' => 'Contract'],
            ['type' => 'job_type', 'value' => 'Freelance'],

            // Experience levels 
            ['type' => 'experience_level', 'value' => 'Junior'],
            ['type' => 'experience_level', 'value' => 'Mid-level'],
            ['type' => 'experience_level', 'value' => 'Senior'],
            ['type' => 'experience_level', 'value' => 'Lead'],
            ['type' => 'experience_level', 'value' => 'Entry Level'],
            
            // Work types
            ['type' => 'work_type', 'value' => 'Work from Office'],
            ['type' => 'work_type', 'value' => 'Work from Home'],
            ['type' => 'work_type', 'value' => 'Hybrid'],

            // Post types
            ['type' => 'post_layout', 'value' => 'Text Only'],
            ['type' => 'post_layout', 'value' => 'Text with Image'],
            ['type' => 'post_layout', 'value' => 'Text with Video'],
            ['type' => 'post_layout', 'value' => 'Text with File'],
            
            // Submission types
            ['type' => 'submission_type', 'value' => 'file'],
            ['type' => 'submission_type', 'value' => 'video'],
            ['type' => 'submission_type', 'value' => 'text'],
            ['type' => 'submission_status', 'value' => 'pending'],
            ['type' => 'submission_status', 'value' => 'accepted'],
            ['type' => 'submission_status', 'value' => 'declined'],

            // Study levels
            ['type' => 'study_level', 'value' => 'SMA'],
            ['type' => 'study_level', 'value' => 'SMK'],
            ['type' => 'study_level', 'value' => 'D3'],
            ['type' => 'study_level', 'value' => 'D4'],
            ['type' => 'study_level', 'value' => 'S1'],
            ['type' => 'study_level', 'value' => 'S2'],
            ['type' => 'study_level', 'value' => 'S3'],

            // Classroom types
            ['type' => 'classroom', 'value' => 'classroom'],
        ];

        foreach ($options as $option) {
            Option::updateOrCreate(
                ['type' => $option['type'], 'value' => $option['value']],
                array_merge($option, ['created_at' => now(), 'updated_at' => now()])
            );
        }
        
        $this->command->info('Options seeded successfully!');
    }
}

<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\Option;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get all users for created_by field
        $userIds = User::pluck('id')->toArray();
        if (empty($userIds)) {
            $this->command->error('No users found! Please run UserSeeder first.');
            return;
        }

        // Get layout options
        $layoutOptions = Option::where('type', 'layout')->pluck('id', 'value')->toArray();
        if (empty($layoutOptions)) {
            $this->command->error('No layout options found! Please run OptionsSeeder first.');
            return;
        }

        // Get material type options
        $materialTypes = Option::where('type', 'material_type')->pluck('id', 'value')->toArray();
        if (empty($materialTypes)) {
            $this->command->error('No material type options found! Please run OptionsSeeder first.');
            return;
        }

        // Sample content by material type
        $trainingContent = '<h2>Training Overview</h2>
        <p>This comprehensive training program is designed to provide participants with a thorough understanding of the subject matter. Through a combination of theoretical concepts and practical exercises, participants will develop the skills needed to excel in this area.</p>
        
        <h3>Learning Objectives</h3>
        <ul>
            <li>Understand fundamental principles and methodologies</li>
            <li>Develop practical skills through hands-on exercises</li>
            <li>Learn to apply concepts to real-world scenarios</li>
            <li>Build confidence in implementing learned techniques</li>
        </ul>
        
        <h3>Course Structure</h3>
        <ol>
            <li>Introduction to core concepts</li>
            <li>Theoretical foundations</li>
            <li>Practical application techniques</li>
            <li>Advanced implementations</li>
            <li>Review and assessment</li>
        </ol>
        
        <h3>Materials Provided</h3>
        <p>Participants will receive comprehensive course materials, including lecture slides, practice exercises, and reference guides. Additional resources will be provided for continued learning after the training completion.</p>';

        $workshopContent = '<h2>Workshop Details</h2>
        <p>This interactive workshop offers hands-on experience and practical knowledge. Participants will engage in collaborative activities designed to enhance understanding and skill development.</p>
        
        <h3>Workshop Agenda</h3>
        <ul>
            <li>Introduction and overview (30 minutes)</li>
            <li>Concept presentation (1 hour)</li>
            <li>Interactive demonstration (45 minutes)</li>
            <li>Hands-on practice session (2 hours)</li>
            <li>Group discussion and feedback (45 minutes)</li>
            <li>Next steps and resources (30 minutes)</li>
        </ul>
        
        <h3>What to Bring</h3>
        <p>Participants should bring their laptops with the required software installed. A list of prerequisites will be sent prior to the workshop date. Come prepared with questions and specific challenges you would like to address during the session.</p>
        
        <h3>Expected Outcomes</h3>
        <p>By the end of this workshop, participants will have gained practical experience and be able to apply the learned techniques in their own work environment. Certificates of participation will be provided.</p>';

        $journalContent = '<h2>Abstract</h2>
        <p>This journal article explores recent developments in the field and their implications for practitioners and researchers. Through careful analysis of existing literature and new findings, we present insights that contribute to the broader understanding of this topic.</p>
        
        <h2>Introduction</h2>
        <p>The field has seen significant evolution in recent years, with new methodologies and approaches emerging to address longstanding challenges. This paper examines these developments through both theoretical and practical lenses.</p>
        
        <h2>Methodology</h2>
        <p>Our research employed a mixed-methods approach, combining quantitative data analysis with qualitative assessment of case studies. This methodology allowed for a comprehensive examination of the subject matter from multiple perspectives.</p>
        
        <h2>Findings</h2>
        <p>Analysis revealed several key patterns and trends that have significant implications for both theory and practice. The data suggests that integrated approaches yield more sustainable outcomes compared to traditional methods.</p>
        
        <h2>Discussion</h2>
        <p>These findings contribute to the growing body of knowledge in this field and suggest new directions for future research and implementation. Practical applications of these insights could lead to improved outcomes in various contexts.</p>
        
        <h2>Conclusion</h2>
        <p>Our research underscores the importance of adaptive strategies and holistic frameworks when addressing complex challenges in this domain. Further research is recommended to explore specific applications in diverse settings.</p>';

        $documentationContent = '<h1>Technical Documentation</h1>
        
        <h2>System Overview</h2>
        <p>This documentation provides comprehensive information about the system architecture, components, and implementation details. It serves as a reference guide for developers, administrators, and end-users.</p>
        
        <h2>Installation Requirements</h2>
        <ul>
            <li><strong>Operating System:</strong> Windows 10/11, macOS 12+, or Linux (Ubuntu 20.04+)</li>
            <li><strong>Memory:</strong> Minimum 8GB RAM, 16GB recommended</li>
            <li><strong>Processor:</strong> Quad-core CPU, 2.5GHz or higher</li>
            <li><strong>Storage:</strong> 50GB available space</li>
            <li><strong>Additional Software:</strong> .NET Framework 4.8, Java Runtime Environment 11+</li>
        </ul>
        
        <h2>Installation Process</h2>
        <ol>
            <li>Download the installation package from the official repository</li>
            <li>Run the installer with administrator privileges</li>
            <li>Select installation directory and components</li>
            <li>Configure system parameters</li>
            <li>Complete setup and verification process</li>
        </ol>
        
        <h2>Configuration</h2>
        <p>System configuration can be customized through the provided configuration files. Key parameters include:</p>
        
        <pre><code>
# Server Configuration
server.port=8080
server.timeout=300

# Database Connection
db.host=localhost
db.port=5432
db.name=appdb
db.user=admin
        </code></pre>
        
        <h2>Troubleshooting</h2>
        <p>Common issues and their solutions are documented in the troubleshooting section. For additional support, please contact the technical support team.</p>';

        $tutorialContent = '<h1>Step-by-Step Tutorial</h1>
        
        <h2>Introduction</h2>
        <p>This tutorial provides a comprehensive guide to implementing the feature in your project. By following these steps, you will gain a practical understanding of the process and be able to adapt it to your specific needs.</p>
        
        <h2>Prerequisites</h2>
        <ul>
            <li>Basic understanding of the platform</li>
            <li>Development environment setup</li>
            <li>Access to required APIs and resources</li>
        </ul>
        
        <h2>Step 1: Initial Setup</h2>
        <p>Begin by preparing your environment and gathering necessary resources. This includes:</p>
        <ul>
            <li>Installing required dependencies</li>
            <li>Configuring access credentials</li>
            <li>Setting up project structure</li>
        </ul>
        
        <h2>Step 2: Core Implementation</h2>
        <p>Now we will implement the main functionality:</p>
        <pre><code>
// Sample implementation code
function initializeFeature() {
    const config = loadConfiguration();
    validateSettings(config);
    return new FeatureManager(config);
}
        </code></pre>
        
        <h2>Step 3: Testing</h2>
        <p>Verify your implementation using these test cases:</p>
        <ol>
            <li>Basic functionality test</li>
            <li>Edge case scenarios</li>
            <li>Performance benchmarks</li>
        </ol>
        
        <h2>Step 4: Deployment</h2>
        <p>Finally, deploy your solution following these best practices:</p>
        <ul>
            <li>Use CI/CD pipelines for reliable deployment</li>
            <li>Monitor performance after deployment</li>
            <li>Implement logging for troubleshooting</li>
        </ul>
        
        <h2>Conclusion</h2>
        <p>You have successfully implemented the feature. For further assistance or advanced configurations, refer to the detailed documentation.</p>';

        // Sample materials with different layouts and types
        $materials = [
            // Training materials
            [
                'title' => 'Introduction to Project Management',
                'content' => $trainingContent,
                'layout' => $layoutOptions['Text Only'],
                'type' => $materialTypes['Training'],
            ],
            [
                'title' => 'Advanced Data Analysis Techniques',
                'content' => $trainingContent,
                'layout' => $layoutOptions['Text with Image'],
                'type' => $materialTypes['Training'],
                'img' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => 'Leadership Skills Development',
                'content' => $trainingContent,
                'layout' => $layoutOptions['Text with File'],
                'type' => $materialTypes['Training'],
                'file' => '/storage/materials/files/sample_leadership_training.pdf',
            ],

            // Workshop materials
            [
                'title' => 'UI/UX Design Workshop',
                'content' => $workshopContent,
                'layout' => $layoutOptions['Text with Image'],
                'type' => $materialTypes['Workshop'],
                'img' => 'https://images.unsplash.com/photo-1559028012-481c04fa702d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => 'Agile Development Practices',
                'content' => $workshopContent,
                'layout' => $layoutOptions['Text Only'],
                'type' => $materialTypes['Workshop'],
            ],
            [
                'title' => 'Cloud Infrastructure Workshop',
                'content' => $workshopContent,
                'layout' => $layoutOptions['Text with File'],
                'type' => $materialTypes['Workshop'],
                'file' => '/storage/materials/files/sample_cloud_workshop.pdf',
            ],

            // Journal materials
            [
                'title' => 'Emerging Trends in Artificial Intelligence',
                'content' => $journalContent,
                'layout' => $layoutOptions['Text Only'],
                'type' => $materialTypes['Journal'],
            ],
            [
                'title' => 'Sustainable Development in Urban Areas',
                'content' => $journalContent,
                'layout' => $layoutOptions['Text with Image'],
                'type' => $materialTypes['Journal'],
                'img' => 'https://images.unsplash.com/photo-1480714378408-67cf0d13bc1b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => 'Economic Impact of Remote Work',
                'content' => $journalContent,
                'layout' => $layoutOptions['File Only'],
                'type' => $materialTypes['Journal'],
                'file' => '/storage/materials/files/sample_economic_journal.pdf',
            ],

            // Documentation materials
            [
                'title' => 'API Integration Guide',
                'content' => $documentationContent,
                'layout' => $layoutOptions['Text Only'],
                'type' => $materialTypes['Documentation'],
            ],
            [
                'title' => 'System Architecture Overview',
                'content' => $documentationContent,
                'layout' => $layoutOptions['Text with Image'],
                'type' => $materialTypes['Documentation'],
                'img' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => 'Security Best Practices',
                'content' => $documentationContent,
                'layout' => $layoutOptions['File Only'],
                'type' => $materialTypes['Documentation'],
                'file' => '/storage/materials/files/sample_security_docs.pdf',
            ],

            // Tutorial materials
            [
                'title' => 'Getting Started with React',
                'content' => $tutorialContent,
                'layout' => $layoutOptions['Text Only'],
                'type' => $materialTypes['Tutorial'],
            ],
            [
                'title' => 'Building RESTful APIs',
                'content' => $tutorialContent,
                'layout' => $layoutOptions['Text with Image'],
                'type' => $materialTypes['Tutorial'],
                'img' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => 'Database Optimization Techniques',
                'content' => $tutorialContent,
                'layout' => $layoutOptions['Text with File'],
                'type' => $materialTypes['Tutorial'],
                'file' => '/storage/materials/files/sample_database_tutorial.pdf',
            ],

            // Additional materials
            [
                'title' => 'Introduction to Machine Learning',
                'content' => $trainingContent,
                'layout' => $layoutOptions['Text with Image'],
                'type' => $materialTypes['Training'],
                'img' => 'https://images.unsplash.com/photo-1527474305487-b87b222841cc?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => 'Blockchain Technology Overview',
                'content' => $documentationContent,
                'layout' => $layoutOptions['Text with File'],
                'type' => $materialTypes['Documentation'],
                'file' => '/storage/materials/files/sample_blockchain_overview.pdf',
            ],
            [
                'title' => 'Product Management Workshop',
                'content' => $workshopContent,
                'layout' => $layoutOptions['Text Only'],
                'type' => $materialTypes['Workshop'],
            ],
            [
                'title' => 'Cybersecurity Fundamentals',
                'content' => $trainingContent,
                'layout' => $layoutOptions['Text with Image'],
                'type' => $materialTypes['Training'],
                'img' => 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => 'Docker Container Tutorial',
                'content' => $tutorialContent,
                'layout' => $layoutOptions['File Only'],
                'type' => $materialTypes['Tutorial'],
                'file' => '/storage/materials/files/sample_docker_tutorial.pdf',
            ],
        ];

        // Insert materials
        foreach ($materials as $index => $material) {
            // Generate slug from title
            $slug = Str::slug($material['title']);
            
            // Add counter to slug if needed to ensure uniqueness
            $finalSlug = $slug;
            $counter = 1;
            while (Material::where('slug', $finalSlug)->exists()) {
                $finalSlug = $slug . '-' . $counter++;
            }
            
            // Create the material
            Material::create([
                'title' => $material['title'],
                'slug' => $finalSlug,
                'content' => $material['content'],
                'layout' => $material['layout'],
                'type' => $material['type'],
                'file' => $material['file'] ?? null,
                'img' => $material['img'] ?? null,
                'created_by' => $userIds[array_rand($userIds)],
                'read_counter' => rand(0, 150),
                'download_counter' => isset($material['file']) ? rand(0, 50) : 0,
                'created_at' => now()->subDays(rand(1, 60))->subHours(rand(1, 24)),
                'updated_at' => now()->subDays(rand(0, 30))->subHours(rand(1, 24)),
            ]);
        }

        $this->command->info('Materials seeded successfully!');
    }
}
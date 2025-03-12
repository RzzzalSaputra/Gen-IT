<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Option;
use App\Models\Post;
use App\Models\Material;
use App\Models\Gallery;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ===== OPTIONS SEEDING =====
        $this->seedOptions();
        
        // ===== USERS SEEDING =====
        $this->seedUsers();
        
        // ===== POSTS SEEDING =====  
        $this->seedPosts();
        
        // ===== GALLERY SEEDING =====
        $this->seedGallery();
        
        // ===== MATERIALS SEEDING =====
        $this->seedMaterials();
        
        // ===== ARTICLES SEEDING =====
        $this->seedArticles();
    }
    
    /**
     * Seed the options table.
     */
    private function seedOptions(): void
    {
        $options = [
            // Basic app options
            ['type' => 'contact_status', 'value' => 'pending'],
            ['type' => 'contact_status', 'value' => 'responded'],
            ['type' => 'user_role', 'value' => 'admin'],
            ['type' => 'user_role', 'value' => 'user'],
            ['type' => 'post_layout', 'value' => 'default'],
            ['type' => 'post_layout', 'value' => 'teks+konten+teks'],
            ['type' => 'gallery_type', 'value' => 'image'],
            ['type' => 'gallery_type', 'value' => 'video'],
            
            // Layout types
            ['type' => 'layout', 'value' => 'Text Only'],
            ['type' => 'layout', 'value' => 'Text with Image'],
            ['type' => 'layout', 'value' => 'Video Content'], // Changed from 'Text with File'
            ['type' => 'layout', 'value' => 'File Only'],
            // Material types
            
            ['type' => 'material_type', 'value' => 'Training'],
            ['type' => 'material_type', 'value' => 'Workshop'],
            ['type' => 'material_type', 'value' => 'Journal'],
            ['type' => 'material_type', 'value' => 'Documentation'],
            ['type' => 'material_type', 'value' => 'Tutorial'],
            

        ];

        foreach ($options as $option) {
            Option::updateOrCreate(
                ['type' => $option['type'], 'value' => $option['value']],
                array_merge($option, ['created_at' => now(), 'updated_at' => now()])
            );
        }
        
        $this->command->info('Options seeded successfully!');
    }

    /**
     * Seed the users table.
     */
    private function seedUsers(): void
    {
        // Get user role from options
        $roleUser = Option::where('type', 'user_role')->where('value', 'user')->first();
        
        // Create users
        $users = [
            [
                'user_name' => 'IchikaNakano',
                'first_name' => 'Ichika',
                'last_name' => 'Nakano',
                'email' => 'ichika@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567890',
                'birthdate' => '2000-05-05',
                'role' => $roleUser->id,
            ],
            [
                'user_name' => 'NinoNakano',
                'first_name' => 'Nino',
                'last_name' => 'Nakano',
                'email' => 'nino@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567891',
                'birthdate' => '2000-05-05',
                'role' => $roleUser->id,
            ],
            [
                'user_name' => 'MikuNakano',
                'first_name' => 'Miku',
                'last_name' => 'Nakano',
                'email' => 'miku@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567892',
                'birthdate' => '2000-05-05',
                'role' => $roleUser->id,
            ],
            [
                'user_name' => 'YotsubaNakano',
                'first_name' => 'Yotsuba',
                'last_name' => 'Nakano',
                'email' => 'yotsuba@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567893',
                'birthdate' => '2000-05-05',
                'role' => $roleUser->id,
            ],
            [
                'user_name' => 'ItsukiNakano',
                'first_name' => 'Itsuki',
                'last_name' => 'Nakano',
                'email' => 'itsuki@example.com',
                'password' => Hash::make('Password123!'),
                'phone' => '081234567894',
                'birthdate' => '2000-05-05',
                'role' => 3,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(['email' => $userData['email']], $userData);
        }
        
        $this->command->info('Users seeded successfully!');
    }

    /**
     * Seed the posts table.
     */
    private function seedPosts(): void
    {
        // Get all users
        $users = User::all();
        if ($users->isEmpty()) {
            $this->command->error('No users found! Cannot seed posts.');
            return;
        }

        // Get layout from options
        $layout = Option::where('type', 'post_layout')->where('value', 'default')->first();
        if (!$layout) {
            $this->command->error('Default post layout option not found!');
            return;
        }

        // Create posts (using random users)
        $posts = [
            [
                'title' => 'Judul Post Pertama',
                'slug' => Str::slug('Judul Post Pertama'),
                'content' => 'Ini adalah konten dari post pertama.',
                'file' => null,
                'img' => 'https://via.placeholder.com/150',
                'layout' => $layout->id,
                'created_by' => $users->random()->id,
                'counter' => 0,
            ],
            [
                'title' => 'Judul Post Kedua',
                'slug' => Str::slug('Judul Post Kedua'),
                'content' => 'Ini adalah konten dari post kedua.',
                'file' => 'https://example.com/file.pdf',
                'img' => null,
                'layout' => $layout->id,
                'created_by' => $users->random()->id,
                'counter' => 5,
            ],
            [
                'title' => 'Judul Post Ketiga',
                'slug' => Str::slug('Judul Post Ketiga'),
                'content' => 'Ini adalah konten dari post ketiga.',
                'file' => null,
                'img' => null,
                'layout' => $layout->id,
                'created_by' => $users->random()->id,
                'counter' => 10,
            ],
        ];

        foreach ($posts as $post) {
            Post::create($post);
        }
        
        $this->command->info('Posts seeded successfully!');
    }

    /**
     * Seed the gallery table.
     */
    private function seedGallery(): void
    {
        // Get random user IDs for created_by
        $userIds = User::pluck('id')->toArray();
        if (empty($userIds)) {
            $this->command->error('No users found! Cannot seed gallery.');
            return;
        }

        // Sample galleries - Images (type 7)
        $imageGalleries = [
            [
                'type' => 7,
                'title' => 'Beautiful Sunset',
                'file' => 'https://images.unsplash.com/photo-1586348943529-beaae6c28db9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
                'link' => null,
                'created_by' => $userIds[array_rand($userIds)],
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now(),
            ],
            [
                'type' => 7,
                'title' => 'Mountain Landscape',
                'file' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
                'link' => null,
                'created_by' => $userIds[array_rand($userIds)],
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now(),
            ],
            [
                'type' => 7,
                'title' => 'Ocean Waves',
                'file' => 'https://images.unsplash.com/photo-1518837695005-2083093ee35b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80',
                'link' => null,
                'created_by' => $userIds[array_rand($userIds)],
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now(),
            ],
        ];

        // Sample galleries - Videos (type 8)
        $videoGalleries = [
            [
                'type' => 8,
                'title' => 'Amazing Nature Documentary',
                'file' => null,
                'link' => 'https://www.youtube.com/watch?v=W-CTzidBK7c',
                'created_by' => $userIds[array_rand($userIds)],
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now(),
            ],
            [
                'type' => 8,
                'title' => 'Travel Vlog: Tokyo',
                'file' => null,
                'link' => 'https://www.youtube.com/watch?v=cS30JWmxlLI',
                'created_by' => $userIds[array_rand($userIds)],
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now(),
            ],
            [
                'type' => 8,
                'title' => 'Cooking Tutorial',
                'file' => null,
                'link' => 'https://www.youtube.com/watch?v=mJ7oEALrP4s',
                'created_by' => $userIds[array_rand($userIds)],
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now(),
            ],
        ];

        // Merge and insert all galleries
        $galleries = array_merge($imageGalleries, $videoGalleries);
        
        foreach ($galleries as $gallery) {
            Gallery::create($gallery);
        }
        
        $this->command->info('Gallery seeded successfully!');
    }
    
    /**
     * Seed the materials table.
     */
    private function seedMaterials(): void
    {
        // Get all users for created_by field
        $userIds = User::pluck('id')->toArray();
        if (empty($userIds)) {
            $this->command->error('No users found! Cannot seed materials.');
            return;
        }

        // Get layout options
        $layoutOptions = Option::where('type', 'layout')->pluck('id', 'value')->toArray();
        if (empty($layoutOptions)) {
            $this->command->error('No layout options found!');
            return;
        }

        // Get material type options
        $materialTypes = Option::where('type', 'material_type')->pluck('id', 'value')->toArray();
        if (empty($materialTypes)) {
            $this->command->error('No material type options found!');
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
                'layout' => $layoutOptions['Video Content'],
                'type' => $materialTypes['Training'],
                'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Example YouTube link
                'file' => null, // No file for video content
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
                'layout' => $layoutOptions['Video Content'],
                'type' => $materialTypes['Workshop'],
                'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Example YouTube link
                'file' => null, // No file for video content
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
                'layout' => $layoutOptions['Video Content'],
                'type' => $materialTypes['Tutorial'],
                'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Example YouTube link
                'file' => null, // No file for video content
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
                'layout' => $layoutOptions['Video Content'],
                'type' => $materialTypes['Documentation'],
                'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Example YouTube link
                'file' => null, // No file for video content
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
                'link' => $material['link'] ?? null,
                'created_by' => $userIds[array_rand($userIds)],
                'read_counter' => rand(0, 150),
                'download_counter' => isset($material['file']) ? rand(0, 50) : 0,
                'created_at' => now()->subDays(rand(1, 60))->subHours(rand(1, 24)),
                'updated_at' => now()->subDays(rand(0, 30))->subHours(rand(1, 24)),
            ]);
        }

        $this->command->info('Materials seeded successfully!');
    }

    /**
     * Seed the articles table.
     */
    private function seedArticles(): void
    {
        // Check if Article model exists
        if (!class_exists('App\Models\Article')) {
            $this->command->error('Article model not found! Skipping article seeding.');
            return;
        }

        // Get users for created_by field
        $userIds = User::pluck('id')->toArray();
        if (empty($userIds)) {
            $this->command->error('No users found! Cannot seed articles.');
            return;
        }

        $articles = [
            [
                'title' => 'Getting Started with Laravel',
                'slug' => 'getting-started-with-laravel',
                'content' => '<p>Laravel is a web application framework with expressive, elegant syntax. Laravel takes the pain out of web development by easing common tasks used in many web projects.</p><h2>Installation</h2><p>Laravel utilizes Composer to manage its dependencies. So, before using Laravel, make sure you have Composer installed on your machine.</p><p>You can install Laravel by issuing the Composer create-project command in your terminal:</p><pre><code>composer create-project laravel/laravel example-app</code></pre><p>This will create a new Laravel project in a directory named example-app.</p>',
                'summary' => 'A beginner\'s guide to setting up and using the Laravel framework for web development.',
                'writer' => 'John Doe',
                'post_time' => now()->subDays(5),
            ],
            [
                'title' => 'Understanding MVC Architecture',
                'slug' => 'understanding-mvc-architecture',
                'content' => '<p>The Model-View-Controller (MVC) architectural pattern separates an application into three main logical components: the model, the view, and the controller.</p><h2>Components</h2><h3>Model</h3><p>The Model component corresponds to all the data-related logic that the user works with. This can represent either the data that is being transferred between the View and Controller components or any other business logic-related data.</p><h3>View</h3><p>The View component is used for all the UI logic of the application. It generates output based on the data provided by the model.</p><h3>Controller</h3><p>Controllers act as an interface between Model and View components to process all the business logic and incoming requests, manipulate data using the Model component and interact with the Views to render the final output.</p>',
                'summary' => 'Explore the fundamentals of Model-View-Controller architecture and its implementation in modern web frameworks.',
                'writer' => 'Jane Smith',
                'post_time' => now()->subDays(3),
            ],
            [
                'title' => 'Best Practices for API Development',
                'slug' => 'best-practices-for-api-development',
                'content' => '<p>Building a robust API requires careful planning and adherence to best practices. Here are some key considerations when developing APIs.</p><h2>Use HTTP Methods Correctly</h2><p>RESTful APIs should use HTTP methods explicitly as follows:</p><ul><li>GET: To retrieve a resource</li><li>POST: To create a resource</li><li>PUT/PATCH: To update a resource</li><li>DELETE: To delete a resource</li></ul><h2>Use Proper Status Codes</h2><p>Return appropriate HTTP status codes with each response. Some common ones include:</p><ul><li>200 OK: The request was successful</li><li>201 Created: A new resource was successfully created</li><li>400 Bad Request: The request couldn\'t be understood</li><li>401 Unauthorized: Authentication failed or user doesn\'t have permissions</li><li>404 Not Found: The resource doesn\'t exist</li><li>500 Internal Server Error: A generic server error occurred</li></ul>',
                'summary' => 'Learn essential guidelines and practices for building secure, efficient, and maintainable APIs.',
                'writer' => 'Alex Johnson',
                'post_time' => now()->subDays(1),
            ],
            [
                'title' => 'Introduction to Tailwind CSS',
                'slug' => 'introduction-to-tailwind-css',
                'content' => '<p>Tailwind CSS is a utility-first CSS framework that allows you to build custom designs without leaving your HTML. Unlike other CSS frameworks that provide predefined components, Tailwind offers low-level utility classes that let you build completely custom designs.</p><h2>Getting Started</h2><p>To get started with Tailwind CSS, you can include it in your project using npm:</p><pre><code>npm install tailwindcss</code></pre><p>Then, create a configuration file:</p><pre><code>npx tailwindcss init</code></pre><h2>Using Utility Classes</h2><p>Tailwind provides utility classes for almost everything you might need:</p><pre><code>&lt;div class="p-6 max-w-sm mx-auto bg-white rounded-xl shadow-md flex items-center space-x-4"&gt;\n  &lt;div&gt;\n    &lt;div class="text-xl font-medium text-black"&gt;Tailwind CSS&lt;/div&gt;\n    &lt;p class="text-gray-500"&gt;Utility-first CSS framework&lt;/p&gt;\n  &lt;/div&gt;\n&lt;/div&gt;</code></pre>',
                'summary' => 'Discover how Tailwind CSS can streamline your frontend development workflow with its utility-first approach.',
                'writer' => 'Emily Chen',
                'post_time' => now()->subDays(7),
            ],
            [
                'title' => 'Data Structures and Algorithms: A Primer',
                'slug' => 'data-structures-and-algorithms-primer',
                'content' => '<p>Understanding data structures and algorithms is fundamental to computer science and software engineering. This article provides an overview of key concepts and their practical applications.</p><h2>Common Data Structures</h2><ul><li><strong>Arrays</strong>: Continuous memory allocation, fast access by index, O(1)</li><li><strong>Linked Lists</strong>: Non-continuous memory, dynamic size, O(n) access</li><li><strong>Stacks</strong>: LIFO (Last In First Out) principle</li><li><strong>Queues</strong>: FIFO (First In First Out) principle</li><li><strong>Trees</strong>: Hierarchical structure, efficient for searching and sorting</li><li><strong>Graphs</strong>: Collection of nodes and edges, useful for complex relationships</li></ul><h2>Essential Algorithms</h2><h3>Sorting Algorithms</h3><p>Comparison between common sorting techniques:</p><table><tr><th>Algorithm</th><th>Time Complexity (Avg)</th><th>Space Complexity</th></tr><tr><td>Bubble Sort</td><td>O(n²)</td><td>O(1)</td></tr><tr><td>Quick Sort</td><td>O(n log n)</td><td>O(log n)</td></tr><tr><td>Merge Sort</td><td>O(n log n)</td><td>O(n)</td></tr></table><h3>Search Algorithms</h3><p>Binary search can find elements in sorted arrays with O(log n) complexity, compared to linear search with O(n).</p>',
                'summary' => 'An introduction to fundamental data structures and algorithms that every programmer should know.',
                'writer' => 'Michael Wong',
                'post_time' => now()->subDays(14),
            ],
        ];

        foreach ($articles as $articleData) {
            \App\Models\Article::create([
                'title' => $articleData['title'],
                'slug' => $articleData['slug'],
                'content' => $articleData['content'],
                'summary' => $articleData['summary'],
                'writer' => $articleData['writer'],
                'post_time' => $articleData['post_time'],
                'created_by' => $userIds[array_rand($userIds)],
            ]);
        }

        $this->command->info('Articles seeded successfully!');
    }
}

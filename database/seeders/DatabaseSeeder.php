<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Option;
use App\Models\Post;
use App\Models\Material;
use App\Models\Gallery;
use App\Models\School;
use App\Models\Study;
use App\Models\Company;
use App\Models\Job;
use App\Models\Submission;
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
        
        // ===== SCHOOLS SEEDING =====
        $this->seedSchools();
        
        // ===== STUDIES SEEDING =====
        $this->seedStudies();
        
        // ===== COMPANIES SEEDING =====
        $this->seedCompanies();

        // ===== JOBS SEEDING =====
        $this->seedJobs();

        // ===== VICON SEEDING =====
        $this->seedVicons();

        // ===== SUBMISSIONS SEEDING =====
        $this->seedSubmissions();

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

            // post types
            ['type' => 'post_layout', 'value' => 'Text Only'],
            ['type' => 'post_layout', 'value' => 'Text with Image'],
            ['type' => 'post_layout', 'value' => 'Text with Video'],
            ['type' => 'post_layout', 'value' => 'Text with File'],
            
            // submission types
            ['type' => 'submission_type', 'value' => 'file'],
            ['type' => 'submission_type', 'value' => 'video'],
            ['type' => 'submission_type', 'value' => 'text'],
            ['type' => 'submission_status', 'value' => 'pending'],
            ['type' => 'submission_status', 'value' => 'accepted'],
            ['type' => 'submission_status', 'value' => 'declined'],

            // classroom types
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

    /**
     * Seed the users table.
     */
    private function seedUsers(): void
    {        
        
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
                'role' => 'teacher',
            ],
            [
                'user_name' => 'NinoNakano',
                'first_name' => 'Nino',
                'last_name' => 'Nakano',
                'email' => 'nino@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567891',
                'birthdate' => '2000-05-05',
                'role' => 'user',
            ],
            [
                'user_name' => 'MikuNakano',
                'first_name' => 'Miku',
                'last_name' => 'Nakano',
                'email' => 'miku@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567892',
                'birthdate' => '2000-05-05',
                'role' => 'user',
            ],
            [
                'user_name' => 'YotsubaNakano',
                'first_name' => 'Yotsuba',
                'last_name' => 'Nakano',
                'email' => 'yotsuba@example.com',
                'password' => Hash::make('password123'),
                'phone' => '081234567893',
                'birthdate' => '2000-05-05',
                'role' => 'user',
            ],
            [
                'user_name' => 'ItsukiNakano',
                'first_name' => 'Itsuki',
                'last_name' => 'Nakano',
                'email' => 'itsuki@example.com',
                'password' => Hash::make('manis123'),
                'phone' => '081234567894',
                'birthdate' => '2000-05-05',
                'role' => 'admin',
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

        // Get layout options
        $textOnlyLayout = Option::where('type', 'post_layout')->where('value', 'Text Only')->first();
        $textWithImageLayout = Option::where('type', 'post_layout')->where('value', 'Text with Image')->first();
        $textWithVideoLayout = Option::where('type', 'post_layout')->where('value', 'Text with Video')->first();
        $textWithFileLayout = Option::where('type', 'post_layout')->where('value', 'Text with File')->first();
        
        if (!$textOnlyLayout || !$textWithImageLayout || !$textWithVideoLayout || !$textWithFileLayout) {
            $this->command->error('Post layout options not found!');
            return;
        }

        // Create posts (using random users)
        $posts = [
            // Text Only post example
            [
                'title' => 'Company Updates - March 2025',
                'slug' => Str::slug('Company Updates - March 2025'),
                'content' => '<h2>Quarterly Performance</h2><p>We are pleased to announce that our company has exceeded quarterly targets by 15%. This success is attributed to the dedicated work of all departments and strategic initiatives implemented earlier this year.</p><h2>New Office</h2><p>Starting next month, we will begin relocating to our new headquarters. The transition will be gradual, with full migration expected by the end of the quarter. The new location offers improved facilities and better accessibility for all employees.</p><h2>Upcoming Events</h2><p>Mark your calendars for the annual company retreat scheduled for June. This year\'s theme is "Innovation and Collaboration." Registration will open next week, with early bird discounts available.</p>',
                'file' => null,
                'img' => null,
                'layout' => $textOnlyLayout->id,
                'created_by' => $users->random()->id,
                'counter' => rand(10, 100),
            ],
            
            // Text with Image post example
            [
                'title' => 'Project Showcase: Modern Office Design',
                'slug' => Str::slug('Project Showcase: Modern Office Design'),
                'content' => '<p>Our design team recently completed the renovation of a tech startup\'s headquarters. The project focused on creating a flexible workspace that promotes collaboration while providing quiet areas for focused work.</p><h3>Key Features</h3><ul><li>Open concept main area with modular furniture</li><li>Sound-insulated meeting pods</li><li>Biophilic design elements including living walls</li><li>Energy-efficient lighting system</li><li>Ergonomic workstations</li></ul><p>Client feedback has been overwhelmingly positive, with particular appreciation for the balance between collaborative and private spaces.</p>',
                'file' => null,
                'img' => 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80',
                'layout' => $textWithImageLayout->id,
                'created_by' => $users->random()->id,
                'counter' => rand(50, 200),
            ],
            
            // Text with Video post example
            [
                'title' => 'Introduction to Our New Product Line',
                'slug' => Str::slug('Introduction to Our New Product Line'),
                'content' => '<p>We\'re excited to introduce our latest product line designed to revolutionize how you work. After months of research and development, we\'ve created solutions that address the most common challenges faced by professionals today.</p><h3>Features & Benefits</h3><p>Our new products offer unparalleled flexibility, superior performance, and intuitive interfaces. Watch the video below for a comprehensive overview of what\'s new and how these innovations can enhance your productivity.</p><p>For more information or to schedule a demo, please contact our sales team.</p>',
                'file' => null,
                'img' => null,
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'layout' => $textWithVideoLayout->id,
                'created_by' => $users->random()->id,
                'counter' => rand(75, 300),
            ],
            
            // Text with File post example
            [
                'title' => 'Annual Report 2024: Financial Performance',
                'slug' => Str::slug('Annual Report 2024: Financial Performance'),
                'content' => '<p>We are pleased to share our Annual Financial Report for the fiscal year 2024. This report outlines our financial performance, strategic achievements, and future outlook.</p><h3>Highlights</h3><ul><li>Revenue growth of 18% year-over-year</li><li>Expansion into three new international markets</li><li>Successful launch of two major product lines</li><li>Reduction in operational costs by 12%</li></ul><p>The detailed report is available for download below. For questions regarding this report, please contact our Investor Relations department.</p>',
                'file' => '/storage/files/annual_report_2024.pdf',
                'img' => null,
                'layout' => $textWithFileLayout->id,
                'created_by' => $users->random()->id,
                'counter' => rand(30, 150),
            ],
            
            // Additional Text Only post
            [
                'title' => 'Employee Recognition Program',
                'slug' => Str::slug('Employee Recognition Program'),
                'content' => '<h2>Introducing Our New Recognition Program</h2><p>We\'re proud to announce the launch of our enhanced employee recognition program designed to celebrate the contributions and achievements of our team members.</p><h3>Program Components</h3><ul><li><strong>Monthly Spotlight:</strong> Recognizing outstanding performance in different departments</li><li><strong>Peer Nominations:</strong> Encouraging team members to recognize colleagues\' exceptional work</li><li><strong>Milestone Awards:</strong> Celebrating work anniversaries and career achievements</li><li><strong>Innovation Awards:</strong> Recognizing creative solutions and process improvements</li></ul><p>The program officially begins next month. All employees will receive detailed information about participation and nomination processes via email.</p>',
                'file' => null,
                'img' => null,
                'layout' => $textOnlyLayout->id,
                'created_by' => $users->random()->id,
                'counter' => rand(40, 90),
            ],
            
            // Additional Text with Image post
            [
                'title' => 'Community Service Day: Building Homes Together',
                'slug' => Str::slug('Community Service Day: Building Homes Together'),
                'content' => '<p>Last weekend, our team participated in the annual Community Service Day, partnering with Habitat for Humanity to help build affordable housing in our local community.</p><p>More than 50 employees volunteered their time and skills, working alongside future homeowners to construct two houses. The experience provided not only tangible results for families in need but also strengthened our team bonds outside the workplace.</p><h3>Impact</h3><p>Through our collective efforts, we contributed approximately 400 volunteer hours, helping to accelerate the construction timeline by several weeks. This initiative is part of our broader commitment to social responsibility and community engagement.</p><p>We extend our thanks to everyone who participated and to our community partners for making this event possible.</p>',
                'file' => null,
                'img' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80',
                'layout' => $textWithImageLayout->id,
                'created_by' => $users->random()->id,
                'counter' => rand(60, 180),
            ],
            
            // Additional Text with Video post
            [
                'title' => 'Expert Interview: Future of Remote Work',
                'slug' => Str::slug('Expert Interview: Future of Remote Work'),
                'content' => '<p>We recently sat down with workplace strategy expert Dr. Amanda Chen to discuss the evolving landscape of remote and hybrid work models. Dr. Chen shared insights on how companies can create effective policies that balance flexibility with collaboration needs.</p><h3>Key Topics Covered</h3><ul><li>Sustainable remote work infrastructure</li><li>Measuring productivity in distributed teams</li><li>Maintaining company culture across virtual environments</li><li>Addressing burnout and work-life boundaries</li></ul><p>Watch the full interview below for practical strategies and research-backed recommendations on navigating the future of work.</p>',
                'file' => null,
                'img' => null,
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'layout' => $textWithVideoLayout->id,
                'created_by' => $users->random()->id,
                'counter' => rand(85, 250),
            ],
            
            // Additional Text with File post
            [
                'title' => 'Updated Employee Handbook 2025',
                'slug' => Str::slug('Updated Employee Handbook 2025'),
                'content' => '<p>We have updated our Employee Handbook for 2025 to reflect recent policy changes and provide clearer guidance on company procedures.</p><h3>Major Updates Include:</h3><ul><li>Revised remote work policy with expanded eligibility</li><li>Enhanced parental leave benefits</li><li>Updated health and safety protocols</li><li>New professional development opportunities</li><li>Clarified expense reimbursement procedures</li></ul><p>All employees should review the handbook and complete the acknowledgment form by April 15. The HR team will host information sessions throughout the month to address questions and provide additional context on the changes.</p><p>Please download the handbook below:</p>',
                'file' => '/storage/files/employee_handbook_2025.pdf',
                'img' => null,
                'layout' => $textWithFileLayout->id,
                'created_by' => $users->random()->id,
                'counter' => rand(70, 140),
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

    /**
     * Seed the schools table.
     */
    private function seedSchools(): void
    {
        // Get school type options
        $schoolTypes = Option::where('type', 'school_type')->pluck('id', 'value')->toArray();
        if (empty($schoolTypes)) {
            $this->command->error('No school type options found! Cannot seed schools.');
            return;
        }


        // Schools data
        $schools = [
            // SMA Data
            [
                'name' => 'SMA Negeri 1 Bandung',
                'description' => 'SMA Negeri 1 Bandung adalah salah satu sekolah menengah atas negeri unggulan di Kota Bandung. Didirikan pada tahun 1950, sekolah ini telah mencetak ribuan lulusan berkualitas yang tersebar di berbagai bidang. Dengan fasilitas lengkap dan guru-guru berpengalaman, SMA Negeri 1 Bandung berkomitmen untuk memberikan pendidikan terbaik bagi para siswa.',
                'img' => 'https://picsum.photos/id/1001/800/600',
                'type' => $schoolTypes['SMA'],
                'gmap' => 'https://maps.google.com/maps?q=-6.9034443,107.6431857&z=15&output=embed',
                'province' => 'Jawa Barat',
                'city' => 'Bandung',
                'address' => 'Jl. Ir. H. Juanda No.93, Bandung',
                'website' => 'https://sman1bandung.sch.id',
                'instagram' => 'https://instagram.com/sman1bdg',
                'facebook' => 'https://facebook.com/sman1bandung',
                'x' => 'https://x.com/sman1bdg',
            ],
            [
                'name' => 'SMA Negeri 3 Jakarta',
                'description' => 'SMA Negeri 3 Jakarta merupakan sekolah dengan sejarah panjang yang berlokasi di jantung ibukota. Dikenal dengan prestasi akademik dan non-akademik yang gemilang, SMAN 3 Jakarta menawarkan lingkungan belajar yang kondusif dengan berbagai fasilitas modern. Lulusan SMA Negeri 3 Jakarta dikenal memiliki daya saing tinggi dan tersebar di perguruan tinggi ternama baik dalam maupun luar negeri.',
                'img' => 'https://picsum.photos/id/1002/800/600',
                'type' => $schoolTypes['SMA'],
                'gmap' => 'https://maps.google.com/maps?q=-6.2088898,106.8355483&z=15&output=embed',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Selatan',
                'address' => 'Jl. Setiabudi II No.1, Jakarta Selatan',
                'website' => 'https://sman3jkt.sch.id',
                'instagram' => 'https://instagram.com/sman3jkt',
                'facebook' => 'https://facebook.com/sman3jakarta',
                'x' => 'https://x.com/sman3jkt',
            ],
            [
                'name' => 'SMA Negeri 1 Yogyakarta',
                'description' => 'SMA Negeri 1 Yogyakarta atau lebih dikenal sebagai SMA Teladan adalah sekolah unggulan dengan reputasi akademik yang sangat baik. Berlokasi di kawasan Malioboro, sekolah ini memadukan nilai-nilai budaya dan pendidikan modern. Dengan kurikulum yang komprehensif dan berbagai kegiatan ekstrakurikuler, SMA Negeri 1 Yogyakarta fokus pada pengembangan karakter dan prestasi siswa secara seimbang.',
                'img' => 'https://picsum.photos/id/1003/800/600',
                'type' => $schoolTypes['SMA'],
                'gmap' => 'https://maps.google.com/maps?q=-7.7956328,110.3695414&z=15&output=embed',
                'province' => 'DI Yogyakarta',
                'city' => 'Yogyakarta',
                'address' => 'Jl. HOS Cokroaminoto No.10, Yogyakarta',
                'website' => 'https://sman1yogya.sch.id',
                'instagram' => 'https://instagram.com/smateladanjogja',
                'facebook' => 'https://facebook.com/sman1yogyakarta',
                'x' => 'https://x.com/smateladan',
            ],
            
            // SMK Data
            [
                'name' => 'SMK Negeri 1 Denpasar',
                'description' => 'SMK Negeri 1 Denpasar adalah sekolah kejuruan unggulan dengan fokus pada bidang teknologi dan bisnis. Dengan laboratorium dan workshop yang lengkap, sekolah ini memberikan pendidikan vokasi berkualitas tinggi. Kerjasama dengan berbagai industri memastikan lulusan SMK Negeri 1 Denpasar siap memasuki dunia kerja dengan keterampilan yang relevan dan berdaya saing.',
                'img' => 'https://picsum.photos/id/1004/800/600',
                'type' => $schoolTypes['SMK'],
                'gmap' => 'https://maps.google.com/maps?q=-8.6478175,115.2167033&z=15&output=embed',
                'province' => 'Bali',
                'city' => 'Denpasar',
                'address' => 'Jl. Cokroaminoto No.84, Denpasar',
                'website' => 'https://smkn1denpasar.sch.id',
                'instagram' => 'https://instagram.com/smkn1denpasar',
                'facebook' => 'https://facebook.com/smkn1denpasar',
                'x' => 'https://x.com/smkn1denpasar',
            ],
            [
                'name' => 'SMK Telkom Malang',
                'description' => 'SMK Telkom Malang adalah sekolah kejuruan yang berfokus pada bidang teknologi informasi dan komunikasi. Didukung oleh Telkom Indonesia, sekolah ini dilengkapi dengan fasilitas modern dan kurikulum yang dirancang bersama industri. Dengan metode pembelajaran berbasis proyek dan sertifikasi internasional, SMK Telkom Malang telah menjadi salah satu SMK terbaik di Indonesia dalam melahirkan tenaga IT profesional.',
                'img' => 'https://picsum.photos/id/1005/800/600',
                'type' => $schoolTypes['SMK'],
                'gmap' => 'https://maps.google.com/maps?q=-7.9666204,112.6173992&z=15&output=embed',
                'province' => 'Jawa Timur',
                'city' => 'Malang',
                'address' => 'Jl. Danau Ranau, Sawojajar, Malang',
                'website' => 'https://smktelkom-mlg.sch.id',
                'instagram' => 'https://instagram.com/smktelkommalang',
                'facebook' => 'https://facebook.com/smktelkommalang',
                'x' => 'https://x.com/smktelkommlg',
            ],
            [
                'name' => 'SMK Negeri 4 Padang',
                'description' => 'SMK Negeri 4 Padang adalah sekolah kejuruan dengan keunggulan di bidang perhotelan, tata boga, dan pariwisata. Terletak di kota Padang dengan akses mudah ke berbagai destinasi wisata, sekolah ini memberikan kesempatan praktik langsung bagi siswanya. Dengan guru-guru berkompeten dan fasilitas pendukung yang lengkap, SMK Negeri 4 Padang berkomitmen menghasilkan lulusan yang siap kerja di industri hospitalitas.',
                'img' => 'https://picsum.photos/id/1006/800/600',
                'type' => $schoolTypes['SMK'],
                'gmap' => 'https://maps.google.com/maps?q=-0.9115136,100.3580286&z=15&output=embed',
                'province' => 'Sumatera Barat',
                'city' => 'Padang',
                'address' => 'Jl. Pulai Anak Air, Pauh, Padang',
                'website' => 'https://smkn4padang.sch.id',
                'instagram' => 'https://instagram.com/smkn4padang',
                'facebook' => 'https://facebook.com/smkn4padang',
                'x' => 'https://x.com/smkn4pdg',
            ],
            
            // University Data
            [
                'name' => 'Universitas Indonesia',
                'description' => 'Universitas Indonesia (UI) adalah salah satu perguruan tinggi terkemuka di Indonesia yang berlokasi di Depok, Jawa Barat. Didirikan pada tahun 1849, UI telah menjadi pusat keunggulan akademik dengan berbagai program studi dari jenjang sarjana hingga doktoral. Kampus yang asri dan fasilitas modern mendukung kegiatan pembelajaran dan penelitian yang inovatif. Dengan reputasi internasional yang kuat, UI terus berkomitmen untuk berkontribusi dalam pembangunan bangsa melalui pendidikan tinggi yang berkualitas.',
                'img' => 'https://picsum.photos/id/1008/800/600',
                'type' => $schoolTypes['University'],
                'gmap' => 'https://maps.google.com/maps?q=-6.3660259,106.8271192&z=15&output=embed',
                'province' => 'Jawa Barat',
                'city' => 'Depok',
                'address' => 'Kampus UI Depok, Jawa Barat',
                'website' => 'https://ui.ac.id',
                'instagram' => 'https://instagram.com/universitasindonesia',
                'facebook' => 'https://facebook.com/universitasindonesia',
                'x' => 'https://x.com/univ_indonesia',
            ],
            [
                'name' => 'Institut Teknologi Bandung',
                'description' => 'Institut Teknologi Bandung (ITB) adalah perguruan tinggi teknik terkemuka di Indonesia yang didirikan pada tahun 1920. Dengan fokus pada bidang sains, teknologi, dan seni, ITB telah mencetak banyak ilmuwan, insinyur, dan seniman yang berkontribusi pada pembangunan nasional dan internasional. Kampus utama yang bersejarah di Jalan Ganesha dilengkapi dengan laboratorium canggih dan pusat penelitian yang mendukung inovasi dan pengembangan teknologi.',
                'img' => 'https://picsum.photos/id/1009/800/600',
                'type' => $schoolTypes['University'],
                'gmap' => 'https://maps.google.com/maps?q=-6.8913528,107.610667&z=15&output=embed',
                'province' => 'Jawa Barat',
                'city' => 'Bandung',
                'address' => 'Jl. Ganesha No.10, Bandung',
                'website' => 'https://itb.ac.id',
                'instagram' => 'https://instagram.com/itbofficial',
                'facebook' => 'https://facebook.com/institutteknologibandung',
                'x' => 'https://x.com/itbofficial',
            ],
            [
                'name' => 'Universitas Gadjah Mada',
                'description' => 'Universitas Gadjah Mada (UGM) adalah universitas riset tertua dan terkemuka di Indonesia yang berlokasi di Yogyakarta. Didirikan pada tahun 1949, UGM menawarkan pendidikan berkualitas tinggi dalam berbagai disiplin ilmu dengan memadukan nilai-nilai lokal dan standar akademik global. Dengan kampus yang luas dan asri, UGM menyediakan lingkungan belajar yang ideal bagi mahasiswa dari seluruh Indonesia dan mancanegara. UGM berkomitmen untuk terus mengembangkan penelitian dan pengabdian masyarakat yang bermanfaat bagi kemajuan bangsa.',
                'img' => 'https://picsum.photos/id/1010/800/600',
                'type' => $schoolTypes['University'],
                'gmap' => 'https://maps.google.com/maps?q=-7.7713847,110.3774998&z=15&output=embed',
                'province' => 'DI Yogyakarta',
                'city' => 'Yogyakarta',
                'address' => 'Bulaksumur, Yogyakarta',
                'website' => 'https://ugm.ac.id',
                'instagram' => 'https://instagram.com/ugm_yogyakarta',
                'facebook' => 'https://facebook.com/universitasgadjahmada',
                'x' => 'https://x.com/ugm_yogyakarta',
            ],
        ];

        // Insert schools
        foreach ($schools as $schoolData) {
            $school = School::updateOrCreate(
                ['name' => $schoolData['name']],
                array_merge($schoolData, [
                    'read_counter' => rand(100, 5000),
                    'created_at' => now()->subDays(rand(30, 365)),
                    'updated_at' => now()->subDays(rand(1, 30)),
                ])
            );
        }
        
        $this->command->info('Schools seeded successfully!');
    }

    /**
     * Seed the studies table.
     */
    private function seedStudies(): void
    {
        // First, add study level options if they don't exist
        $studyLevels = [
            ['type' => 'study_level', 'value' => 'SMA'],
            ['type' => 'study_level', 'value' => 'SMK'],
            ['type' => 'study_level', 'value' => 'D3'],
            ['type' => 'study_level', 'value' => 'D4'],
            ['type' => 'study_level', 'value' => 'S1'],
            ['type' => 'study_level', 'value' => 'S2'],
            ['type' => 'study_level', 'value' => 'S3'],
        ];

        foreach ($studyLevels as $level) {
            Option::updateOrCreate(
                ['type' => $level['type'], 'value' => $level['value']],
                array_merge($level, ['created_at' => now(), 'updated_at' => now()])
            );
        }
        
        // Get study level IDs
        $smaLevelId = Option::where('type', 'study_level')->where('value', 'SMA')->first()->id;
        $smkLevelId = Option::where('type', 'study_level')->where('value', 'SMK')->first()->id;
        $d3LevelId = Option::where('type', 'study_level')->where('value', 'D3')->first()->id;
        $s1LevelId = Option::where('type', 'study_level')->where('value', 'S1')->first()->id;
        $s2LevelId = Option::where('type', 'study_level')->where('value', 'S2')->first()->id;
        $s3LevelId = Option::where('type', 'study_level')->where('value', 'S3')->first()->id;
        
        // Get school types
        $schoolTypeIds = Option::where('type', 'school_type')->pluck('id', 'value')->toArray();
        
        // Get all schools
        $schools = School::all();
        
        // Study descriptions
        $smaDescription = "Program pembelajaran yang komprehensif untuk mempersiapkan siswa menghadapi berbagai tantangan akademik. Dengan kurikulum yang dirancang khusus, program ini memberikan landasan pengetahuan yang kuat dalam berbagai mata pelajaran. Siswa akan memperoleh kemampuan analisis, pemecahan masalah, dan keterampilan komunikasi yang esensial untuk melanjutkan ke perguruan tinggi dan berkarir di masa depan.";
        
        $smkDescription = "Program pendidikan kejuruan yang dirancang untuk mempersiapkan siswa dengan keterampilan praktis dan pengetahuan teoretis untuk memasuki dunia kerja. Kurikulum di program ini menekankan pada pembelajaran berbasis proyek dan praktikum di laboratorium atau bengkel yang dilengkapi peralatan industri standar. Siswa juga akan mendapatkan kesempatan magang di industri untuk mendapatkan pengalaman kerja nyata sebelum lulus.";
        
        $d3Description = "Program diploma tiga tahun yang menekankan pada penguasaan keterampilan praktis dan siap kerja. Kurikulum dirancang dengan masukan dari industri untuk memastikan relevansi dengan kebutuhan pasar kerja terkini. Mahasiswa akan menjalani praktikum intensif dan proyek akhir yang berfokus pada penerapan praktis dari bidang studi. Lulusan program ini memiliki keunggulan dalam keterampilan teknis yang langsung dapat diterapkan di dunia kerja.";
        
        $s1Description = "Program sarjana empat tahun yang memberikan landasan teoretis yang kuat dan keterampilan praktis dalam bidang studi. Kurikulum komprehensif mencakup mata kuliah inti, pilihan, dan pendukung untuk mengembangkan pemahaman mendalam tentang disiplin ilmu. Mahasiswa akan melakukan penelitian untuk tugas akhir yang mengintegrasikan pengetahuan dan keterampilan yang diperoleh selama studi. Program ini mempersiapkan lulusan untuk karir profesional atau melanjutkan ke jenjang pendidikan yang lebih tinggi.";
        
        $s2Description = "Program magister yang dirancang untuk memperdalam pengetahuan dan keahlian dalam bidang spesifik. Mahasiswa akan mengembangkan pemikiran kritis dan kemampuan penelitian melalui studi lanjutan dan seminar khusus. Program ini melibatkan riset mandiri yang menghasilkan tesis dengan kontribusi orisinal pada bidang studi. Lulusan akan memiliki keahlian tingkat lanjut dan perspektif yang lebih luas untuk posisi kepemimpinan dalam karir akademis maupun profesional.";
        
        $s3Description = "Program doktoral yang berfokus pada penelitian orisinal dan pengembangan pengetahuan baru dalam bidang spesifik. Mahasiswa akan bekerja di bawah bimbingan pakar terkemuka untuk mengembangkan dan melakukan penelitian yang signifikan. Program ini menghasilkan disertasi yang memberikan kontribusi substansial pada ilmu pengetahuan. Lulusan program doktoral akan menjadi ahli dalam bidangnya, siap untuk karir di penelitian tingkat lanjut, pendidikan tinggi, atau posisi kepemimpinan dalam industri.";

        // Create study programs for each school
        foreach ($schools as $school) {
            // Determine which programs to create based on school type
            switch ($school->type) {
                case $schoolTypeIds['SMA']:
                    $studyPrograms = [
                        [
                            'name' => 'Program Reguler',
                            'description' => $smaDescription,
                            'duration' => '3 tahun',
                            'level' => $smaLevelId,
                            'img' => 'https://picsum.photos/id/' . rand(100, 999) . '/800/600',
                        ],
                        [
                            'name' => 'Jurusan Ilmu Pengetahuan Alam',
                            'description' => $smaDescription . ' Jurusan IPA menekankan pada mata pelajaran Matematika, Fisika, Kimia, dan Biologi untuk mempersiapkan siswa melanjutkan ke jurusan teknik, kedokteran, farmasi, atau ilmu alam lainnya di perguruan tinggi.',
                            'duration' => '3 tahun',
                            'level' => $smaLevelId,
                            'img' => 'https://picsum.photos/id/' . rand(100, 999) . '/800/600',
                        ],
                        [
                            'name' => 'Jurusan Ilmu Pengetahuan Sosial',
                            'description' => $smaDescription . ' Jurusan IPS fokus pada mata pelajaran Ekonomi, Geografi, Sejarah, dan Sosiologi untuk mempersiapkan siswa melanjutkan ke jurusan ekonomi, hukum, psikologi, atau ilmu sosial lainnya di perguruan tinggi.',
                            'duration' => '3 tahun',
                            'level' => $smaLevelId,
                            'img' => 'https://picsum.photos/id/' . rand(100, 999) . '/800/600',
                        ],
                    ];
                    break;
                    
                case $schoolTypeIds['SMK']:
                    $studyPrograms = [
                        [
                            'name' => 'Teknik Komputer dan Jaringan',
                            'description' => $smkDescription . ' Program TKJ berfokus pada perakitan komputer, administrasi jaringan, keamanan sistem, dan pemecahan masalah IT dengan sertifikasi standar industri.',
                            'duration' => '3 tahun',
                            'level' => $smkLevelId,
                            'img' => 'https://picsum.photos/id/' . rand(100, 999) . '/800/600',
                        ],
                        [
                            'name' => 'Rekayasa Perangkat Lunak',
                            'description' => $smkDescription . ' Program RPL mempelajari pengembangan aplikasi, basis data, pemrograman web, dan mobile dengan pendekatan agile dan devops.',
                            'duration' => '3 tahun',
                            'level' => $smkLevelId,
                            'img' => 'https://picsum.photos/id/' . rand(100, 999) . '/800/600',
                        ],
                        [
                            'name' => 'Tata Boga',
                            'description' => $smkDescription . ' Program Tata Boga fokus pada teknik memasak, pastry, food styling, manajemen restoran, dan inovasi kuliner dengan praktik di dapur profesional.',
                            'duration' => '3 tahun',
                            'level' => $smkLevelId,
                            'img' => 'https://picsum.photos/id/' . rand(100, 999) . '/800/600',
                        ],
                    ];
                    break;
                    
                case $schoolTypeIds['University']:
                    // For universities, create a mix of programs
                    $studyPrograms = [
                        // Diploma programs
                        [
                            'name' => 'D3 Akuntansi',
                            'description' => $d3Description . ' Program ini membekali mahasiswa dengan keterampilan praktis dalam pembukuan, perpajakan, dan analisis laporan keuangan.',
                            'duration' => '3 tahun (6 semester)',
                            'level' => $d3LevelId,
                            'img' => 'https://picsum.photos/id/' . rand(100, 999) . '/800/600',
                        ],
                        [
                            'name' => 'D3 Teknologi Informasi',
                            'description' => $d3Description . ' Program ini fokus pada pengembangan aplikasi, jaringan komputer, dan manajemen sistem informasi.',
                            'duration' => '3 tahun (6 semester)',
                            'level' => $d3LevelId,
                            'img' => 'https://picsum.photos/id/' . rand(100, 999) . '/800/600',
                        ],
                        
                        // Bachelor programs
                        [
                            'name' => 'S1 Teknik Informatika',
                            'description' => $s1Description . ' Program ini mencakup algoritma, struktur data, pengembangan perangkat lunak, kecerdasan buatan, dan teknologi komputasi canggih.',
                            'duration' => '4 tahun (8 semester)',
                            'level' => $s1LevelId,
                            'img' => 'https://picsum.photos/id/' . rand(100, 999) . '/800/600',
                            'link' => 'https://example.com/informatika',
                        ],
                        [
                            'name' => 'S1 Manajemen',
                            'description' => $s1Description . ' Program ini mengajarkan prinsip bisnis, pemasaran, manajemen sumber daya manusia, keuangan, dan strategi organisasi.',
                            'duration' => '4 tahun (8 semester)',
                            'level' => $s1LevelId,
                            'img' => 'https://picsum.photos/id/' . rand(100, 999) . '/800/600',
                        ],
                        [
                            'name' => 'S1 Kedokteran',
                            'description' => $s1Description . ' Program ini meliputi ilmu dasar kedokteran, sistem organ, penyakit, diagnostik, dan terapi dengan praktik klinis di rumah sakit pendidikan.',
                            'duration' => '5.5 tahun (11 semester)',
                            'level' => $s1LevelId,
                            'img' => 'https://picsum.photos/id/' . rand(100, 999) . '/800/600',
                            'link' => 'https://example.com/kedokteran',
                        ],
                        
                        // Master programs
                        [
                            'name' => 'S2 Ilmu Komputer',
                            'description' => $s2Description . ' Program ini menawarkan spesialisasi dalam komputasi berperforma tinggi, data science, atau keamanan siber dengan riset kolaboratif bersama industri.',
                            'duration' => '2 tahun (4 semester)',
                            'level' => $s2LevelId,
                            'img' => 'https://picsum.photos/id/' . rand(100, 999) . '/800/600',
                        ],
                        [
                            'name' => 'S2 Manajemen Bisnis',
                            'description' => $s2Description . ' Program MBA ini mengembangkan keterampilan kepemimpinan, analisis bisnis, dan pengambilan keputusan strategis untuk posisi eksekutif.',
                            'duration' => '2 tahun (4 semester)',
                            'level' => $s2LevelId,
                            'img' => 'https://picsum.photos/id/' . rand(100, 999) . '/800/600',
                        ],
                        
                        // Doctoral program
                        [
                            'name' => 'S3 Ilmu Komputer',
                            'description' => $s3Description . ' Program doktoral ini mendukung penelitian canggih dalam bidang machine learning, computer vision, komputasi kuantum, dan teknologi disruptif lainnya.',
                            'duration' => '3-5 tahun (6-10 semester)',
                            'level' => $s3LevelId,
                            'img' => 'https://picsum.photos/id/' . rand(100, 999) . '/800/600',
                        ],
                    ];
                    break;
                    
                default:
                    $studyPrograms = [];
            }
            
            // Create each study program for the current school
            foreach ($studyPrograms as $program) {
                Study::create([
                    'school_id' => $school->id,
                    'name' => $program['name'],
                    'description' => $program['description'],
                    'duration' => $program['duration'],
                    'link' => $program['link'] ?? null,
                    'img' => $program['img'] ?? null,
                    'level' => $program['level'],
                    'read_counter' => rand(10, 500),
                    'created_at' => now()->subDays(rand(1, 60)),
                    'updated_at' => now()->subDays(rand(0, 30)),
                ]);
            }
        }
        
        $this->command->info('Studies seeded successfully!');
    }

    /**
     * Seed the companies table.
     */
    private function seedCompanies(): void
    {
        $this->command->info('Seeding companies...');

        $companies = [
            [
                'name' => 'TechNova Solutions',
                'description' => 'TechNova Solutions is a leading software development company specializing in custom enterprise applications, mobile development, and cloud solutions. With a team of experienced developers and designers, we deliver innovative digital products that help businesses transform their operations and reach their full potential.',
                'img' => '/storage/companies/technova.jpg',
                'gmap' => 'https://maps.google.com/maps?q=-6.2251319,106.8271151&z=15&output=embed',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Selatan',
                'address' => 'Jl. Gatot Subroto Kav. 38, Menara Jamsostek Lt. 12, Kuningan, Jakarta Selatan 12710',
                'website' => 'https://www.technovasolutions.id',
                'instagram' => 'technova_id',
                'facebook' => 'TechNovaSolutionsID',
                'x' => 'technova_id',
                'read_counter' => rand(100, 5000),
            ],
            [
                'name' => 'Green Earth Industries',
                'description' => 'Green Earth Industries is an eco-friendly manufacturing company dedicated to sustainable production practices. We create biodegradable packaging solutions, renewable energy systems, and eco-conscious consumer products that help reduce environmental impact while maintaining high quality and performance standards. Our mission is to prove that business success and environmental responsibility can go hand in hand.',
                'img' => '/storage/companies/greenearth.jpg',
                'gmap' => 'https://maps.google.com/maps?q=-6.9269273,107.6038687&z=15&output=embed',
                'province' => 'Jawa Barat',
                'city' => 'Bandung',
                'address' => 'Jl. Soekarno Hatta No. 269, Bandung 40235',
                'website' => 'https://www.greenearthindustries.co.id',
                'instagram' => 'greenearthid',
                'facebook' => 'GreenEarthIndustriesID',
                'x' => 'GreenEarthID',
                'read_counter' => rand(100, 5000),
            ],
            [
                'name' => 'Horizon Financial Group',
                'description' => 'Horizon Financial Group is a comprehensive financial services company providing investment management, retirement planning, insurance solutions, and wealth advisory services. Our team of certified financial planners and investment specialists work closely with clients to develop personalized strategies that align with their goals and values. With a focus on long-term growth and risk management, we help individuals and businesses navigate complex financial landscapes.',
                'img' => '/storage/companies/horizon.jpg',
                'gmap' => 'https://maps.google.com/maps?q=-6.2563119,106.7805831&z=15&output=embed',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Selatan',
                'address' => 'Office 8 Building, Lt. 18, SCBD Lot 28, Jl. Jend. Sudirman Kav. 52-53, Jakarta Selatan 12190',
                'website' => 'https://www.horizonfinancial.co.id',
                'instagram' => 'horizonfinancial_id',
                'facebook' => 'HorizonFinancialID',
                'x' => 'HorizonFin_ID',
                'read_counter' => rand(100, 5000),
            ],
            [
                'name' => 'MediPlus Healthcare',
                'description' => 'MediPlus Healthcare is an integrated healthcare provider offering comprehensive medical services from preventive care to specialized treatments. Our network includes modern hospitals, outpatient clinics, diagnostic centers, and telemedicine services staffed by experienced healthcare professionals. We combine advanced medical technology with compassionate care to deliver positive health outcomes for our patients, with a particular emphasis on accessibility and patient education.',
                'img' => '/storage/companies/mediplus.jpg',
                'gmap' => 'https://maps.google.com/maps?q=-7.2764879,112.7475366&z=15&output=embed',
                'province' => 'Jawa Timur',
                'city' => 'Surabaya',
                'address' => 'Jl. Dr. Soetomo No. 122, Tegalsari, Surabaya 60264',
                'website' => 'https://www.mediplushealthcare.co.id',
                'instagram' => 'mediplus_id',
                'facebook' => 'MediPlusHealthcareID',
                'x' => 'MediPlusID',
                'read_counter' => rand(100, 5000),
            ],
            [
                'name' => 'Culinary Creations',
                'description' => 'Culinary Creations is a food innovation company specializing in product development, restaurant consulting, and culinary education. Our team of chefs, food scientists, and nutritionists collaborate to create unique food products, develop restaurant concepts, and design specialized menus for various dietary needs. We combine traditional cooking techniques with modern food science to deliver exceptional taste experiences that meet contemporary consumer demands.',
                'img' => '/storage/companies/culinary.jpg',
                'gmap' => 'https://maps.google.com/maps?q=-8.6571026,115.1763829&z=15&output=embed',
                'province' => 'Bali',
                'city' => 'Denpasar',
                'address' => 'Jl. Sunset Road No. 88X, Kuta, Badung, Bali 80361',
                'website' => 'https://www.culinarycreations.id',
                'instagram' => 'culinary_id',
                'facebook' => 'culinarycreationsid',
                'x' => 'culinary_id',
                'read_counter' => rand(100, 5000),
            ],
            [
                'name' => 'Nexus Education Centers',
                'description' => 'Nexus Education Centers is a modern educational institution offering innovative learning programs for students of all ages. Our curriculum integrates traditional academic subjects with practical skills development, digital literacy, and character building. With campuses equipped with advanced learning technologies and staffed by qualified educators, we prepare students for success in a rapidly evolving global environment while fostering creativity, critical thinking, and collaboration.',
                'img' => '/storage/companies/nexus.jpg',
                'gmap' => 'https://maps.google.com/maps?q=-6.1760148,106.8184689&z=15&output=embed',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Pusat',
                'address' => 'Jl. Menteng Raya No. 45, Menteng, Jakarta Pusat 10340',
                'website' => 'https://www.nexusedu.id',
                'instagram' => 'nexusedu_id',
                'facebook' => 'NexusEducationID',
                'x' => 'NexusEdu_ID',
                'read_counter' => rand(100, 5000),
            ],
            [
                'name' => 'ArkaTech Systems',
                'description' => 'ArkaTech Systems is a cybersecurity company offering comprehensive digital protection solutions for businesses and organizations. Our services include network security, threat detection and response, data encryption, compliance consulting, and security training. With a team of certified security experts and proprietary technologies, we safeguard critical digital assets against evolving cyber threats while ensuring business continuity and regulatory compliance.',
                'img' => '/storage/companies/arkatech.jpg',
                'gmap' => 'https://maps.google.com/maps?q=3.5796501,98.6776421&z=15&output=embed',
                'province' => 'Sumatera Utara',
                'city' => 'Medan',
                'address' => 'Jl. Imam Bonjol No. 17, Medan 20112',
                'website' => 'https://www.arkatech.co.id',
                'instagram' => 'arkatech_id',
                'facebook' => 'ArkaTechID',
                'x' => 'ArkaTech_ID',
                'read_counter' => rand(100, 5000),
            ],
            [
                'name' => 'Urban Oasis Properties',
                'description' => 'Urban Oasis Properties is a real estate development company focused on creating sustainable urban living spaces. Our portfolio includes eco-friendly residential complexes, mixed-use developments, and commercial properties designed with green building principles. We integrate energy-efficient systems, renewable materials, and community-focused amenities in our projects to deliver comfortable, healthy living environments that minimize environmental impact while enhancing quality of life.',
                'img' => '/storage/companies/urbanoasis.jpg',
                'gmap' => 'https://maps.google.com/maps?q=-5.1445028,119.4088722&z=15&output=embed',
                'province' => 'Sulawesi Selatan',
                'city' => 'Makassar',
                'address' => 'Jl. Penghibur No. 55, Makassar 90111',
                'website' => 'https://www.urbanoasis.id',
                'instagram' => 'urbanoasis_id',
                'facebook' => 'UrbanOasisID',
                'x' => 'UrbanOasis_ID',
                'read_counter' => rand(100, 5000),
            ],
            [
                'name' => 'Stellar Logistics',
                'description' => 'Stellar Logistics is an integrated supply chain management company providing comprehensive logistics solutions across Indonesia and Southeast Asia. Our services include freight forwarding, warehousing, distribution, customs clearance, and last-mile delivery. With advanced tracking technology and strategically located facilities, we optimize supply chains to improve efficiency, reduce costs, and enhance reliability for businesses of all sizes, from startups to multinational corporations.',
                'img' => '/storage/companies/stellar.jpg',
                'gmap' => 'https://maps.google.com/maps?q=-7.7597406,110.4151153&z=15&output=embed',
                'province' => 'DI Yogyakarta',
                'city' => 'Yogyakarta',
                'address' => 'Jl. Magelang Km. 7, Sleman, Yogyakarta 55284',
                'website' => 'https://www.stellarlogistics.co.id',
                'instagram' => 'stellar_log',
                'facebook' => 'StellarLogisticsID',
                'x' => 'Stellar_Log',
                'read_counter' => rand(100, 5000),
            ],
            [
                'name' => 'Harmony Wellness Center',
                'description' => 'Harmony Wellness Center is a holistic health facility combining traditional healing practices with modern wellness approaches. Our services include therapeutic massage, acupuncture, yoga classes, nutritional counseling, and mindfulness training. With a team of certified practitioners and a serene environment, we help clients achieve optimal physical, mental, and emotional wellbeing through personalized wellness programs that address the root causes of health issues rather than just symptoms.',
                'img' => '/storage/companies/harmony.jpg',
                'gmap' => 'https://maps.google.com/maps?q=-6.2185775,106.8030675&z=15&output=embed',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Selatan',
                'address' => 'Jl. Senopati No. 75, Kebayoran Baru, Jakarta Selatan 12190',
                'website' => 'https://www.harmonywellness.id',
                'instagram' => 'harmony_wellness',
                'facebook' => 'HarmonyWellnessID',
                'x' => 'Harmony_WellnessID',
                'read_counter' => rand(100, 5000),
            ]
        ];

        foreach ($companies as $companyData) {
            // Create the company record
            Company::updateOrCreate(
                ['name' => $companyData['name']],
                array_merge($companyData, [
                    'created_at' => now()->subDays(rand(1, 365)),
                    'updated_at' => now()->subDays(rand(0, 30)),
                ])
            );
        }

        $this->command->info('Companies seeded successfully!');
    }
    
    /**
     * Seed the jobs table.
     */
    private function seedJobs(): void
    {
        $this->command->info('Seeding jobs...');
        
        // Get job types
        $fullTimeId = Option::where('type', 'job_type')->where('value', 'Full Time')->first()->id;
        $partTimeId = Option::where('type', 'job_type')->where('value', 'Part Time')->first()->id;
        $internshipId = Option::where('type', 'job_type')->where('value', 'Internship')->first()->id;
        $contractId = Option::where('type', 'job_type')->where('value', 'Contract')->first()->id;
        $freelanceId = Option::where('type', 'job_type')->where('value', 'Freelance')->first()->id;
        
        // Get experience levels
        $juniorId = Option::where('type', 'experience_level')->where('value', 'Junior')->first()->id;
        $midLevelId = Option::where('type', 'experience_level')->where('value', 'Mid-level')->first()->id;
        $seniorId = Option::where('type', 'experience_level')->where('value', 'Senior')->first()->id;
        $leadId = Option::where('type', 'experience_level')->where('value', 'Lead')->first()->id;
        $entryLevelId = Option::where('type', 'experience_level')->where('value', 'Entry Level')->first()->id;
        
        // Get work types
        $wfoId = Option::where('type', 'work_type')->where('value', 'Work from Office')->first()->id;
        $wfhId = Option::where('type', 'work_type')->where('value', 'Work from Home')->first()->id;
        $hybridId = Option::where('type', 'work_type')->where('value', 'Hybrid')->first()->id;
        
        // Get companies
        $companies = Company::all();
        
        // Define job data for each company
        $jobsData = [
            // TechNova Solutions
            [
                'company_name' => 'TechNova Solutions',
                'jobs' => [
                    [
                        'title' => 'Senior Backend Developer',
                        'description' => 'We are seeking an experienced Backend Developer to join our growing team. The ideal candidate will design, develop, and maintain server-side applications using modern technologies and best practices.',
                        'requirment' => "- 5+ years of experience in backend development\n- Strong proficiency in PHP, Laravel, and Node.js\n- Experience with RESTful APIs and microservices architecture\n- Knowledge of database design and optimization (MySQL, PostgreSQL)\n- Familiarity with cloud services (AWS, GCP, or Azure)\n- Excellent problem-solving skills and attention to detail",
                        'salary_range' => 25000000,
                        'register_link' => 'https://technovasolutions.id/careers/senior-backend-developer',
                        'type' => $fullTimeId,
                        'experience' => $seniorId,
                        'work_type' => $hybridId,
                    ],
                    [
                        'title' => 'UI/UX Designer',
                        'description' => 'Join our creative team as a UI/UX Designer to create intuitive and engaging user experiences for web and mobile applications. You will collaborate with developers and product managers to deliver visually appealing and user-friendly interfaces.',
                        'requirment' => "- 3+ years of experience in UI/UX design\n- Proficiency in design tools (Figma, Adobe XD, Sketch)\n- Portfolio demonstrating strong visual design skills\n- Understanding of user-centered design principles\n- Experience conducting user research and usability testing\n- Knowledge of HTML/CSS is a plus",
                        'salary_range' => 18000000,
                        'register_link' => 'https://technovasolutions.id/careers/ui-ux-designer',
                        'type' => $fullTimeId,
                        'experience' => $midLevelId,
                        'work_type' => $hybridId,
                    ],
                    [
                        'title' => 'DevOps Engineer Intern',
                        'description' => 'We are looking for a DevOps Engineer Intern to assist our infrastructure team in implementing CI/CD pipelines, automating deployment processes, and maintaining cloud infrastructure. This is an excellent opportunity to gain hands-on experience in modern DevOps practices.',
                        'requirment' => "- Currently pursuing or recently graduated with a degree in Computer Science or related field\n- Basic understanding of Linux systems and command line\n- Familiarity with version control systems (Git)\n- Interest in cloud technologies (AWS, GCP, or Azure)\n- Eagerness to learn and problem-solve\n- Good communication skills",
                        'salary_range' => 5000000,
                        'register_link' => 'https://technovasolutions.id/careers/devops-intern',
                        'type' => $internshipId,
                        'experience' => $entryLevelId,
                        'work_type' => $wfoId,
                    ],
                ],
            ],
            
            // Green Earth Industries
            [
                'company_name' => 'Green Earth Industries',
                'jobs' => [
                    [
                        'title' => 'Environmental Engineer',
                        'description' => 'Green Earth Industries is seeking an Environmental Engineer to help design and implement sustainable manufacturing processes. You will evaluate environmental impacts, develop solutions to minimize waste, and ensure compliance with environmental regulations.',
                        'requirment' => "- Bachelor's or Master's degree in Environmental Engineering or related field\n- 3+ years of experience in environmental engineering or sustainable manufacturing\n- Knowledge of waste management and pollution prevention techniques\n- Familiarity with environmental regulations and compliance requirements\n- Experience with life cycle assessment and sustainability metrics\n- Strong analytical and problem-solving skills",
                        'salary_range' => 22000000,
                        'register_link' => 'https://greenearthindustries.co.id/careers/environmental-engineer',
                        'type' => $fullTimeId,
                        'experience' => $midLevelId,
                        'work_type' => $wfoId,
                    ],
                    [
                        'title' => 'Sustainable Packaging Designer',
                        'description' => 'Join our product team as a Sustainable Packaging Designer to create innovative, eco-friendly packaging solutions. You will develop designs that minimize environmental impact while maintaining functionality and aesthetic appeal.',
                        'requirment' => "- Degree in Industrial Design, Package Design, or related field\n- 2+ years of experience in packaging design\n- Knowledge of sustainable materials and manufacturing processes\n- Proficiency in design software (Adobe Creative Suite, SolidWorks)\n- Understanding of packaging production requirements and constraints\n- Portfolio demonstrating creative and sustainable design solutions",
                        'salary_range' => 15000000,
                        'register_link' => 'https://greenearthindustries.co.id/careers/packaging-designer',
                        'type' => $fullTimeId,
                        'experience' => $juniorId,
                        'work_type' => $hybridId,
                    ],
                ],
            ],
            
            // Horizon Financial Group
            [
                'company_name' => 'Horizon Financial Group',
                'jobs' => [
                    [
                        'title' => 'Financial Analyst',
                        'description' => 'Horizon Financial Group is seeking a Financial Analyst to join our investment team. You will analyze market trends, evaluate investment opportunities, and prepare financial models to support strategic decision-making.',
                        'requirment' => "- Bachelor's degree in Finance, Economics, or related field (MBA preferred)\n- 2-4 years of experience in financial analysis or investment management\n- Strong analytical skills and proficiency in financial modeling\n- Knowledge of financial markets and investment principles\n- Experience with financial analysis software and tools\n- Excellent attention to detail and problem-solving abilities",
                        'salary_range' => 20000000,
                        'register_link' => 'https://horizonfinancial.co.id/careers/financial-analyst',
                        'type' => $fullTimeId,
                        'experience' => $midLevelId,
                        'work_type' => $wfoId,
                    ],
                    [
                        'title' => 'Part-Time Financial Advisor',
                        'description' => 'We are looking for a Part-Time Financial Advisor to provide personalized financial guidance to our clients. You will develop financial plans, recommend investment strategies, and help clients achieve their financial goals.',
                        'requirment' => "- Bachelor's degree in Finance, Economics, or related field\n- Financial advisory certifications (CFP, CFA, or equivalent)\n- 3+ years of experience in financial advising or wealth management\n- Strong interpersonal and communication skills\n- Knowledge of investment products, tax planning, and retirement strategies\n- Ability to build and maintain client relationships",
                        'salary_range' => 12000000,
                        'register_link' => 'https://horizonfinancial.co.id/careers/part-time-advisor',
                        'type' => $partTimeId,
                        'experience' => $midLevelId,
                        'work_type' => $hybridId,
                    ],
                ],
            ],
            
            // MediPlus Healthcare
            [
                'company_name' => 'MediPlus Healthcare',
                'jobs' => [
                    [
                        'title' => 'Healthcare Data Analyst',
                        'description' => 'MediPlus Healthcare is seeking a Healthcare Data Analyst to analyze clinical and operational data to improve patient care and operational efficiency. You will develop reports, identify trends, and provide data-driven insights to support decision-making.',
                        'requirment' => "- Bachelor's degree in Health Informatics, Statistics, or related field\n- 2+ years of experience in healthcare analytics\n- Proficiency in data analysis tools and programming languages (SQL, Python, R)\n- Knowledge of healthcare terminologies and systems (ICD, CPT, EMR)\n- Experience with data visualization tools (Tableau, Power BI)\n- Strong analytical thinking and problem-solving skills",
                        'salary_range' => 18000000,
                        'register_link' => 'https://mediplushealthcare.co.id/careers/data-analyst',
                        'type' => $fullTimeId,
                        'experience' => $midLevelId,
                        'work_type' => $wfhId,
                    ],
                    [
                        'title' => 'Telemedicine Coordinator',
                        'description' => 'Join our innovative telemedicine team as a Telemedicine Coordinator. You will manage the day-to-day operations of our virtual care platform, coordinate virtual appointments, and ensure smooth communication between patients and healthcare providers.',
                        'requirment' => "- Background in healthcare administration or related field\n- Experience with telemedicine or virtual care platforms\n- Strong organizational and coordination skills\n- Excellent communication and customer service abilities\n- Proficiency with healthcare scheduling systems\n- Knowledge of healthcare privacy regulations (HIPAA)",
                        'salary_range' => 15000000,
                        'register_link' => 'https://mediplushealthcare.co.id/careers/telemedicine-coordinator',
                        'type' => $fullTimeId,
                        'experience' => $juniorId,
                        'work_type' => $hybridId,
                    ],
                    [
                        'title' => 'Medical Content Writer (Freelance)',
                        'description' => 'We are seeking a skilled Medical Content Writer to create accurate, engaging, and informative healthcare content for our website, patient education materials, and social media platforms.',
                        'requirment' => "- Degree in Medical Sciences, Health Communication, or related field\n- Experience writing healthcare content for diverse audiences\n- Strong understanding of medical terminology and concepts\n- Excellent writing, editing, and proofreading skills\n- Ability to translate complex medical information into accessible content\n- Portfolio of published healthcare content",
                        'salary_range' => 8000000,
                        'register_link' => 'https://mediplushealthcare.co.id/careers/medical-writer',
                        'type' => $freelanceId,
                        'experience' => $midLevelId,
                        'work_type' => $wfhId,
                    ],
                ],
            ],
            
            // Culinary Creations
            [
                'company_name' => 'Culinary Creations',
                'jobs' => [
                    [
                        'title' => 'Food Product Developer',
                        'description' => 'Culinary Creations is looking for a creative Food Product Developer to join our R&D team. You will create innovative food products, develop recipes, and collaborate with culinary experts to bring new concepts to market.',
                        'requirment' => "- Degree in Food Science, Culinary Arts, or related field\n- 3+ years of experience in food product development\n- Knowledge of food ingredients, processing techniques, and trends\n- Understanding of food safety regulations and quality standards\n- Creative thinking and problem-solving abilities\n- Experience with sensory evaluation and consumer testing",
                        'salary_range' => 20000000,
                        'register_link' => 'https://culinarycreations.id/careers/product-developer',
                        'type' => $fullTimeId,
                        'experience' => $midLevelId,
                        'work_type' => $wfoId,
                    ],
                    [
                        'title' => 'Culinary Photography Intern',
                        'description' => 'We are seeking a passionate Culinary Photography Intern to assist in creating visually appealing images of our food products and dishes for marketing materials, social media, and our website.',
                        'requirment' => "- Currently pursuing a degree in Photography, Visual Arts, or related field\n- Basic understanding of food styling and photography techniques\n- Proficiency with DSLR cameras and photography equipment\n- Knowledge of Adobe Photoshop and Lightroom\n- Creative eye for composition and detail\n- Portfolio showcasing photography skills",
                        'salary_range' => 4500000,
                        'register_link' => 'https://culinarycreations.id/careers/photography-intern',
                        'type' => $internshipId,
                        'experience' => $entryLevelId,
                        'work_type' => $hybridId,
                    ],
                    [
                        'title' => 'Restaurant Consultant',
                        'description' => 'Join our consulting team as a Restaurant Consultant to provide expert advice to restaurants and food service businesses. You will help clients optimize their menu offerings, improve operational efficiency, and enhance customer experience.',
                        'requirment' => "- 5+ years of experience in restaurant management or culinary operations\n- Strong understanding of food service business models\n- Knowledge of menu development, pricing strategies, and food costing\n- Experience in kitchen workflow optimization and staff training\n- Excellent communication and presentation skills\n- Ability to analyze business data and provide actionable recommendations",
                        'salary_range' => 30000000,
                        'register_link' => 'https://culinarycreations.id/careers/restaurant-consultant',
                        'type' => $contractId,
                        'experience' => $seniorId,
                        'work_type' => $hybridId,
                    ],
                ],
            ],
        ];
        
        // Create jobs for each company
        foreach ($jobsData as $companyData) {
            $company = Company::where('name', $companyData['company_name'])->first();
            
            if ($company) {
                foreach ($companyData['jobs'] as $jobData) {
                    Job::create([
                        'company_id' => $company->id,
                        'title' => $jobData['title'],
                        'description' => $jobData['description'],
                        'requirment' => $jobData['requirment'],
                        'salary_range' => $jobData['salary_range'],
                        'register_link' => $jobData['register_link'],
                        'type' => $jobData['type'],
                        'experience' => $jobData['experience'],
                        'work_type' => $jobData['work_type'],
                        'read_counter' => rand(0, 200),
                        'created_at' => now()->subDays(rand(1, 30)),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
        
        $this->command->info('Jobs seeded successfully!');
    }

    /**
     * Seed the vicons table.
     */
    private function seedVicons(): void
    {
        // Get users by username for easier reference
        $ichika = User::where('user_name', 'IchikaNakano')->first();
        $nino = User::where('user_name', 'NinoNakano')->first();
        $miku = User::where('user_name', 'MikuNakano')->first();
        $yotsuba = User::where('user_name', 'YotsubaNakano')->first();
        $itsuki = User::where('user_name', 'ItsukiNakano')->first();
        
        if (!$ichika || !$nino || !$miku || !$yotsuba || !$itsuki) {
            $this->command->error('Required users not found! Cannot seed vicons.');
            return;
        }

        $vicons = [
            [
                'title' => 'Introduction to Web Development',
                'desc' => 'Join us for an interactive session covering the fundamentals of HTML, CSS, and JavaScript. This webinar is perfect for beginners looking to start their journey in web development. We will cover basic concepts, demonstrate practical examples, and answer your questions live.',
                'img' => 'https://images.unsplash.com/photo-1547658719-da2b51169166?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80',
                'time' => now()->addDays(7)->setHour(10)->setMinute(0),
                'link' => 'https://meet.google.com/abc-defg-hij',
                'download' => 'https://example.com/downloads/web-dev-slides.pdf',
                'created_by' => $ichika->id,
            ],
            [
                'title' => 'Advanced React Techniques',
                'desc' => 'This technical deep dive will explore advanced React patterns including context API, custom hooks, and performance optimization strategies. Intended for developers with intermediate React experience who want to take their skills to the next level.',
                'img' => 'https://images.unsplash.com/photo-1633356122102-3fe601e05bd2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80',
                'time' => now()->addDays(14)->setHour(15)->setMinute(30),
                'link' => 'https://zoom.us/j/12345678901',
                'download' => 'https://example.com/downloads/react-advanced-materials.zip',
                'created_by' => $nino->id,
            ],
            [
                'title' => 'Database Design Best Practices',
                'desc' => 'Learn how to design efficient, scalable, and maintainable database structures. We\'ll cover normalization, indexing strategies, and common pitfalls to avoid. Case studies will demonstrate real-world applications of these principles.',
                'img' => null,
                'time' => now()->addDays(5)->setHour(13)->setMinute(0),
                'link' => 'https://teams.microsoft.com/l/meetup-join/12345',
                'download' => null,
                'created_by' => $miku->id,
            ],
            [
                'title' => 'UX Research Methods Workshop',
                'desc' => 'This hands-on workshop will introduce various user research methodologies including user interviews, usability testing, and analytics interpretation. Participants will learn how to gather actionable insights to improve product design and user satisfaction.',
                'img' => 'https://images.unsplash.com/photo-1553877522-43269d4ea984?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80',
                'time' => now()->addDays(21)->setHour(9)->setMinute(0),
                'link' => 'https://meet.google.com/xyz-abcd-efg',
                'download' => 'https://example.com/downloads/ux-research-toolkit.pdf',
                'created_by' => $yotsuba->id,
            ],
            [
                'title' => 'Cloud Infrastructure Security',
                'desc' => 'This technical session focuses on securing cloud-based infrastructure across major platforms (AWS, Azure, GCP). Topics include identity management, network security, encryption best practices, and automated security testing.',
                'img' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80',
                'time' => now()->addDays(10)->setHour(16)->setMinute(0),
                'link' => 'https://zoom.us/j/98765432109',
                'download' => 'https://example.com/downloads/cloud-security-checklist.pdf',
                'created_by' => $itsuki->id,
            ],
            [
                'title' => 'Mobile App Development with Flutter',
                'desc' => 'Discover how to build cross-platform mobile applications with Flutter. This session will cover Flutter basics, widget architecture, state management, and deployment strategies for both iOS and Android platforms.',
                'img' => null,
                'time' => now()->addDays(18)->setHour(11)->setMinute(30),
                'link' => 'https://meet.google.com/pqr-stuv-wxy',
                'download' => null,
                'created_by' => $ichika->id,
            ],
            [
                'title' => 'DevOps Pipeline Automation',
                'desc' => 'Learn strategies for automating your development and deployment pipelines. We\'ll cover CI/CD tools, infrastructure as code, automated testing frameworks, and monitoring solutions to streamline your software delivery process.',
                'img' => 'https://images.unsplash.com/photo-1607743386760-88ac62b89b8a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80',
                'time' => now()->addDays(25)->setHour(14)->setMinute(0),
                'link' => 'https://teams.microsoft.com/l/meetup-join/67890',
                'download' => 'https://example.com/downloads/devops-templates.zip',
                'created_by' => $nino->id,
            ],
            [
                'title' => 'Data Science for Beginners',
                'desc' => 'This introductory session will cover the fundamentals of data science including data collection, cleaning, analysis, and visualization. We\'ll use Python and popular libraries like Pandas and Matplotlib to demonstrate practical techniques.',
                'img' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80',
                'time' => now()->addDays(12)->setHour(17)->setMinute(0),
                'link' => 'https://zoom.us/j/12398745601',
                'download' => 'https://example.com/downloads/data-science-starter-kit.zip',
                'created_by' => $miku->id,
            ],
        ];

        foreach ($vicons as $vicon) {
            \App\Models\Vicon::create($vicon);
        }
        
        $this->command->info('Video conferences seeded successfully!');
    }

    /**
     * Seed the submissions table.
     */
    private function seedSubmissions(): void
    {
        $this->command->info('Seeding submissions...');
        
        // Get user IDs
        $userIds = User::pluck('id')->toArray();
        if (empty($userIds)) {
            $this->command->error('No users found! Cannot seed submissions.');
            return;
        }
        
        // Get submission types
        $fileType = Option::where('type', 'submission_type')->where('value', 'file')->first()->id;
        $videoType = Option::where('type', 'submission_type')->where('value', 'video')->first()->id;
        $textType = Option::where('type', 'submission_type')->where('value', 'text')->first()->id;
        
        // Get submission statuses
        $pendingStatus = Option::where('type', 'submission_status')->where('value', 'pending')->first()->id;
        $acceptedStatus = Option::where('type', 'submission_status')->where('value', 'accepted')->first()->id;
        $declinedStatus = Option::where('type', 'submission_status')->where('value', 'declined')->first()->id;
        
        // Sample submissions
        $submissions = [
            // File submissions
            [
                'title' => 'Project Proposal Document',
                'content' => null,
                'file' => '/storage/submissions/project_proposal.pdf',
                'link' => null,
                'img' => null,
                'type' => $fileType,
                'status' => $acceptedStatus,
                'submitted_at' => now()->subDays(15),
            ],
            [
                'title' => 'UI Design Mockups',
                'content' => null,
                'file' => '/storage/submissions/ui_mockups.zip',
                'link' => null,
                'img' => null,
                'type' => $fileType,
                'status' => $pendingStatus,
                'submitted_at' => now()->subDays(2),
            ],
            [
                'title' => 'Technical Documentation',
                'content' => null,
                'file' => '/storage/submissions/api_documentation.docx',
                'link' => null,
                'img' => null,
                'type' => $fileType,
                'status' => $declinedStatus,
                'submitted_at' => now()->subDays(10),
            ],
            
            // Video submissions
            [
                'title' => 'Product Demo Video',
                'content' => null,
                'file' => null,
                'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'img' => 'https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg',
                'type' => $videoType,
                'status' => $acceptedStatus,
                'submitted_at' => now()->subDays(20),
            ],
            [
                'title' => 'Team Introduction Video',
                'content' => null,
                'file' => null,
                'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'img' => 'https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg',
                'type' => $videoType,
                'status' => $pendingStatus,
                'submitted_at' => now()->subDays(3),
            ],
            [
                'title' => 'User Testing Session Recording',
                'content' => null,
                'file' => null,
                'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'img' => 'https://img.youtube.com/vi/dQw4w9WgXcQ/maxresdefault.jpg',
                'type' => $videoType,
                'status' => $declinedStatus,
                'submitted_at' => now()->subDays(12),
            ],
            
            // Text submissions
            [
                'title' => 'Weekly Progress Report',
                'content' => 'This week, our team completed the following tasks:\n\n1. Finalized database schema design\n2. Implemented user authentication system\n3. Created initial API endpoints for core functionality\n4. Set up automated testing framework\n\nNext week\'s priorities:\n- Complete front-end integration\n- Perform security audit\n- Begin user acceptance testing\n- Prepare deployment documentation',
                'file' => null,
                'link' => null,
                'img' => null,
                'type' => $textType,
                'status' => $acceptedStatus,
                'submitted_at' => now()->subDays(7),
            ],
            [
                'title' => 'Feature Request',
                'content' => 'Feature: Advanced Analytics Dashboard\n\nPurpose: To provide users with deeper insights into their data through customizable visualizations and metrics.\n\nKey Components:\n- Customizable widget-based interface\n- Real-time data processing\n- Export capabilities (PDF, CSV, Excel)\n- Role-based access control\n- Saved report templates\n\nBusiness Value:\n- Improve decision-making through better data visibility\n- Increase user engagement with interactive visualizations\n\nEstimated Development Effort: 4-6 weeks',
                'file' => null,
                'link' => null,
                'img' => null,
                'type' => $textType,
                'status' => $pendingStatus,
                'submitted_at' => now()->subDays(1),
            ],
            [
                'title' => 'Bug Report',
                'content' => 'Issue: Payment Processing Failure\n\nDescription: International customers are experiencing payment failures when using Visa cards. The transaction appears to be processed but then fails with error code E-4029.\n\nSteps to Reproduce:\n1. Add item to cart\n2. Proceed to checkout\n3. Enter shipping information for international address\n4. Enter Visa card details\n5. Submit payment\n\nExpected Behavior: Payment processes successfully\n\nActual Behavior: Error message "Transaction declined (E-4029)" appears',
                'file' => null,
                'link' => null,
                'img' => null,
                'type' => $textType,
                'status' => $declinedStatus,
                'submitted_at' => now()->subDays(5),
            ],
        ];
        
        // Create submissions
        foreach ($submissions as $submission) {
            // For each submission, we'll assign random users
            $createdBy = $userIds[array_rand($userIds)];
            $approveBy = null;
            $approveAt = null;
            
            // If the status is accepted or declined, set approval info
            if ($submission['status'] == $acceptedStatus || $submission['status'] == $declinedStatus) {
                $approveBy = $userIds[array_rand($userIds)];
                $approveAt = $submission['submitted_at']->addDays(rand(1, 3));
            }
            
            // Create random read and download counters
            $readCounter = rand(0, 100);
            $downloadCounter = $submission['file'] ? rand(0, 50) : 0;
            
            Submission::create([
                'title' => $submission['title'],
                'content' => $submission['content'],
                'file' => $submission['file'],
                'link' => $submission['link'],
                'img' => $submission['img'],
                'type' => $submission['type'],
                'status' => $submission['status'],
                'read_counter' => $readCounter,
                'download_counter' => $downloadCounter,
                'approve_at' => $approveAt,
                'approve_by' => $approveBy,
                'created_by' => $createdBy,
                'created_at' => $submission['submitted_at'],
                'updated_at' => now(),
            ]);
        }
        
        $this->command->info('Submissions seeded successfully!');
    }
}

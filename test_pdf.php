<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Profile;
use App\Models\Skill;
use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;

// Clear existing
Profile::query()->delete();
Skill::query()->delete();
Project::query()->delete();

Profile::create([
    'user_id' => 1,
    'name' => 'Carlos Darvich',
    'title' => 'Senior Full-Stack Developer',
    'email' => 'carlos@example.com',
    'phone' => '+1234567890',
    'bio' => 'Experienced developer specializing in high-performance real-time applications with WebSockets, extensive AI model integrations, and payment gateways. Fluent in modern PHP and JavaScript ecosystems.',
    'github_url' => 'https://github.com/darvich',
    'linkedin_url' => 'https://linkedin.com/in/darvich'
]);

Skill::create(['user_id' => 1, 'name' => 'PHP / Laravel', 'category' => 'Languages']);
Skill::create(['user_id' => 1, 'name' => 'JavaScript / Vue.js', 'category' => 'Languages']);
Skill::create(['user_id' => 1, 'name' => 'WebSockets (Pusher / Reverb)', 'category' => 'Tools']);
Skill::create(['user_id' => 1, 'name' => 'MercadoPago Checkout API', 'category' => 'Integrations']);
Skill::create(['user_id' => 1, 'name' => 'OpenAI & LLM Integration', 'category' => 'Integrations']);
Skill::create(['user_id' => 1, 'name' => 'Tailwind CSS', 'category' => 'Frameworks']);

Project::create([
    'user_id' => 1,
    'name' => 'CRM Real-time Communication System',
    'description' => 'Built a high performance CRM inbox interface featuring multi-line WhatsApp integration and complete real-time messaging using WebSockets.',
    'url' => 'https://example.com/crm',
    'stack' => ['Laravel', 'WebSockets', 'Meta WhatsApp API']
]);

Project::create([
    'user_id' => 1,
    'name' => 'E-Commerce AI Platform',
    'description' => 'Developed an AI sales assistant platform that manages payments via MercadoPago and processes customer queries through an LLM.',
    'url' => 'https://example.com/ecommerce',
    'stack' => ['Vue.js', 'Laravel', 'MercadoPago', 'OpenAI']
]);

$profile = Profile::first();
$skills = Skill::all()->groupBy('category');
$projects = Project::all();
$educations = collect();
$portfolioUrl = 'https://example.com/port/foo-1';

$pdf = Pdf::loadView('resume.pdf-classic', compact('profile', 'skills', 'projects', 'educations', 'portfolioUrl'));
$pdf->save(public_path('sample_resume.pdf'));

echo "PDF saved successfully to public/sample_resume.pdf\n";

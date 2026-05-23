<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\Skill;
use App\Models\Project;
use App\Models\Education;
use Barryvdh\DomPDF\Facade\Pdf;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ResumeController extends Controller
{
    public function index()
    {
        $profile = Profile::where('user_id', auth()->id())->first();
        $skills = Skill::where('user_id', auth()->id())->get();
        $projects = Project::where('user_id', auth()->id())->get();
        $educations = Education::where('user_id', auth()->id())->get();

        $suggestions = [
            'titles' => Profile::whereNotNull('title')->where('title', '!=', '')->distinct()->pluck('title')->toArray(),
            'skillCategories' => Skill::whereNotNull('category')->where('category', '!=', '')->distinct()->pluck('category')->toArray(),
            'skillNames' => Skill::whereNotNull('name')->where('name', '!=', '')->get(['category', 'name'])->groupBy('category')->map(function($items) {
                return $items->pluck('name')->unique()->values()->toArray();
            })->toArray(),
            'schools' => Education::whereNotNull('school')->where('school', '!=', '')->distinct()->pluck('school')->toArray(),
            'degrees' => Education::whereNotNull('degree')->where('degree', '!=', '')->distinct()->pluck('degree')->toArray(),
        ];

        return view('resume.index', compact('profile', 'skills', 'projects', 'educations', 'suggestions'));
    }

    public function save(Request $request)
    {
        $profile = Profile::firstOrNew(['user_id' => auth()->id()]);
        $profile->fill($request->only(['name', 'title', 'email', 'phone', 'bio', 'linkedin_url', 'github_url', 'show_qr']));
        
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $profile->photo = $path;
        }
        
        $profile->save();

        Skill::where('user_id', auth()->id())->delete();
        if ($request->has('skills')) {
            foreach ($request->skills as $skillData) {
                if (!empty($skillData['name']) || !empty($skillData['custom_name'])) {
                    $name = !empty($skillData['custom_name']) ? $skillData['custom_name'] : $skillData['name'];
                    Skill::create([
                        'user_id' => auth()->id(),
                        'name' => $name,
                        'category' => $skillData['category'] ?? 'Language',
                        'proficiency' => $skillData['proficiency'] ?? 3,
                    ]);
                }
            }
        }

        Project::where('user_id', auth()->id())->delete();
        if ($request->has('projects')) {
            foreach ($request->projects as $projectData) {
                if (!empty($projectData['name'])) {
                    Project::create([
                        'user_id' => auth()->id(),
                        'name' => $projectData['name'],
                        'description' => $projectData['description'] ?? '',
                        'url' => $projectData['url'] ?? '',
                        'stack' => !empty($projectData['stack']) ? explode(',', $projectData['stack']) : [],
                    ]);
                }
            }
        }

        Education::where('user_id', auth()->id())->delete();
        if ($request->has('educations')) {
            foreach ($request->educations as $eduData) {
                if (!empty($eduData['school'])) {
                    Education::create([
                        'user_id' => auth()->id(),
                        'school' => $eduData['school'],
                        'degree' => $eduData['degree'] ?? '',
                        'year' => $eduData['year'] ?? '',
                    ]);
                }
            }
        }

        return redirect()->route('resume.index')->with('success', 'Resume data saved successfully.');
    }

    private function generatePdf(Request $request)
    {
        $profile = Profile::where('user_id', auth()->id())->first();
        if (!$profile) return null;

        $skills = Skill::where('user_id', auth()->id())->get()->groupBy('category');
        $projects = Project::where('user_id', auth()->id())->get();
        $educations = Education::where('user_id', auth()->id())->get();

        $lang = $request->get('lang', 'original');
        $template = $request->get('template', 'classic'); // classic, minimal, creative
        
        if ($lang !== 'original') {
            try {
                $tr = new GoogleTranslate();
                $tr->setTarget($lang);
                
                $toTranslate = [];
                if (!empty($profile->title)) $toTranslate['t_title'] = $profile->title;
                if (!empty($profile->bio)) $toTranslate['t_bio'] = $profile->bio;
                
                $i = 0;
                foreach ($skills as $cat => $skls) {
                    $toTranslate['cat_'.$i] = $cat;
                    $i++;
                }
                
                foreach ($projects as $idx => $p) {
                    if (!empty($p->description)) $toTranslate['p_desc_'.$idx] = $p->description;
                    if (!empty($p->name)) $toTranslate['p_name_'.$idx] = $p->name;
                }

                foreach ($educations as $idx => $edu) {
                    if (!empty($edu->school)) $toTranslate['edu_s_'.$idx] = $edu->school;
                    if (!empty($edu->degree)) $toTranslate['edu_d_'.$idx] = $edu->degree;
                    if (!empty($edu->year)) $toTranslate['edu_y_'.$idx] = $edu->year;
                }
                
                if (count($toTranslate) > 0) {
                    $keys = array_keys($toTranslate);
                    $values = array_values($toTranslate);
                    
                    // Bundle all requests into ONE payload
                    $combinedString = implode(" ||| ", $values);
                    $translatedCombined = $tr->translate($combinedString);
                    
                    // Split the result back preserving indices
                    $translatedParts = explode("|||", $translatedCombined);
                    $translatedParts = array_map('trim', $translatedParts);
                    
                    if (count($translatedParts) === count($keys)) {
                        $translatedAssoc = array_combine($keys, $translatedParts);
                        
                        if (isset($translatedAssoc['t_title'])) $profile->title = $translatedAssoc['t_title'];
                        if (isset($translatedAssoc['t_bio'])) $profile->bio = $translatedAssoc['t_bio'];
                        
                        $translatedSkills = [];
                        $i = 0;
                        foreach ($skills as $cat => $skls) {
                            $translatedCat = isset($translatedAssoc['cat_'.$i]) ? ucfirst(strtolower($translatedAssoc['cat_'.$i])) : $cat;
                            $translatedSkills[$translatedCat] = $skls;
                            $i++;
                        }
                        $skills = collect($translatedSkills);
                        
                        foreach ($projects as $idx => $p) {
                            if (isset($translatedAssoc['p_desc_'.$idx])) $p->description = $translatedAssoc['p_desc_'.$idx];
                            if (isset($translatedAssoc['p_name_'.$idx])) $p->name = $translatedAssoc['p_name_'.$idx];
                        }

                        foreach ($educations as $idx => $edu) {
                            if (isset($translatedAssoc['edu_s_'.$idx])) $edu->school = $translatedAssoc['edu_s_'.$idx];
                            if (isset($translatedAssoc['edu_d_'.$idx])) $edu->degree = $translatedAssoc['edu_d_'.$idx];
                            if (isset($translatedAssoc['edu_y_'.$idx])) $edu->year = $translatedAssoc['edu_y_'.$idx];
                        }
                    }
                }
            } catch (\Exception $e) {
                // Ignore translation errors
            }
        }

        $view = 'resume.pdf-' . $template; 
        if (!view()->exists($view)) {
             $view = 'resume.pdf-classic';
        }

        // We will generate the portfolio url to be used in QR codes
        $portfolioUrl = url('/p/' . \Illuminate\Support\Str::slug($profile->name) . '-' . $profile->user_id);

        $showQr = $request->has('show_qr') ? (bool)$request->get('show_qr') : ($profile->show_qr ?? true);

        return Pdf::loadView($view, compact('profile', 'skills', 'projects', 'educations', 'portfolioUrl', 'showQr'));
    }

    public function download(Request $request)
    {
        $pdf = $this->generatePdf($request);
        if (!$pdf) return redirect()->route('resume.index')->withErrors('Please save your profile first.');
        
        $lang = $request->get('lang', 'original');
        return $pdf->download('Resume_' . strtoupper($lang) . '.pdf');
    }

    public function preview(Request $request)
    {
        $pdf = $this->generatePdf($request);
        if (!$pdf) return response('Save profile before previewing', 404);
        
        return $pdf->stream('preview.pdf');
    }

    public function downloadCover(Request $request)
    {
        $profile = Profile::where('user_id', auth()->id())->first();
        if (!$profile) return redirect()->route('resume.index')->withErrors('Please save your profile first.');

        $skills = Skill::where('user_id', auth()->id())->limit(5)->pluck('name')->toArray();
        $projects = Project::where('user_id', auth()->id())->limit(2)->get();
        $education = Education::where('user_id', auth()->id())->first();

        $company = $request->get('company', '');
        $recruiter = $request->get('recruiter', '');
        $lang = $request->get('lang', 'original');

        $greeting = $recruiter ? "Dear $recruiter," : "Dear Hiring Manager,";
        if ($company) {
            $para1 = "I am writing to express my enthusiastic interest in the " . ($profile->title ?: 'professional role') . " position at $company. Having followed $company's impact in the industry, I am eager to contribute my expertise to your innovative team.";
        } else {
            $para1 = "I am writing to express my strong interest in joining your team. As a " . ($profile->title ?: 'dedicated professional') . ", I have developed a deep expertise in modern technologies and strategies to drive significant results.";
        }

        $paraBio = $profile->bio ? "My professional journey has been defined by " . $profile->bio : "";
        
        $pDetails = [];
        foreach ($projects as $p) {
            $pDetails[] = $p->name . ($p->stack ? " (built with " . implode(', ', (array)$p->stack) . ")" : "");
        }
        
        $para2 = "";
        if (count($pDetails) > 0) {
            $para2 = "Throughout my career, I have successfully executed impactful projects such as " . implode(' and ', $pDetails) . ". ";
        }
        
        if (count($skills) > 0) {
            $para2 .= "These experiences allowed me to refine my technical command over " . implode(', ', $skills) . ". ";
        }

        $para3 = "I am passionate about creating impactful solutions and am confident that my background makes me an excellent fit for your organization. ";
        if ($education) {
            $para3 .= "My academic foundation from " . $education->school . ", where I earned a " . $education->degree . ", has equipped me with the analytical mindset necessary for complex problem-solving.";
        }

        $content = [
            'greeting' => $greeting,
            'para1' => $para1,
            'para2' => $para2,
            'para3' => $para3,
            'paraBio' => $paraBio,
            'closing' => 'Thank you for your time and consideration. I look forward to the possibility of discussing how my background aligns with the needs of your dynamic team.',
            'signoff' => 'Sincerely,'
        ];

        if ($lang !== 'original' && $lang !== '') {
            try {
                $tr = new GoogleTranslate();
                $tr->setTarget($lang);
                
                $keys = array_keys($content);
                $values = array_values($content);
                $combined = implode(" ||| ", $values);
                $translatedCombined = $tr->translate($combined);
                $translated = explode("|||", $translatedCombined);
                $translated = array_map('trim', $translated);
                
                if (count($translated) === count($content)) {
                    $content = array_combine($keys, $translated);
                }
            } catch (\Exception $e) { }
        }

        $pdf = Pdf::loadView('resume.cover-letter', compact('profile', 'content'));
        return $pdf->download('CoverLetter_' . ($company ? \Illuminate\Support\Str::slug($company) : 'Personalized') . '.pdf');
    }
}

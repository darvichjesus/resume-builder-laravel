<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Skill;
use App\Models\Project;
use App\Models\Education;

class PortfolioController extends Controller
{
    public function show($slug)
    {
        // Extract the user_id from the end of the slug pattern /p/name-1
        preg_match('/-(\d+)$/', $slug, $matches);
        $userId = $matches[1] ?? null;
        if (!$userId) abort(404);

        $profile = Profile::where('user_id', $userId)->firstOrFail();
        $skills = Skill::where('user_id', $userId)->get()->groupBy('category');
        $projects = Project::where('user_id', $userId)->get();
        $educations = Education::where('user_id', $userId)->get();

        return view('portfolio.show', compact('profile', 'skills', 'projects', 'educations'));
    }
}

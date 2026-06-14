<?php
namespace App\Http\Controllers;

use App\Mail\NewJobApplicationNotification;
use App\Models\CareerPosting;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class JobApplicationController extends Controller
{
    public function show(CareerPosting $career)
    {
        abort_unless($career->is_active && ! $career->is_expired, 404);

        return view('careers.apply', compact('career'));
    }

    public function store(Request $request, CareerPosting $career)
    {
        abort_unless($career->is_active && ! $career->is_expired, 404);

        // Rate limit: 3 submissions per 10 minutes per IP
        $key = 'job_apply.' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors(['email' => "Too many submissions. Please try again in {$seconds} seconds."]);
        }

        $validated = $request->validate([
            'first_name'   => 'required|string|max:100',
            'last_name'    => 'required|string|max:100',
            'email'        => 'required|email|max:255',
            'phone'        => 'nullable|string|max:30',
            'city'         => 'nullable|string|max:150',
            'linkedin_url' => 'nullable|url|max:500',
            'cover_letter' => 'nullable|string|max:5000',
            'cv'           => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        // Store CV
        $cvFile         = $request->file('cv');
        $cvPath         = $cvFile->store('applications/cv', 'public');
        $cvOriginalName = $cvFile->getClientOriginalName();

        $application = JobApplication::create([
            'career_posting_id' => $career->id,
            'first_name'        => $validated['first_name'],
            'last_name'         => $validated['last_name'],
            'email'             => $validated['email'],
            'phone'             => $validated['phone'] ?? null,
            'city'              => $validated['city'] ?? null,
            'linkedin_url'      => $validated['linkedin_url'] ?? null,
            'cover_letter'      => $validated['cover_letter'] ?? null,
            'cv_path'           => $cvPath,
            'cv_original_name'  => $cvOriginalName,
            'status'            => 'new',
            'ip_address'        => $request->ip(),
            'user_agent'        => $request->userAgent(),
        ]);

        RateLimiter::hit($key, 600);

        // Notify admin
        try {
            $notifyEmail = $career->apply_email ?? config('amsparts.jobs_email') ?? config('amsparts.email_general');

            Mail::to($notifyEmail)->send(new NewJobApplicationNotification($application));
        } catch (\Throwable $e) {
            Log::error('Job application notification failed', [
                'application_id' => $application->id,
                'error'          => $e->getMessage(),
            ]);
        }

        return redirect()->route('careers')
            ->with('success', 'Your application has been submitted! We will review it and get back to you.');
    }
}

<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CareerPosting;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class AdminJobApplicationsController extends Controller
{
    // ─── All applications across all jobs ────────────────────────────
    public function index(Request $request)
    {
        $query = JobApplication::with('careerPosting')->latest();

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('first_name', 'like', "%{$term}%")
                    ->orWhere('last_name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('career_id')) {
            $query->where('career_posting_id', $request->career_id);
        }

        $applications = $query->paginate(25)->withQueryString();
        $careers      = CareerPosting::orderBy('title')->get(['id', 'title']);

        return view('admin.job-applications.index', compact('applications', 'careers'));
    }

    // ─── Applications for a specific job ─────────────────────────────
    public function byJob(CareerPosting $career)
    {
        $applications = JobApplication::where('career_posting_id', $career->id)
            ->latest()
            ->paginate(25);

        return view('admin.job-applications.by-job', compact('career', 'applications'));
    }

    // ─── Single application detail ────────────────────────────────────
    public function show(JobApplication $application)
    {
        $application->load('careerPosting');

        // Mark as reviewed when first opened
        if ($application->status === 'new') {
            $application->update(['status' => 'reviewed']);
        }

        return view('admin.job-applications.show', compact('application'));
    }

    // ─── Update status / notes ────────────────────────────────────────
    public function update(Request $request, JobApplication $application)
    {
        $request->validate([
            'status'      => 'required|in:new,reviewed,shortlisted,rejected,hired',
            'admin_notes' => 'nullable|string',
        ]);

        $application->update($request->only('status', 'admin_notes'));

        return redirect()
            ->route('admin.job-applications.show', $application)
            ->with('success', 'Application updated.');
    }

    // ─── Quick status update via AJAX ─────────────────────────────────
    public function updateStatus(Request $request, JobApplication $application)
    {
        $request->validate(['status' => 'required|in:new,reviewed,shortlisted,rejected,hired']);
        $application->update(['status' => $request->status]);

        return response()->json(['success' => true]);
    }

    // ─── Delete ───────────────────────────────────────────────────────
    public function destroy(JobApplication $application)
    {
        // Delete CV file from storage
        if ($application->cv_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($application->cv_path);
        }

        $application->delete();

        return redirect()
            ->route('admin.job-applications.index')
            ->with('success', 'Application deleted.');
    }
}

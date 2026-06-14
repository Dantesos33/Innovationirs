<?php
namespace App\Http\Controllers;

use App\Models\CareerPosting;
use App\Models\Faq;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;

class PageController extends Controller
{
    public function about()
    {
        $testimonials = Cache::remember('about.testimonials', 3600, fn() =>
            Testimonial::active()
                ->orderBy('sort_order')
                ->take(6)
                ->get()
        );

        return view('pages.about', compact('testimonials'));
    }

    public function faqs()
    {
        $faqs = Faq::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        return view('pages.faqs', compact('faqs'));
    }

    public function careers()
    {
        $jobs = CareerPosting::where('is_active', true)
            ->orderBy('department')
            ->orderBy('title')
            ->get()
            ->groupBy('department');

        return view('pages.careers', compact('jobs'));
    }

    public function warranty()
    {
        return view('pages.warranty');
    }

    public function shipping()
    {
        return view('pages.shipping');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function prop65()
    {
        return view('pages.prop65');
    }
}

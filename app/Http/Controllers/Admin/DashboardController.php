<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardStatsService;

class DashboardController extends Controller
{
    public function __construct(protected DashboardStatsService $stats)
    {}

    public function index()
    {
        return view('admin.dashboard', [
            'stats'           => $this->stats->getStats(),
            'chartLabels'     => $this->stats->getChartLabels(),
            'chartData'       => $this->stats->getChartData(),
            'statusBreakdown' => $this->stats->getQuoteStatusBreakdown(),
            'recentQuotes'    => $this->stats->getRecentQuotes(8),
            'recentContacts'  => $this->stats->getRecentContacts(5),
        ]);
    }
}

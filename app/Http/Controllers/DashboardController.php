<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\Api\SubmissionController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\MaterialController;
use App\Models\Submission;
use App\Models\Contact;
use App\Models\Activity;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with user's statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get submission statistics
        $submissionsCount = Submission::where('created_by', $user->id)->count();
        $lastMonthSubmissions = Submission::where('created_by', $user->id)
            ->where('created_at', '>=', Carbon::now()->subMonth())
            ->count();
        $prevMonthSubmissions = Submission::where('created_by', $user->id)
            ->whereBetween('created_at', [
                Carbon::now()->subMonths(2),
                Carbon::now()->subMonth()
            ])
            ->count();
        
        $submissionsChange = $this->calculatePercentChange($lastMonthSubmissions, $prevMonthSubmissions);
        
        // Get the latest submission status (if any)
        $latestSubmission = Submission::where('created_by', $user->id)
            ->latest()
            ->first();

        // Get the status value from the related option table    
        $latestSubmissionStatus = null;
        if ($latestSubmission) {
            $statusOption = \App\Models\Option::find($latestSubmission->status);
            $latestSubmissionStatus = $statusOption ? $statusOption->value : null;
        }
        
        // Get contact statistics
        $contactsCount = Contact::where('created_by', $user->id)->count();

        // Get the pending status ID (messages that need attention)
        $pendingStatusId = \App\Models\Option::where('type', 'contact_status')
            ->where('value', 'pending')
            ->first()?->id;

        // Get the responded status ID (for completed messages)
        $respondedStatusId = \App\Models\Option::where('type', 'contact_status')
            ->where('value', 'responded')
            ->first()?->id;

        $pendingMessagesCount = 0;
        $respondedMessagesCount = 0;

        if ($pendingStatusId) {
            $pendingMessagesCount = Contact::where('created_by', $user->id)
                ->where('status', $pendingStatusId)
                ->count();
        }

        if ($respondedStatusId) {
            $respondedMessagesCount = Contact::where('created_by', $user->id)
                ->where('status', $respondedStatusId)
                ->count();
        }
        
        // Get view statistics - placeholder
        $viewsCount = 0; 
        $viewsChange = 0;
        
        // Get materials accessed statistics - placeholder
        $materialsCount = 0;
        $materialsChange = 0;
        
        // Get recent activities from the Activity model
        $recentActivities = Activity::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
        
        // Set user profile completeness
        $profileComplete = !empty($user->user_name) && !empty($user->email) && !empty($user->first_name) && !empty($user->last_name);
        
        return view('dashboard', compact(
            'user',
            'submissionsCount',
            'submissionsChange',
            'latestSubmissionStatus',
            'contactsCount',
            'pendingMessagesCount',
            'respondedMessagesCount',
            'viewsCount',
            'viewsChange',
            'materialsCount',
            'materialsChange',
            'recentActivities',
            'profileComplete'
        ));
    }
    
    /**
     * Calculate percentage change between two values.
     *
     * @param int $current
     * @param int $previous
     * @return int
     */
    private function calculatePercentChange($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        
        return round((($current - $previous) / $previous) * 100);
    }
}
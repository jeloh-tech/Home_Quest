<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Listing;
use App\Models\User;
use Illuminate\View\View;

class PropertyController extends Controller
{
    /**
     * Display all listings
     */
    public function index(): View
    {
        $listings = Listing::with('user')->latest()->paginate(10);
        return view('admin.listings', compact('listings'));
    }

    /**
     * Display active listings
     */
    public function active(): View
    {
        $listings = Listing::with('user')
            ->where('status', 'active')
            ->latest()
            ->paginate(10);
        return view('admin.listings', compact('listings'));
    }

    /**
     * Display rented properties
     */
    public function rented(): View
    {
        $listings = Listing::with('user')
            ->where('status', 'rented')
            ->latest()
            ->paginate(10);
        return view('admin.listings', compact('listings'));
    }

    /**
     * Display analytics
     */
    public function analytics(): View
    {
        $totalListings = Listing::count();
        $activeListings = Listing::where('status', 'active')->count();
        $rentedListings = Listing::where('status', 'rented')->count();
        $totalRevenue = Listing::where('status', 'rented')->sum('price');
        
        return view('admin.analytics', compact(
            'totalListings',
            'activeListings',
            'rentedListings',
            'totalRevenue'
        ));
    }
}

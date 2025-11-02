<?php

// Add unbanListing method to AdminDashboardController
trait UnbanListingTrait
{
    /**
     * Unban a listing.
     */
    public function unbanListing(Listing $listing): RedirectResponse
    {
        // Update listing status to active
        $listing->update([
            'status' => 'active'
        ]);

        return redirect()->back()->with('success', 'Listing unbanned successfully.');
    }
}

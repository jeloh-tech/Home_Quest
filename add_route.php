<?php
$content = file_get_contents('routes/web.php');
$content = str_replace(
    "    Route::put('/profile', [LandlordDashboardController::class, 'updateProfile'])->name('profile.update');",
    "    Route::put('/profile', [LandlordDashboardController::class, 'updateProfile'])->name('profile.update');\n    Route::put('/password', [LandlordDashboardController::class, 'updatePassword'])->name('password.update');",
    $content
);
file_put_contents('routes/web.php', $content);
echo "Route added successfully";
?>

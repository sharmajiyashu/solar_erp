<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SiteVisit;

$statuses = SiteVisit::select('status')->distinct()->pluck('status');
echo "Distinct statuses in site_visits table:\n";
foreach ($statuses as $status) {
    echo "- '$status'\n";
}

$completedVisits = SiteVisit::where('status', 'completed')->with('lead')->get();
echo "\nVisits with status 'completed':\n";
foreach ($completedVisits as $visit) {
    echo "- Lead ID: {$visit->lead_id}, Lead Stage: '{$visit->lead->stage}'\n";
}

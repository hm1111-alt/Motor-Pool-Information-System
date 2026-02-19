<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Itinerary;

echo "=== Itineraries Data Check ===\n\n";

$itineraries = Itinerary::all();

foreach($itineraries as $itinerary) {
    echo "ID: " . $itinerary->id . "\n";
    echo "Status: " . $itinerary->status . "\n";
    echo "Unit Head Approved: " . $itinerary->unit_head_approved . "\n";
    echo "VP Approved: " . $itinerary->vp_approved . "\n";
    echo "Unit Head Approved At: " . ($itinerary->unit_head_approved_at ? $itinerary->unit_head_approved_at->format('Y-m-d H:i:s') : 'NULL') . "\n";
    echo "VP Approved At: " . ($itinerary->vp_approved_at ? $itinerary->vp_approved_at->format('Y-m-d H:i:s') : 'NULL') . "\n";
    echo "Date From: " . $itinerary->date_from . "\n";
    echo "Destination: " . $itinerary->destination . "\n";
    echo "---\n";
}

echo "\n=== Count by Status Field ===\n";
$pendingCount = Itinerary::where('status', 'Not yet Approved')->count();
$approvedCount = Itinerary::where('status', 'Approved')->count();
$cancelledCount = Itinerary::where('status', 'Cancelled')->count();

echo "Pending (Not yet Approved): " . $pendingCount . "\n";
echo "Approved: " . $approvedCount . "\n";
echo "Cancelled: " . $cancelledCount . "\n";

echo "\n=== Count by Approval Fields ===\n";
$pendingByApproval = Itinerary::where(function($q) {
    $q->where('unit_head_approved', false)
      ->whereNull('unit_head_approved_at')
      ->orWhere(function($q2) {
          $q2->where('unit_head_approved', true)
             ->where('vp_approved', false)
             ->whereNull('vp_approved_at');
      });
})->count();

$approvedByApproval = Itinerary::where('unit_head_approved', true)
    ->where('vp_approved', true)
    ->whereNotNull('vp_approved_at')
    ->count();

$cancelledByApproval = Itinerary::where(function($q) {
    $q->where('unit_head_approved', false)
      ->whereNotNull('unit_head_approved_at')
      ->orWhere(function($q2) {
          $q2->where('unit_head_approved', true)
             ->where('vp_approved', false)
             ->whereNotNull('vp_approved_at');
      });
})->count();

echo "Pending by Approval Logic: " . $pendingByApproval . "\n";
echo "Approved by Approval Logic: " . $approvedByApproval . "\n";
echo "Cancelled by Approval Logic: " . $cancelledByApproval . "\n";
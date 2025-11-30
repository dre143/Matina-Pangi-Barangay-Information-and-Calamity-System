<?php

namespace App\Http\Controllers;

use App\Models\ResidentTransfer;
use App\Models\Resident;
use App\Models\Household;
use App\Models\HouseholdEvent;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResidentTransferController extends Controller
{
    /**
     * Display a listing of transfers
     */
    public function index(Request $request)
    {
        $query = ResidentTransfer::with(['resident', 'oldHousehold', 'newHousehold', 'creator', 'approver']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by transfer type
        if ($request->filled('type')) {
            $query->where('transfer_type', $request->type);
        }

        // Search by resident name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('resident', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // Filter by reason
        if ($request->filled('reason')) {
            $query->where('reason', $request->reason);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('transfer_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('transfer_date', '<=', $request->date_to);
        }

        // Filter by processed by
        if ($request->filled('processed_by')) {
            $query->where('processed_by', $request->processed_by);
        }

        // Filter by from purok
        if ($request->filled('from_purok')) {
            $query->whereHas('oldHousehold', function($q) use ($request) {
                $q->whereHas('purok', function($q) use ($request) {
                    $q->where('purok_name', $request->from_purok);
                });
            });
        }

        // Load relationships including soft-deleted residents
        $transfers = $query->with([
            'resident' => function($q) {
                $q->withTrashed();
            },
            'oldHousehold.purok',
            'newHousehold.purok',
            'creator',
            'approver'
        ])->latest()->paginate(20)->appends($request->except('page'));

        // Get staff for filter dropdown
        $staff = \App\Models\User::where('role', '!=', 'resident')->get(['id', 'name']);

        return view('resident-transfers.index', compact('transfers', 'staff'));
    }

    /**
     * Show pending transfers (Secretary only)
     */
    public function pending()
    {
        if (!auth()->user()->isSecretary()) {
            abort(403, 'Unauthorized action.');
        }

        $pendingTransfers = ResidentTransfer::with([
                'resident' => function($q) {
                    $q->withTrashed();
                },
                'oldHousehold',
                'newHousehold',
                'creator'
            ])
            ->pending()
            ->latest()
            ->paginate(20);

        return view('resident-transfers.pending', compact('pendingTransfers'));
    }

    /**
     * Show the form for creating a new transfer
     */
    public function create(Request $request)
    {
        $resident = null;
        if ($request->filled('resident_id')) {
            $resident = Resident::with('household')->findOrFail($request->resident_id);
        }

        $residents = Resident::approved()->active()
            ->orderBy('last_name')
            ->get()
            ->map(function($r) {
                return [
                    'id' => $r->id,
                    'label' => "{$r->full_name} - {$r->household->household_id}"
                ];
            });

        $households = Household::approved()
            ->with('officialHead')
            ->get()
            ->map(function($h) {
                return [
                    'id' => $h->id,
                    'label' => "{$h->household_id} - " . ($h->officialHead ? $h->officialHead->full_name : 'No Head') . " ({$h->purok})"
                ];
            });

        return view('resident-transfers.create', compact('residents', 'households', 'resident'));
    }

    /**
     * Store a newly created transfer request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'transfer_type' => 'required|in:internal,external',
            'new_household_id' => 'required_if:transfer_type,internal|nullable|exists:households,id',
            'new_purok' => 'nullable|string|max:100',
            'destination_address' => 'required_if:transfer_type,external|nullable|string',
            'destination_barangay' => 'nullable|string|max:255',
            'destination_municipality' => 'nullable|string|max:255',
            'destination_province' => 'nullable|string|max:255',
            'reason' => 'required|in:work,marriage,school,family,health,other',
            'reason_for_transfer' => 'required|string',
            'transfer_date' => 'required|date',
        ]);

        // Get resident's current household
        $resident = Resident::findOrFail($validated['resident_id']);
        $validated['old_household_id'] = $resident->household_id;
        $validated['old_purok'] = $resident->household->purok;

        // Map transfer_type: 'internal'/'external' -> 'transfer_in'/'transfer_out'
        if ($validated['transfer_type'] === 'external') {
            $validated['transfer_type'] = 'transfer_out';
        } else {
            $validated['transfer_type'] = 'transfer_in';
        }

        // Set initial status and creator
        $validated['created_by'] = auth()->id();
        
        // Auto-approve if Secretary, pending if Staff
        if (auth()->user()->isSecretary()) {
            $validated['status'] = 'approved';
            $validated['approved_by'] = auth()->id();
            $validated['approved_at'] = now();
        } else {
            $validated['status'] = 'pending';
        }

        $transfer = ResidentTransfer::create($validated);

        AuditLog::logAction(
            'create',
            'ResidentTransfer',
            $transfer->id,
            "Transfer request created for {$resident->full_name}"
        );

        // If auto-approved (Secretary), process immediately
        if (auth()->user()->isSecretary()) {
            $this->processTransfer($transfer);
            
            return redirect()->route('resident-transfers.show', $transfer)
                ->with('success', 'Transfer request created and processed successfully!');
        }

        return redirect()->route('resident-transfers.show', $transfer)
            ->with('success', 'Transfer request submitted successfully! Waiting for Secretary approval.');
    }

    /**
     * Display the specified transfer
     */
    public function show(ResidentTransfer $residentTransfer)
    {
        // Load relationships including soft-deleted residents (for external transfers)
        $residentTransfer->load([
            'resident' => function($query) {
                $query->withTrashed(); // Include soft-deleted residents
            },
            'oldHousehold',
            'newHousehold',
            'creator',
            'approver'
        ]);
        
        return view('resident-transfers.show', compact('residentTransfer'));
    }

    /**
     * Approve a transfer request (Secretary only)
     */
    public function approve(ResidentTransfer $residentTransfer)
    {
        if (!auth()->user()->isSecretary()) {
            abort(403, 'Unauthorized action.');
        }

        if ($residentTransfer->status !== 'pending') {
            return back()->with('error', 'Only pending transfers can be approved.');
        }

        DB::transaction(function () use ($residentTransfer) {
            // Update transfer status
            $residentTransfer->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Process the transfer
            $this->processTransfer($residentTransfer);

            AuditLog::logAction(
                'approve',
                'ResidentTransfer',
                $residentTransfer->id,
                "Transfer approved for {$residentTransfer->resident->full_name}"
            );
        });

        return redirect()->route('resident-transfers.show', $residentTransfer)
            ->with('success', 'Transfer approved and processed successfully!');
    }

    /**
     * Reject a transfer request (Secretary only)
     */
    public function reject(Request $request, ResidentTransfer $residentTransfer)
    {
        if (!auth()->user()->isSecretary()) {
            abort(403, 'Unauthorized action.');
        }

        if ($residentTransfer->status !== 'pending') {
            return back()->with('error', 'Only pending transfers can be rejected.');
        }

        $validated = $request->validate([
            'remarks' => 'required|string',
        ]);

        $residentTransfer->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'remarks' => $validated['remarks'],
        ]);

        AuditLog::logAction(
            'reject',
            'ResidentTransfer',
            $residentTransfer->id,
            "Transfer rejected for {$residentTransfer->resident->full_name}"
        );

        return redirect()->route('resident-transfers.show', $residentTransfer)
            ->with('success', 'Transfer request rejected.');
    }

    /**
     * Process the approved transfer
     */
    protected function processTransfer(ResidentTransfer $transfer)
    {
        $resident = $transfer->resident;
        $oldHousehold = $transfer->oldHousehold;

        if ($transfer->transfer_type === 'transfer_in') {
            // Internal transfer within Matina Pangi
            $newHousehold = $transfer->newHousehold;

            // Create household event for old household
            HouseholdEvent::create([
                'household_id' => $oldHousehold->id,
                'event_type' => 'member_removed',
                'description' => "Resident {$resident->full_name} transferred to household {$newHousehold->household_id}",
                'reason' => $transfer->reason,
                'event_date' => $transfer->transfer_date,
                'processed_by' => auth()->id(),
            ]);

            // Update resident's household
            $resident->update([
                'household_id' => $newHousehold->id,
            ]);

            // Create household event for new household
            HouseholdEvent::create([
                'household_id' => $newHousehold->id,
                'event_type' => 'member_added',
                'description' => "Resident {$resident->full_name} transferred from household {$oldHousehold->household_id}",
                'reason' => $transfer->reason,
                'event_date' => $transfer->transfer_date,
                'processed_by' => auth()->id(),
            ]);

            // Update household member counts
            $oldHousehold->update(['total_members' => $oldHousehold->residents()->count()]);
            $newHousehold->update(['total_members' => $newHousehold->residents()->count()]);

       } elseif ($transfer->transfer_type === 'transfer_out') {
            // External transfer - moving out of Matina Pangi
            HouseholdEvent::create([
                'household_id' => $oldHousehold->id,
                'event_type' => 'relocation',
                'description' => "Resident {$resident->full_name} relocated to {$transfer->destination_barangay}, {$transfer->destination_municipality}",
                'reason' => $transfer->reason,
                'event_date' => $transfer->transfer_date,
                'processed_by' => auth()->id(),
            ]);

            // Mark resident as relocated and archive
            $resident->update([
                'status' => 'reallocated',
            ]);
            $resident->delete(); // Soft delete

            // Update household member count
            $oldHousehold->update(['total_members' => $oldHousehold->residents()->count()]);
        }

        // Mark transfer as completed
        $transfer->update(['status' => 'completed']);
    }

    /**
     * Remove the specified transfer
     */
    public function destroy(ResidentTransfer $residentTransfer)
    {
        if (!auth()->user()->isSecretary()) {
            abort(403, 'Unauthorized action.');
        }

        if ($residentTransfer->status !== 'pending') {
            return back()->with('error', 'Only pending transfers can be deleted.');
        }

        $residentTransfer->delete();

        return redirect()->route('resident-transfers.index')
            ->with('success', 'Transfer request deleted successfully.');
    }
}

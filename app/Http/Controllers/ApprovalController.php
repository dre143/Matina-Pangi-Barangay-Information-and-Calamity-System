<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\Household;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    /**
     * Show pending approvals dashboard
     */
    public function index()
    {
        // Only secretary can access
        if (!auth()->user()->isSecretary()) {
            abort(403, 'Unauthorized action.');
        }

        $pendingResidents = Resident::with(['household', 'creator'])
            ->pending()
            ->latest()
            ->paginate(15, ['*'], 'residents');

        $pendingHouseholds = Household::with(['head', 'residents'])
            ->pending()
            ->latest()
            ->paginate(15, ['*'], 'households');

        return view('approvals.index', compact('pendingResidents', 'pendingHouseholds'));
    }

    /**
     * Approve a resident
     */
    public function approveResident(Resident $resident)
    {
        // Only secretary can approve
        if (!auth()->user()->isSecretary()) {
            abort(403, 'Unauthorized action.');
        }

        $resident->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'rejection_reason' => null,
        ]);

        AuditLog::logAction(
            'approve',
            'Resident',
            $resident->id,
            "Approved resident: {$resident->full_name}"
        );

        return back()->with('success', 'Resident approved successfully!');
    }

    /**
     * Reject a resident
     */
    public function rejectResident(Request $request, Resident $resident)
    {
        // Only secretary can reject
        if (!auth()->user()->isSecretary()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $resident->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Archive rejected resident
        $resident->delete();

        AuditLog::logAction(
            'reject',
            'Resident',
            $resident->id,
            "Rejected and archived resident: {$resident->full_name}",
            null,
            ['rejection_reason' => $request->rejection_reason]
        );

        return back()->with('success', 'Resident rejected and archived.');
    }

    /**
     * Approve a household
     */
    public function approveHousehold(Household $household)
    {
        // Only secretary can approve
        if (!auth()->user()->isSecretary()) {
            abort(403, 'Unauthorized action.');
        }

        $household->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'rejection_reason' => null,
        ]);

        // Also approve all residents in the household
        $household->residents()->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        AuditLog::logAction(
            'approve',
            'Household',
            $household->id,
            "Approved household: {$household->household_id}"
        );

        return back()->with('success', 'Household and all members approved successfully!');
    }

    /**
     * Reject a household
     */
    public function rejectHousehold(Request $request, Household $household)
    {
        // Only secretary can reject
        if (!auth()->user()->isSecretary()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $household->update([
            'approval_status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Archive rejected household (will cascade to residents)
        $household->delete();

        AuditLog::logAction(
            'reject',
            'Household',
            $household->id,
            "Rejected and archived household: {$household->household_id}",
            null,
            ['rejection_reason' => $request->rejection_reason]
        );

        return back()->with('success', 'Household rejected and archived.');
    }

    /**
     * Change resident status
     */
    public function changeResidentStatus(Request $request, Resident $resident)
    {
        // Only secretary can change status
        if (!auth()->user()->isSecretary()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:active,reallocated,deceased',
            'status_notes' => 'nullable|string|max:500',
        ]);

        $oldStatus = $resident->status;

        $resident->update([
            'status' => $request->status,
            'status_notes' => $request->status_notes,
            'status_changed_at' => now(),
            'status_changed_by' => auth()->id(),
        ]);

        AuditLog::logAction(
            'status_change',
            'Resident',
            $resident->id,
            "Changed resident status from {$oldStatus} to {$request->status}: {$resident->full_name}",
            ['status' => $oldStatus],
            ['status' => $request->status, 'notes' => $request->status_notes]
        );

        return back()->with('success', 'Resident status updated successfully!');
    }

    /**
     * Archive a resident (soft delete)
     */
    public function archiveResident(Resident $resident)
    {
        // Only secretary can archive
        if (!auth()->user()->isSecretary()) {
            abort(403, 'Unauthorized action.');
        }

        $residentName = $resident->full_name;
        $resident->delete();

        AuditLog::logAction(
            'archive',
            'Resident',
            $resident->id,
            "Archived resident: {$residentName}"
        );

        return back()->with('success', 'Resident archived successfully!');
    }

    /**
     * Archive a household (soft delete)
     */
    public function archiveHousehold(Household $household)
    {
        // Only secretary can archive
        if (!auth()->user()->isSecretary()) {
            abort(403, 'Unauthorized action.');
        }

        $householdId = $household->household_id;
        $household->delete();

        AuditLog::logAction(
            'archive',
            'Household',
            $household->id,
            "Archived household: {$householdId}"
        );

        return back()->with('success', 'Household archived successfully!');
    }

    /**
     * Show archived records
     */
    public function archived()
    {
        // Only secretary can view archived
        if (!auth()->user()->isSecretary()) {
            abort(403, 'Unauthorized action.');
        }

        $archivedResidents = Resident::onlyTrashed()
            ->with(['household' => function($query) {
                $query->withTrashed();
            }])
            ->latest('deleted_at')
            ->paginate(15, ['*'], 'residents');

        $archivedHouseholds = Household::onlyTrashed()
            ->with(['head' => function($query) {
                $query->withTrashed();
            }])
            ->latest('deleted_at')
            ->paginate(15, ['*'], 'households');

        return view('approvals.archived', compact('archivedResidents', 'archivedHouseholds'));
    }

    /**
     * Restore archived resident
     */
    public function restoreResident($id)
    {
        // Only secretary can restore
        if (!auth()->user()->isSecretary()) {
            abort(403, 'Unauthorized action.');
        }

        $resident = Resident::onlyTrashed()->findOrFail($id);
        $resident->restore();

        AuditLog::logAction(
            'restore',
            'Resident',
            $resident->id,
            "Restored resident: {$resident->full_name}"
        );

        return back()->with('success', 'Resident restored successfully!');
    }

    /**
     * Restore archived household
     */
    public function restoreHousehold($id)
    {
        // Only secretary can restore
        if (!auth()->user()->isSecretary()) {
            abort(403, 'Unauthorized action.');
        }

        $household = Household::onlyTrashed()->findOrFail($id);
        $household->restore();

        // Also restore all residents in the household
        Resident::onlyTrashed()
            ->where('household_id', $household->id)
            ->restore();

        AuditLog::logAction(
            'restore',
            'Household',
            $household->id,
            "Restored household: {$household->household_id}"
        );

        return back()->with('success', 'Household and all members restored successfully!');
    }

    /**
     * Permanently delete a resident
     */
    public function deleteResident($id)
    {
        // Only secretary can permanently delete
        if (!auth()->user()->isSecretary()) {
            abort(403, 'Unauthorized action.');
        }

        $resident = Resident::onlyTrashed()->findOrFail($id);
        $residentName = $resident->full_name;
        
        // Permanently delete (forceDelete)
        $resident->forceDelete();

        AuditLog::logAction(
            'force_delete',
            'Resident',
            $id,
            "Permanently deleted resident: {$residentName}"
        );

        return back()->with('success', 'Resident permanently deleted!');
    }

    /**
     * Permanently delete a household
     */
    public function deleteHousehold($id)
    {
        // Only secretary can permanently delete
        if (!auth()->user()->isSecretary()) {
            abort(403, 'Unauthorized action.');
        }

        $household = Household::onlyTrashed()->findOrFail($id);
        $householdId = $household->household_id;
        
        // Permanently delete all residents in the household first
        Resident::onlyTrashed()
            ->where('household_id', $household->id)
            ->forceDelete();
        
        // Then permanently delete the household
        $household->forceDelete();

        AuditLog::logAction(
            'force_delete',
            'Household',
            $id,
            "Permanently deleted household and all members: {$householdId}"
        );

        return back()->with('success', 'Household and all members permanently deleted!');
    }
}

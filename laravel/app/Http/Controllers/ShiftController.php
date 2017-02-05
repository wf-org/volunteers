<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Requests\ShiftRequest;
use App\Models\Event;
use App\Models\Department;
use App\Models\ShiftData;
use App\Models\Slot;

use App\Events\EventChanged;

class ShiftController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Display list of shifts in an event
    public function listShifts(Request $request, Event $event)
    {
        $this->authorize('create-shift');
        return view('pages/shift/list', compact('event'));
    }

    // Display shift creation page
    public function createForm(Request $request, Event $event)
    {
        $this->authorize('create-shift');
        return view('pages/shift/create', compact('event'));
    }

    // Create a new shift
    public function create(ShiftRequest $request)
    {
        $this->authorize('create-shift');
        $input = $request->all();
        $department = Department::find($input['department_id']);

        if(isset($input['roles']))
        {
            // Convert roles into JSON
            $input['roles'] = json_encode($input['roles']);

            // Check if the current roles match the department roles
            if($input['roles'] == $department->roles)
            {
                // Unset the roles, use department as default instead
                unset($input['roles']);
            }
        }

        $input['event_id'] = $department->event->id;
        $shift = ShiftData::create($input);

        $request->session()->flash('success', 'Your shift has been created.');
        return redirect('/event/' . $department->event->id);
    }

    // View form to edit an existing shift
    public function editForm(Request $request, ShiftData $shift)
    {
        $this->authorize('edit-shift');
        return view('pages/shift/edit', compact('shift'));
    }

    // Save changes to an existing shift
    public function edit(ShiftRequest $request, ShiftData $shift)
    {
        $this->authorize('edit-shift');
        $input = $request->all();
        $department = Department::find($input['department_id']);

        // Convert roles into JSON
        $input['roles'] = json_encode($input['roles']);

        // Check if the current roles match the department roles
        if($input['roles'] == $department->roles)
        {
            // Unset the roles, use department as default instead
            unset($input['roles']);
        }

        $shift->update($input);
        
        $request->session()->flash('success', 'Shift has been updated.');
        return redirect('/event/' . $shift->event->id);
    }

    // View confirmation page before deleting a shift
    public function deleteForm(Request $request, ShiftData $shift)
    {
        $this->authorize('delete-shift');
        return view('pages/shift/delete', compact('shift'));
    }

    // Delete a shift
    public function delete(Request $request, ShiftData $shift)
    {
        $this->authorize('delete-shift');
        $event = $shift->event;
        $shift->delete();

        $request->session()->flash('success', 'Shift has been deleted.');
        return redirect('/event/' . $event->id);
    }
}

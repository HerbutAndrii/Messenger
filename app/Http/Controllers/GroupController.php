<?php

namespace App\Http\Controllers;

use App\Http\Requests\Groups\StoreRequest;
use App\Http\Requests\Groups\UpdateRequest;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $groups = $request->user()->groups()->get();

        return view('groups.index', compact('groups'));
    }

    public function show(Group $group) 
    {
        $this->authorize('view', $group);

        $messages = $group->messages()->get();

        return view('groups.show', compact('group', 'messages'));
    }

    public function create(Request $request)
    {
        $contacts = $request->user()->contacts()->get();

        return view('groups.create', compact('contacts'));
    }

    public function store(StoreRequest $request)
    {
        $group = $request->user()->groups()->create([
            'name' => $request->name,
            'user_id' => $request->user()->id
        ]);

        if($request->hasFile('avatar')) {
            $fileName = $request->file('avatar')->hashName();
            $request->file('avatar')->storeAs('public/avatars', $fileName);
            $group->avatar = $fileName;
        } else {
            Storage::copy('/public/defaults/default-avatar.jpg', '/public/avatars/default-avatar.jpg');
        }

        $group->save();

        if($request->has('users')) {
            $group->users()->attach($request->users);
        }

        return redirect(route('groups.index'));
    }

    public function edit(Request $request, Group $group)
    {
        $contacts = $request->user()->contacts()->get();

        return view('groups.edit', compact('group', 'contacts'));
    }

    public function update(UpdateRequest $request, Group $group)
    {
        $this->authorize('update', $group);
        
        $group->fill(['name' => $request->name]);

        if($request->hasFile('avatar')) {
            $fileName = $request->file('avatar')->hashName();
            $request->file('avatar')->storeAs('public/avatars', $fileName);
            $group->avatar = $fileName;
        }

        $group->save();

        if($request->has('users')) {
            $group->users()->sync($request->users);
            $group->users()->attach($request->user());
        } else {
            $group->users()->detach();
            $group->users()->attach($request->user());
        }

        return redirect(route('groups.index'));
    }

    public function showMembers(Group $group)
    {
        $members = $group->users;

        return view('groups.members', compact('members', 'group'));
    }

    public function removeMember(Group $group, User $user)
    {
        $this->authorize('delete', $group);

        $group->users()->detach($user);

        return response()->json(['success' => true]);
    }

    public function leave(Request $request, Group $group)
    {
        $group->users()->detach($request->user());

        if($group->users()->count() == 0) {
            $group->delete();
        }

        if($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect(route('groups.index'));
    }

    public function destroy(Group $group)
    {
        $this->authorize('delete', $group);

        $group->delete();

        return redirect(route('groups.index'));
    }
}

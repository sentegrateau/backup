<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->query();
        $rooms = Room::query();

        if (!empty($query) && !empty($query['sort']) && !empty($query['sortName'])) {
            if ($query['sortName']) {
                $rooms = $rooms->orderBy($query['sortName'], ($query['sort'] == 'asc') ? 'asc' : 'desc');
            }
        }
        $rooms = $rooms->paginate(15);
        return view('admin.room.index')->with(['rooms' => $rooms]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.room.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, array(
            'name' => 'required|max:200',
            'description' => 'required|max:2000',
        ));
        $data = $request->all();
        $room = new Room();
        $room->name = $data['name'];
        if ($request->hasfile('image')) {
            $name = 'room_' . time() . $request->file('image')->getClientOriginalName();

            Storage::disk('room_uploads')->put($name, file_get_contents($request->file('image')->getRealPath()));
            $room->img = $name;
        }

        $room->partner_id = 1;
        $room->description = $data['description'];
        $room->save();
        Session::flash('success', 'Room has been created!');
        return redirect()->route('room.index');

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $room = Room::where('id', '=', $id)->first();
        return view('admin.room.edit')->with(['room' => $room]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, array(
            'name' => 'required|unique:packages|max:255',
            'description' => 'required|max:2000',
        ));

        $data = $request->all();
        $room = Room::find($id);
        $room->name = $data['name'];
        $room->description = $data['description'];
        if ($request->hasfile('image')) {
            $this->deleteImagesWithThumbs($room->img);
            $name = 'room_' . time() . $request->file('image')->getClientOriginalName();
            Storage::disk('room_uploads')->put($name, file_get_contents($request->file('image')->getRealPath()));
            $room->img = $name;
        }
        $room->save();
        Session::flash('success', 'Room has been updated!');
        return redirect()->route('room.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $this->deleteMethod($id);
        return redirect()->route('room.index');
    }

    public function deleteMethod($id)
    {
        $room = Room::where('id', $id)->first();
        $room->delete();
    }

    public function activeDeactivate(Request $request, $id,$field_name)
    {
        $mt = Room::where('id', $id)->first();
        $mt->update([$field_name => ($mt[$field_name] == '1' ? '0' : '1')]);
        return redirect()->back()->with('success', "Room " . (($mt[$field_name]) ? 'Activated' : 'Deactivated') . " Successfully.");
    }

    public function deleteImagesWithThumbs($img)
    {
        Storage::disk('room_uploads')->delete($img);
    }
}

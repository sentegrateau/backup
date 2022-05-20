<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeOwner;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class HomeOwnerController extends Controller
{

    public $route = 'home-owner-settings';

    public $title = 'Function';

    public $model;

    public function __construct()
    {
        $this->model = new HomeOwner();
        view()->composer('*', function ($view) {
            $title = $this->title;
            $routeName = $this->route;

            $view->with(compact('title', 'routeName'));
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = $request->query();
        $rooms = $this->model->query();



        if (!empty($query) && !empty($query['sort']) && !empty($query['sortName'])) {
            if ($query['sortName']) {
                $rooms = $rooms->orderBy($query['sortName'], ($query['sort'] == 'asc') ? 'asc' : 'desc');
            }
        }
        $rooms = $rooms->paginate(15);
        return view('admin.' . $this->route . '.index')->with(['rooms' => $rooms]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin.' . $this->route . '.add');
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
        $room = $this->model;
        $room->name = $data['name'];
        if ($request->hasfile('image')) {
            $name = 'room_' . time() . $request->file('image')->getClientOriginalName();

            Storage::disk('home_owner_uploads')->put($name, file_get_contents($request->file('image')->getRealPath()));
            $room->img = $name;
        }

        $room->description = $data['description'];
        $room->save();
        Session::flash('success', $this->title . ' has been created!');
        return redirect()->route($this->route . '.index');

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
        $room = $this->model->where('id', '=', $id)->first();
        return view('admin.' . $this->route . '.edit')->with(['room' => $room]);
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
        $room = $this->model->find($id);
        $room->name = $data['name'];
        $room->description = $data['description'];
        if ($request->hasfile('image')) {
            $this->deleteImagesWithThumbs($room->img);
            $name = 'room_' . time() . $request->file('image')->getClientOriginalName();
            Storage::disk('home_owner_uploads')->put($name, file_get_contents($request->file('image')->getRealPath()));
            $room->img = $name;
        }
        $room->save();
        Session::flash('success', $this->title . ' has been updated!');
        return redirect()->route($this->route . '.index');
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
        return redirect()->back();
    }

    public function deleteMethod($id)
    {
        $room = $this->model->where('id', $id)->first();
        $this->deleteImagesWithThumbs($room->img);
        $room->delete();
    }

    public function activeDeactivate(Request $request, $id, $field_name)
    {
        $mt = $this->model->where('id', $id)->first();
        $mt->update([$field_name => ($mt[$field_name] == '1' ? '0' : '1')]);
        return redirect()->back()->with('success', $this->title . ' ' . (($mt[$field_name]) ? 'Activated' : 'Deactivated') . " Successfully.");
    }

    public function deleteImagesWithThumbs($img)
    {
        Storage::disk('home_owner_uploads')->delete($img);
    }

    public function relations()
    {
       return view('admin.'.$this->route.'.relations');
    }


}

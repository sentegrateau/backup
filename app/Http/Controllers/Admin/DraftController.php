<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Draft;
use App\Models\DraftItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DraftController extends Controller
{
    /**
     * @param Request $request
     * @return View
     */
    public $route = 'drafts';

    public $title = 'Quotation';

    public function __construct()
    {
        view()->composer('*', function ($view) {
            $title = $this->title;
            $routeName = $this->route;
            $view->with(compact('title', 'routeName'));
        });
    }

    public function index(Request $request)
    {
        $drafts = Draft::where('category', 'quotation')->with(['user'])->orderBy('created_at', 'desc')->paginate(20);
        //$this->pr($drafts->toArray(), 1);
        return view('admin.' . $this->route . '.index')->with(['drafts' => $drafts]);
    }


    public function show($id)
    {
        $draft = Draft::where('id', $id)->with(['user', 'items.package', 'items.room', 'items.device'])->first();
        $draftArr =  $draft->toArray();
        //   $this->pr($draftArr);die;
        $packages = [];
        foreach($draftArr['items'] as $arr){
            if(!array_key_exists($arr['package']['id'],$packages)){
                $packages[$arr['package']['id']] = $arr['package']['name'];

            }
        }
       /// $this->pr($packages,1);die;
        return view('admin.' . $this->route . '.show')->with(['draft' => $draft,'packages' => $packages]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return View
     */
    public function edit($id)
    {
        $draft = Draft::where('id', '=', $id)->first();
        return view('admin.' . $this->route . '.edit')->with(['draft' => $draft]);
    }

    public function deleteItem($id)
    {
        DraftItem::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Deleted Item');
    }

    public function saveItemQty(Request $request)
    {
        echo "<pre>";
        print_r($request->all());
        $data = $request->all();
        foreach ($data['qty'] as $id => $val) {
            DraftItem::where('id', $id)->update(['quantity' => $val]);
        }
        return redirect()->back()->with('success', 'Updated Item');
    }


    public function update(Request $request, $id)
    {
        $data = $request->all();
        $draft = Draft::find($id);
        $draft->validity = $data['validity'];
        $draft->save();
        return redirect()->back()->with('success', 'Updated Draft');
    }

    public function delete($id)
    {
        DraftItem::where('draft_id', $id)->delete();

        Draft::where('id', $id)->delete();

        return redirect()->back()->with('success', 'Deleted Item');
    }



}

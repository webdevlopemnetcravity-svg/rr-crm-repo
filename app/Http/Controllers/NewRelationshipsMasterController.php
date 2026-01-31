<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewRelationshipsMaster;
use Illuminate\Http\Request;

class NewRelationshipsMasterController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.newRelationshipsMaster';
        $this->activeSettingMenu = 'new_relationships_master';
        $this->middleware(function ($request, $next) {
            abort_403(!in_array('admin', user_roles()));
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->relationships = NewRelationshipsMaster::all();

        return view('new-relationships-master.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('new-relationships-master.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $relationship = new NewRelationshipsMaster();
        $relationship->company_id = company()->id;
        $relationship->name = $request->name;

        $relationship->save();

        return Reply::success(__('messages.recordSaved'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->relationship = NewRelationshipsMaster::findOrFail($id);
        return view('new-relationships-master.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $relationship = NewRelationshipsMaster::findOrFail($id);
        $relationship->name = $request->name;
        $relationship->save();

        return Reply::success(__('messages.updateSuccess'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $relationship = NewRelationshipsMaster::findOrFail($id);
        $relationship->delete();

        return Reply::success(__('messages.deleteSuccess'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewHighestQualificationMaster;
use Illuminate\Http\Request;

class NewHighestQualificationMasterController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.newHighestQualificationMaster';
        $this->activeSettingMenu = 'new_highest_qualification_master';
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
        $this->qualifications = NewHighestQualificationMaster::all();

        return view('new-highest-qualification-master.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('new-highest-qualification-master.create');
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

        $qualification = new NewHighestQualificationMaster();
        $qualification->company_id = company()->id;
        $qualification->name = $request->name;

        $qualification->save();

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
        $this->qualification = NewHighestQualificationMaster::findOrFail($id);
        return view('new-highest-qualification-master.edit', $this->data);
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

        $qualification = NewHighestQualificationMaster::findOrFail($id);
        $qualification->name = $request->name;
        $qualification->save();

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
        $qualification = NewHighestQualificationMaster::findOrFail($id);
        $qualification->delete();

        return Reply::success(__('messages.deleteSuccess'));
    }
}

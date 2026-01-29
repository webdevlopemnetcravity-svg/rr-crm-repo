<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewPassportStatusMaster;
use Illuminate\Http\Request;

class NewPassportStatusMasterController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.newPassportStatusMaster';
        $this->activeSettingMenu = 'new_passport_status_master';
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
        $this->passportStatuses = NewPassportStatusMaster::all();

        return view('new-passport-status-master.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('new-passport-status-master.create');
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

        $passportStatus = new NewPassportStatusMaster();
        $passportStatus->company_id = company()->id;
        $passportStatus->name = $request->name;

        $passportStatus->save();

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
        $this->passportStatus = NewPassportStatusMaster::findOrFail($id);
        return view('new-passport-status-master.edit', $this->data);
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

        $passportStatus = NewPassportStatusMaster::findOrFail($id);
        $passportStatus->name = $request->name;
        $passportStatus->save();

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
        $passportStatus = NewPassportStatusMaster::findOrFail($id);
        $passportStatus->delete();

        return Reply::success(__('messages.deleteSuccess'));
    }
}

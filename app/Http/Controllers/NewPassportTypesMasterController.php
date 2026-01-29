<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewPassportTypesMaster;
use Illuminate\Http\Request;

class NewPassportTypesMasterController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.newPassportTypesMaster';
        $this->activeSettingMenu = 'new_passport_types_master';
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
        $this->passportTypes = NewPassportTypesMaster::all();

        return view('new-passport-types-master.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('new-passport-types-master.create');
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

        $passportType = new NewPassportTypesMaster();
        $passportType->company_id = company()->id;
        $passportType->name = $request->name;

        $passportType->save();

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
        $this->passportType = NewPassportTypesMaster::findOrFail($id);
        return view('new-passport-types-master.edit', $this->data);
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

        $passportType = NewPassportTypesMaster::findOrFail($id);
        $passportType->name = $request->name;
        $passportType->save();

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
        $passportType = NewPassportTypesMaster::findOrFail($id);
        $passportType->delete();

        return Reply::success(__('messages.deleteSuccess'));
    }
}

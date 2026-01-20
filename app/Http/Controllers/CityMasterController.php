<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewCityMaster;
use App\Models\NewStateMaster;
use Illuminate\Http\Request;

class CityMasterController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.city';
        $this->activeSettingMenu = 'new_country_master';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->states = NewStateMaster::with('country')->get();
        return view('new-country-master.create-city-modal', $this->data);
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
            'state_id' => 'required|exists:new_state_master,id',
            'name' => 'required|string|max:255'
        ]);

        $city = new NewCityMaster();
        $city->company_id = company()->id;
        $city->state_id = $request->state_id;
        $city->name = $request->name;
        $city->save();

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
        $this->city = NewCityMaster::findOrFail($id);
        $this->states = NewStateMaster::with('country')->get();
        return view('new-country-master.edit-city-modal', $this->data);
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
            'state_id' => 'required|exists:new_state_master,id',
            'name' => 'required|string|max:255'
        ]);

        $city = NewCityMaster::findOrFail($id);
        $city->state_id = $request->state_id;
        $city->name = $request->name;
        $city->save();

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
        NewCityMaster::destroy($id);

        return Reply::success(__('messages.deleteSuccess'));
    }
}

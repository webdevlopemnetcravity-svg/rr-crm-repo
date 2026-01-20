<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewStateMaster;
use App\Models\NewCountryMaster;
use Illuminate\Http\Request;

class StateMasterController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.state';
        $this->activeSettingMenu = 'new_country_master';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->countries = NewCountryMaster::all();
        return view('new-country-master.create-state-modal', $this->data);
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
            'country_id' => 'required|exists:new_countery_master,id',
            'name' => 'required|string|max:255'
        ]);

        $state = new NewStateMaster();
        $state->company_id = company()->id;
        $state->country_id = $request->country_id;
        $state->name = $request->name;
        $state->save();

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
        $this->state = NewStateMaster::findOrFail($id);
        $this->countries = NewCountryMaster::all();
        return view('new-country-master.edit-state-modal', $this->data);
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
            'country_id' => 'required|exists:new_countery_master,id',
            'name' => 'required|string|max:255'
        ]);

        $state = NewStateMaster::findOrFail($id);
        $state->country_id = $request->country_id;
        $state->name = $request->name;
        $state->save();

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
        NewStateMaster::destroy($id);

        return Reply::success(__('messages.deleteSuccess'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewCountryMaster;
use Illuminate\Http\Request;

class CountryController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.country';
        $this->activeSettingMenu = 'new_country_master';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('new-country-master.create-country-modal', $this->data);
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

        $country = new NewCountryMaster();
        $country->company_id = company()->id;
        $country->name = $request->name;
        $country->save();

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
        $this->country = NewCountryMaster::findOrFail($id);
        return view('new-country-master.edit-country-modal', $this->data);
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

        $country = NewCountryMaster::findOrFail($id);
        $country->name = $request->name;
        $country->save();

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
        NewCountryMaster::destroy($id);

        return Reply::success(__('messages.deleteSuccess'));
    }
}

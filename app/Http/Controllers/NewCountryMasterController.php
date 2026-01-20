<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewCountryMaster;
use App\Models\NewStateMaster;
use App\Models\NewCityMaster;
use Illuminate\Http\Request;

class NewCountryMasterController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.newCountryMaster';
        $this->activeSettingMenu = 'new_country_master';
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
        $this->countries = NewCountryMaster::all();
        $this->states = NewStateMaster::with('country')->get();
        $this->cities = NewCityMaster::with('state.country')->get();

        $this->view = 'new-country-master.ajax.country';

        $tab = request('tab');

        switch ($tab) {
        case 'state':
            $this->pageTitle = 'app.menu.state';
            $this->view = 'new-country-master.ajax.state';
            break;
        case 'city':
            $this->pageTitle = 'app.menu.city';
            $this->view = 'new-country-master.ajax.city';
            break;
        default:
            $this->pageTitle = 'app.menu.country';
            $this->view = 'new-country-master.ajax.country';
            break;
        }

        $this->activeTab = $tab ?: 'country';

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle, 'activeTab' => $this->activeTab]);
        }

        return view('new-country-master.index', $this->data);
    }

}

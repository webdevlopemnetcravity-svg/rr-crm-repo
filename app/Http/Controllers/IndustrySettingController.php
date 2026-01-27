<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewIndustryMaster;
use App\Models\NewSectorMaster;
use Illuminate\Http\Request;

class IndustrySettingController extends AccountBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.industrySettings';
        $this->activeSettingMenu = 'industry_settings';
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
        $this->industries = NewIndustryMaster::where(function ($query) {
            $query->where('company_id', company()->id)
                ->orWhereNull('company_id');
        })->orderBy('name')->get();

        $this->sectors = NewSectorMaster::with('industry')
            ->where(function ($query) {
                $query->where('company_id', company()->id)
                    ->orWhereNull('company_id');
            })
            ->orderBy('name')
            ->get();

        $this->view = 'industry-settings.ajax.industry';

        $tab = request('tab');

        switch ($tab) {
        case 'sector':
            $this->pageTitle = 'app.menu.sector';
            $this->view = 'industry-settings.ajax.sector';
            break;
        default:
            $this->pageTitle = 'app.menu.industry';
            $this->view = 'industry-settings.ajax.industry';
            break;
        }

        $this->activeTab = $tab ?: 'industry';

        if (request()->ajax()) {
            $html = view($this->view, $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'html' => $html, 'title' => $this->pageTitle, 'activeTab' => $this->activeTab]);
        }

        return view('industry-settings.index', $this->data);
    }
}

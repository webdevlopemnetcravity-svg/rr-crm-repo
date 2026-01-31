<?php

namespace App\Http\Controllers;

use App\Helper\Reply;
use App\Models\NewCountryMaster;
use App\Models\NewStateMaster;
use App\Models\NewCityMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    /**
     * Import countries from storage/countries.csv (required column: name).
     */
    public function importCountriesFromCsv(Request $request)
    {
        $path = storage_path('countries.csv');
        if (!file_exists($path)) {
            return response()->json(Reply::error(__('File not found:') . ' storage/countries.csv'));
        }
        $companyId = company()->id;
        $created = 0;
        $header = null;
        $handle = fopen($path, 'r');
        if ($handle === false) {
            return response()->json(Reply::error(__('Could not open file.')));
        }
        try {
            DB::beginTransaction();
            while (($row = fgetcsv($handle)) !== false) {
                if ($header === null) {
                    $header = array_map('trim', $row);
                    continue;
                }
                $row = array_slice(array_pad($row, count($header), ''), 0, count($header));
                $assoc = array_combine($header, $row);
                if ($assoc === false) {
                    continue;
                }
                $name = trim($assoc['name'] ?? '');
                if ($name === '') {
                    continue;
                }
                $country = NewCountryMaster::withoutGlobalScopes()->firstOrCreate(
                    ['company_id' => $companyId, 'name' => $name],
                    ['company_id' => $companyId, 'name' => $name]
                );
                if ($country->wasRecentlyCreated) {
                    $created++;
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return response()->json(Reply::error($e->getMessage()));
        }
        fclose($handle);
        return response()->json(Reply::success(__('messages.recordSaved') . ' — ' . $created . ' ' . __('app.menu.country')));
    }

    /**
     * Import states from storage/states.csv (required columns: name, country_name).
     */
    public function importStatesFromCsv(Request $request)
    {
        $path = storage_path('states.csv');
        if (!file_exists($path)) {
            return response()->json(Reply::error(__('File not found:') . ' storage/states.csv'));
        }
        $companyId = company()->id;
        $created = 0;
        $header = null;
        $handle = fopen($path, 'r');
        if ($handle === false) {
            return response()->json(Reply::error(__('Could not open file.')));
        }
        try {
            DB::beginTransaction();
            while (($row = fgetcsv($handle)) !== false) {
                if ($header === null) {
                    $header = array_map('trim', $row);
                    continue;
                }
                $row = array_slice(array_pad($row, count($header), ''), 0, count($header));
                $assoc = array_combine($header, $row);
                if ($assoc === false) {
                    continue;
                }
                $name = trim($assoc['name'] ?? '');
                $countryName = trim($assoc['country_name'] ?? '');
                if ($name === '' || $countryName === '') {
                    continue;
                }
                $country = NewCountryMaster::withoutGlobalScopes()->where('company_id', $companyId)->where('name', $countryName)->first();
                if (!$country) {
                    continue;
                }
                $state = NewStateMaster::withoutGlobalScopes()->firstOrCreate(
                    ['company_id' => $companyId, 'country_id' => $country->id, 'name' => $name],
                    ['company_id' => $companyId, 'country_id' => $country->id, 'name' => $name]
                );
                if ($state->wasRecentlyCreated) {
                    $created++;
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return response()->json(Reply::error($e->getMessage()));
        }
        fclose($handle);
        return response()->json(Reply::success(__('messages.recordSaved') . ' — ' . $created . ' ' . __('app.menu.state')));
    }

    /**
     * Import cities from storage/cities.csv (required columns: name, state_name, country_name).
     */
    public function importCitiesFromCsv(Request $request)
    {
        $path = storage_path('cities.csv');
        if (!file_exists($path)) {
            return response()->json(Reply::error(__('File not found:') . ' storage/cities.csv'));
        }
        $companyId = company()->id;
        $created = 0;
        $header = null;
        $handle = fopen($path, 'r');
        if ($handle === false) {
            return response()->json(Reply::error(__('Could not open file.')));
        }
        try {
            DB::beginTransaction();
            while (($row = fgetcsv($handle)) !== false) {
                if ($header === null) {
                    $header = array_map('trim', $row);
                    continue;
                }
                $row = array_slice(array_pad($row, count($header), ''), 0, count($header));
                $assoc = array_combine($header, $row);
                if ($assoc === false) {
                    continue;
                }
                $name = trim($assoc['name'] ?? '');
                $stateName = trim($assoc['state_name'] ?? '');
                $countryName = trim($assoc['country_name'] ?? '');
                if ($name === '' || $stateName === '' || $countryName === '') {
                    continue;
                }
                $country = NewCountryMaster::withoutGlobalScopes()->where('company_id', $companyId)->where('name', $countryName)->first();
                if (!$country) {
                    continue;
                }
                $state = NewStateMaster::withoutGlobalScopes()->where('company_id', $companyId)->where('country_id', $country->id)->where('name', $stateName)->first();
                if (!$state) {
                    continue;
                }
                $city = NewCityMaster::withoutGlobalScopes()->firstOrCreate(
                    ['company_id' => $companyId, 'state_id' => $state->id, 'name' => $name],
                    ['company_id' => $companyId, 'state_id' => $state->id, 'name' => $name]
                );
                if ($city->wasRecentlyCreated) {
                    $created++;
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return response()->json(Reply::error($e->getMessage()));
        }
        fclose($handle);
        return response()->json(Reply::success(__('messages.recordSaved') . ' — ' . $created . ' ' . __('app.menu.city')));
    }

}

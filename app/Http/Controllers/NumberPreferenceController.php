<?php

namespace App\Http\Controllers;

use App\Models\Number;
use App\Models\NumberPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class NumberPreferenceController extends Controller
{
    /**
     * Contains the error messages to be sent to the frontend
     */
    private $error_messages = [];

    /**
     * Validates the user form input
     */
    private function validateForm(Request $request)
    {
        // perform validation
        return $request->validate([
            'name' => 'required',
            'value' => 'required',
            'number_id' => 'required'
        ]);
    }

    /**
     * Checks if a number id is ok
     */
    public static function checkNumberId($number_id)
    {
        $number = Number::find($number_id);
        if(is_null($number)) {
            return false;
        }
        return self::checkNumber($number);
    }

    /**
     * Checks if a customer is ok
     */
    public static function checkNumber(Number $number)
    {
        return NumberController::checkCustomer($number->customer);
    }

    /**
     * Return the breadcrumb
     */
    public static function breadcrumbNumber(Number $number, array $extras = [])
    {
        return array_merge(
            NumberController::breadcrumbNumber($number),
            [
                [
                    'name' => 'Number preferences',
                    'href' => URL::route('number.show', $number->id)
                ]
            ],
            $extras
        );
    }

    /**
     * Return the breadcrumb with a number preference
     */
    public static function breadcrumbNumberPreference(NumberPreference $numberPreference, array $extras = [])
    {
        return array_merge(
            self::breadcrumbNumber($numberPreference->number),
            [
                [
                    'name' => $numberPreference->name,
                    'href' => URL::route('number.show', $numberPreference->number->id)
                ]
            ],
            $extras
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect(URL::route('customer.index'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $number_id = null)
    {
        $number = $number_id ?? $request->query('number_id');
        $number = Number::find($number);
        if(!$number || !$this->checkNumber($number)) {
            // either the number was not found or does not belong to the current user
            return redirect(URL::route('customer.index'));
        }
        return \Inertia\Inertia::render('NumberPreference/Create', [
            'number' => $number,
            'store_url' => URL::route('number-preference.store'),
            'back_url' => URL::route('number.show', $number->id),
            'error_messages' => (object)$this->error_messages,
            'breadcrumb' => $this->breadcrumbNumber($number, [['name' => 'Add']])

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try
        {
            $data = $this->validateForm($request);
        }
        catch(\Exception $ex)
        {
            // validation failed
            $this->error_messages = array_merge($this->error_messages, $ex->validator->messages()->messages());
            return $this->create($request, $request->post('number_id'));
        }

        if(!$this->checkNumberId($data["number_id"]))
        {
            return redirect(URL::route('customer.index'));
        }

        // validation was successful
        NumberPreference::create($data);

        return redirect(URL::route('number.show', $data['number_id']));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NumberPreference  $numberPreference
     * @return \Illuminate\Http\Response
     */
    public function show(NumberPreference $numberPreference)
    {
        return redirect(URL::route('number.show', $numberPreference->number_id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NumberPreference  $numberPreference
     * @return \Illuminate\Http\Response
     */
    public function edit(NumberPreference $numberPreference)
    {
        if(!$this->checkNumber($numberPreference->number))
        {
            return redirect(URL::route('customer.index'));
        }
        return \Inertia\Inertia::render('NumberPreference/Edit', [
            'number_preference' => $numberPreference,
            'number' => $numberPreference->number,
            'update_url' => URL::route('number-preference.update', $numberPreference->id),
            'back_url' => URL::route('number.show', $numberPreference->number->id),
            'error_messages' => (object)$this->error_messages,
            'breadcrumb' => $this->breadcrumbNumber($numberPreference->number, [['name' => 'Edit']])
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NumberPreference  $numberPreference
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NumberPreference $numberPreference)
    {
        if(!$this->checkNumber($numberPreference->number))
        {
            return redirect(URL::route('customer.index'));
        }
        try
        {
            // perform validation
            $data = $this->validateForm($request);
        }
        catch(\Exception $ex)
        {
            // validation failed
            $this->error_messages = array_merge($this->error_messages, $ex->validator->messages()->messages());
            return $this->edit($numberPreference);
        }

        // validation was successful
        $numberPreference->update($data);
        return redirect(URL::route('number.show', $numberPreference->number->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NumberPreference  $numberPreference
     * @return \Illuminate\Http\Response
     */
    public function destroy(NumberPreference $numberPreference)
    {
        if($this->checkNumber($numberPreference->number))
        {
            $numberPreference->delete();
        }
        return redirect(URL::route('number.show', $numberPreference->number->id));
    }
}

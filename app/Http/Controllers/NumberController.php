<?php

namespace App\Http\Controllers;

use App\Models\Number;
use App\Models\Customer;
use App\Models\NumberPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class NumberController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

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
            'number' => 'required|min:8|max:14',
            'status' => 'required|integer',
            'customer_id' => 'required|integer'
        ]);
    }

    /**
     * Checks if a customer id is ok
     */
    public static function checkCustomerId($customer_id)
    {
        $customer = Customer::find($customer_id);
        if(is_null($customer)) {
            return false;
        }
        return self::checkCustomer($customer);
    }

    /**
     * Checks if a customer is ok
     */
    public static function checkCustomer(Customer $customer)
    {
        return CustomerController::checkUser($customer->user);
    }

    /**
     * Return the breadcrumb with a number
     */
    public static function breadcrumbCustomer(Customer $customer, array $extras = [])
    {
        return array_merge(
            CustomerController::breadcrumbCustomer($customer),
            [
                [
                    'name' => 'Numbers',
                    'href' => URL::route('customer.show', $customer->id)
                ]
            ],
            $extras
        );
    }

    /**
     * Return the breadcrumb with a number
     */
    public static function breadcrumbNumber(Number $number, array $extras = [])
    {
        return array_merge(
            self::breadcrumbCustomer($number->customer),
            [
                [
                    'name' => $number->number,
                    'href' => URL::route('number.show', $number->id)
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
    public function create(Request $request, $customer_id = null)
    {
        $customer = $customer_id ?? $request->query('customer_id');
        $customer = Customer::find($customer);
        if(!$customer || !$this->checkCustomer($customer)) {
            // either the customer was not found or does not belong to the current user
            return redirect(URL::route('customer.index'));
        }
        return \Inertia\Inertia::render('Number/Create', [
            'customer' => $customer,
            'status_options' => (object)Number::statusOptions(),
            'store_url' => URL::route('number.store'),
            'back_url' => URL::route('customer.show', $customer->id),
            'error_messages' => (object)$this->error_messages,
            'breadcrumb' => $this->breadcrumbCustomer(($customer), [['name' => 'Add']])
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
            return $this->create($request, $request->post('customer_id'));
        }

        if(!$this->checkCustomerId($data["customer_id"]))
        {
            return redirect(URL::route('customer.index'));
        }

        // validation was successful
        $number = Number::create($data);

        // create default number preferences
        NumberPreference::create([
            'name' => 'auto_attendant',
            'value' => '1',
            'number_id' => $number->id
        ]);
        NumberPreference::create([
            'name' => 'voicemail_enabled',
            'value' => '1',
            'number_id' => $number->id
        ]);

        return redirect(URL::route('customer.show', $data['customer_id']));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Number  $number
     * @return \Illuminate\Http\Response
     */
    public function show(Number $number)
    {
        if(!$this->checkCustomerId($number->customer_id))
        {
            return redirect(URL::route('customer.index'));
        }
        return \Inertia\Inertia::render('Number/Show', [
            'customer' => $number->customer,
            'number' => $number,
            'number_preferences' => $number->numberPreferences->map(function($numberPreference) {
                return array_merge(
                    $numberPreference->attributesToArray(),
                    [
                        'destroy_url' => URL::route('number-preference.destroy', $numberPreference->id),
                        'edit_url' => URL::route('number-preference.edit', $numberPreference->id)
                    ]
                );
            }),
            'create_number_preference_url' => URL::route('number-preference.create'),
            'edit_url' => URL::route('number.edit', $number->id),
            'back_url' => URL::route('customer.show', $number->customer->id),
            'destroy_url' => URL::route('number.destroy', $number->id),
            'breadcrumb' => $this->breadcrumbNumber($number)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Number  $number
     * @return \Illuminate\Http\Response
     */
    public function edit(Number $number)
    {
        if(!$this->checkCustomer($number->customer))
        {
            return redirect(URL::route('customer.index'));
        }
        return \Inertia\Inertia::render('Number/Edit', [
            'number' => $number,
            'customer' => $number->customer,
            'status_options' => (object)Number::statusOptions(),
            'update_url' => URL::route('number.update', $number->id),
            'back_url' => URL::route('number.show', $number->id),
            'error_messages' => (object)$this->error_messages,
            'breadcrumb' => $this->breadcrumbNumber($number, [['name' => 'Edit']])
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Number  $number
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Number $number)
    {
        if(!$this->checkCustomerId($number->customer_id))
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
            return $this->edit($number);
        }

        // validation was successful
        $number->update($data);
        return redirect(URL::route('number.show', $number->id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Number  $number
     * @return \Illuminate\Http\Response
     */
    public function destroy(Number $number)
    {
        if($this->checkCustomer($number->customer))
        {
            $number->delete();
        }
        return redirect(URL::route('customer.show', $number->customer));
    }
}

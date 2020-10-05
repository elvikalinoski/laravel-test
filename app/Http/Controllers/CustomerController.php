<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class CustomerController extends Controller
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
            'name' => 'required|min:3',
            'document' => 'required|min:6|max:12',
            'status' => 'required|integer',
        ]);
    }

    /**
     * Check if the customer belongs to the user
     */
    public static function checkUser(User $user)
    {
        if($user->id !== Auth::user()->id)
        {
            return false;
        }
        return true;
    }

    /**
     * Return the breadcrumb
     */
    public static function breadcrumb(array $extras = [])
    {
        return array_merge(
            [
                [
                    'name' => 'Customers',
                    'href' => URL::route('customer.index')
                ]
            ],
            $extras
        );
    }

    /**
     * Return the breadcrumb with a customer
     */
    public static function breadcrumbCustomer(Customer $customer, array $extras = [])
    {
        return array_merge(
            self::breadcrumb(),
            [
                [
                    'name' => $customer->name,
                    'href' => URL::route('customer.show', $customer->id)
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
        $customers = Auth::user()->customers;
        return \Inertia\Inertia::render('Customer/Index', [
            'customers' => $customers->map(function($customer) {
                return array_merge(
                    $customer->attributesToArray(),
                    [
                        'edit_url' => URL::route('customer.edit', $customer->id),
                        'show_url' => URL::route('customer.show', $customer->id),
                        'destroy_url' => URL::route('customer.destroy', $customer->id),
                        'numbers_amount' => count($customer->numbers)
                    ]
                );
            }),
            'create_url' => URL::route('customer.create'),
            'error_messages' => (object)$this->error_messages,
            'breadcrumb' => $this->breadcrumb()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return \Inertia\Inertia::render('Customer/Create', [
            'status_options' => (object)Customer::statusOptions(),
            'store_url' => URL::route('customer.store'),
            'back_url' => URL::route('customer.index'),
            'error_messages' => (object)$this->error_messages,
            'breadcrumb' => $this->breadcrumb([['name' => 'Add']])
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
            $data['user_id'] = Auth::user()->id;
        }
        catch(\Exception $ex)
        {
            // validation failed
            $this->error_messages = array_merge($this->error_messages, $ex->validator->messages()->messages());
            return $this->create();
        }

        // validation was successful
        Customer::create($data);
        return $this->index();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        if(!$this->checkUser($customer->user))
        {
            return redirect(URL::route('customer.index'));
        }
        return \Inertia\Inertia::render('Customer/Show', [
            'customer' => $customer,
            'numbers' => $customer->numbers->map(function($number) {
                return array_merge(
                    $number->attributesToArray(),
                    [
                        'edit_url' => URL::route('number.edit', $number->id),
                        'show_url' => URL::route('number.show', $number->id),
                        'destroy_url' => URL::route('number.destroy', $number->id),
                        'number_preferences_amount' => count($number->numberPreferences)
                    ],
                );
            }),
            'edit_url' => URL::route('customer.edit', $customer->id),
            'back_url' => URL::route('customer.index'),
            'create_number_url' => URL::route('number.create'),
            'destroy_url' => URL::route('customer.destroy', $customer->id),
            'breadcrumb' => $this->breadcrumbCustomer($customer)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        if(!$this->checkUser($customer->user))
        {
            return redirect(URL::route('customer.index'));
        }
        return \Inertia\Inertia::render('Customer/Edit', [
            'customer' => $customer,
            'status_options' => (object)Customer::statusOptions(),
            'update_url' => URL::route('customer.update', $customer->id),
            'back_url' => URL::route('customer.show', $customer->id),
            'error_messages' => (object)$this->error_messages,
            'breadcrumb' => $this->breadcrumbCustomer($customer, [['name' => 'Edit']])
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        if(!$this->checkUser($customer->user))
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
            return $this->edit($customer);
        }

        // validation was successful
        $customer->update($data);
        return $this->show($customer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        if(!$this->checkUser($customer->user))
        {
            return redirect(URL::route('customer.index'));
        }
        $customer->delete();
        return $this->index();
    }
}

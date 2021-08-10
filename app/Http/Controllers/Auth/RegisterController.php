<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Repositories\UserRepositoryInterface;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\FrontendManage\Entities\LoginPage;
use Illuminate\Http\Request;
use App\Models\StudentParent;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    protected $userRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'phone' => 'nullable|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:5|unique:users',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ],
            [
                'name.required' => 'User name is required',
                'phone.regex' => 'Phone Number is not valid.Please enter valid phone number',
                'phone.digits' => 'Phone Number is not valid.Please enter valid phone number',
                'email.required' => 'Email is required',
                'password.required' => 'Password is required',
                'password.min' => 'Password length must be 8 characters',
            ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        if (isset($data['type']) && $data['type'] == "Instructor") {
            $role = 2;
        } else {
            $role = 3;
        }

        if (empty($data['phone'])) {
            $data['phone'] = null;
        }
        return $this->userRepository->create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'role_id' => $role,
            'language_id' => getSetting()->language_id,
            'language_code' => getSetting()->language->code ?? '19',
            'country' => getSetting()->country_id,
            'username' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function RegisterForm()
    {
        $page = LoginPage::first();
        return view(theme('auth.register'), compact('page'));
    }

    public function ParentRegisterForm()
    {
        $page = LoginPage::first();
        return view(theme('auth.register-parent'), compact('page'));
    }

    public function showRegistrationForm()
    {
        $page = LoginPage::first();
        return view(theme('auth.register'), compact('page'));
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\User
     */
    protected function ParentCreate(Request $request)
    {
        try {
            $data = StudentParent::create([
                'parent_name' => $request->parent_name,
                'parent_ic' => $request->parent_ic,
                'parent_phone_no' => $request->parent_phone_no,
                'parent_email' => $request->parent_email,
                'username' => $request->parent_email,
                'country' => getSetting()->country_id,
                'state' => $request->state,
                'district' => $request->district,
                'city' => $request->city,
                'post_code' => $request->post_code,
                'house_address' => $request->house_address,
                'student_name' => $request->student_name,
                'student_ic' => $request->student_ic,
                'school_name' => $request->school_name,
                'password' => Hash::make($request->password),
            ]);
            if ($data) {
                Toastr::success('Parent Registration Successful', 'Success');
                return redirect('login');
            }
        }catch (\Exception $e) {

            Toastr::error(trans("lang.Oops, Something Went Wrong"), trans('common.Failed'));
            return redirect()->back();
        }
    }


}

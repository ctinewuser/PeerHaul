<?php
namespace App\Repository;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Customer;
use App\Models\NotificationList;
use App\User;
use Validator;
use Illuminate\Support\Facades\Storage;

class CustomerRepository
{

    public function __construct(Customer $customer, NotificationList $notificationlist)
    {
        $this->customer = $customer;
        $this->notificationlist = $notificationlist;
    }
    public function removeCustomer($id)
    {
        return $this
            ->customer
            ->where('id', $id)->update(['status' => 2]);

    }
     public function getCustomerByEmail($email)
    {
        return $this
            ->customer
            ->where('email', $email)->first();
    }
    public function getCustomerById($id)
    {
        return $this
            ->customer
            ->where('id', $id)->first();
    }
    public function getCustomerUser()
    {
           return $this->customer->get();
    }
    public function getCustomerList()
    {
        return $this
            ->customer
            ->where('status', '=' ,'0')
            ->orWhere('status', '=' ,'1')
            ->orWhere('status', '=' ,'3')
            ->orderBy('id', 'desc')
            ->get();
    }
    public function getNotificationList()
    {
        return $this
            ->notificationlist
            ->get();
    }
    public function checkEmail($email)
    {
        return $this
            ->customer
            ->where('email', $email)->first();
    }

    public function checkPhone($phone)
    {
        return $this
            ->customer
            ->where('phone', $phone)->first();
    }
      public function checkNum($request)
    {
        return $request->validate([
         'phone' => 'required|numeric|min:10',
             
            ]);
      
       
    }
   
    public function store($request)
    {

        $numbers = md5(rand(999, 9999));
        $randomCode = mb_substr($numbers, 0, 5);

        $requestData = ['name' => ucfirst($request->name) , 'email' => $request->email, 'password' => md5($request->password) , 'show_password' => $request->password, 'phone' => $request->phone, 'fcmToken' => $request->fcmToken, 'my_referral_code' => $randomCode, 'is_company' => $request->is_company, 'company_name' => $request->company_name , 'otp' => $request->otp,];
        return $this
            ->customer
            ->insert($requestData);
    }

    public function customerLogin($request)
    {
        return $this
            ->customer
            ->where('email', $request->email)
            ->where('password', md5($request->password))
            ->first();
    }

    public function updateFCM($id, $fcm)
    {
        return $this
            ->customer
            ->where('id', $id)->update(['fcmToken' => $fcm]);
    }

    public function updateCustomerProfile($request)
    {

        $id = $request->customer_id;

        if ($request->name != '')
        {
            $req['name'] = $request->name;
        }

        if ($request->email != '')
        {
            $req['email'] = $request->email;
        }

        if ($request->phone != '')
        {
            $req['phone'] = $request->phone;
        }

        if ($request->date_of_birth != '')
        {
            $req['date_of_birth'] = $request->date_of_birth;
        }

        if ($request->address != '')
        {
            $req['address'] = $request->address;
        }

        if ($request->referred_code != '')
        {
            $req['referred_code'] = $request->referred_code;
        }

        if ($request->state != '')
        {
            $req['state'] = $request->state;
        }

        if ($request->city != '')
        {
            $req['city'] = $request->city;
        }

        if ($request->post_code != '')
        {
            $req['post_code'] = $request->post_code;
        }

        if ($request->house_no != '')
        {
            $req['house_no'] = $request->house_no;
        }

        return $this
            ->customer
            ->where('id', $request->customer_id)
            ->update($req);

    }

    public function updatePasssword($id, $password)
    {
        return $this
            ->customer
            ->where('id', $id)->update(array(
            'password' => md5($password) ,
            'show_password' => $password
        ));
    }
    
     public function blockCustomer($id)
    {
        return $this
            ->customer
            ->where('id', $id)->update(array(
            'status' =>  '3'
        ));
    }

}


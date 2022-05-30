<?php

use App\Models\Notification;
use App\Models\UserDevice;
use App\User;

    /**
     * Function :
     */
    function saveAdminNotification($data){
        try{
            $model = new Notification();
            $model->user_id = $data['user_id'];
            $model->gigs_id = isset($data['gigs_id'])?$data['gigs_id']:0;
            $model->title = $data['title'];
            $model->role  = 'admin';
            $model->message = $data['message'];
            $model->save();
        } catch (Exception $ex) {
            return false;
        }
    }

    /**
    * Send Notification for admin and vendor 
    * @param Request $request
    * @return type
    */
    function sendNotification($data){
        try{
            $model = new Notification();
            $model->user_id = $data['user_id'];
            $model->gigs_id = isset($data['gigs_id'])?$data['gigs_id']:0;
            $model->title = $data['title'];
            $model->message = $data['message'];
            if($model->save()){
                $this->pushNotificationForcustomer($data);
            }
        } catch (Exception $ex) {
            return false;
        }
    }
    
    /**
     * send notification for customer
     * @param type $data
     * @return boolean
     */
 
 
    function pushNotificationForcustomer($data) {
        if(!empty($data['user_id'])){
            foreach($data['user_id'] as $customerId){
                $userDevice = User::where('id', $customerId)->select('id','device_token')->first();
                if(!empty($userDevice['device_token'])){
                    $notifaction['user_id'] = $data['user_id'];
                    $notifaction['title']   = $data['title'];
                    $notifaction['img']     = $data['img'];
                    $notifaction['type']    = $data['type'];
                    $notifaction['title']   = $data['title'];
                    $notifaction['msg']     = $data['msg'];
                    $notifaction['user_type'] = 'user';
                    $result = Notification::create($notifaction);
                    if(!empty($result)){
                      
                        $deviceId = [$userDevice['device_token']];
                        $API_ACCESS_KEY = getenv("API_ACCESS_KEY");
                        if (!isset($API_ACCESS_KEY)) {
                            return false;
                        }
                        $orderId = (!empty($data['order_id'])) ? $data['order_id'] : '';
                        $url = 'https://fcm.googleapis.com/fcm/send';
                        if($userDevice['device_type'] == 'ios') {
                            $fields = array('registration_ids' => $deviceId,'notification' => array('sound' => 'default','body' => $messages, 'data' => array('message' => $messages, 'type' => $data['type'],'title' => $data['title'],'order_id' => $orderId)));
                        } else {
                            $fields = array('registration_ids' => $deviceId,'data' => array('message' => $messages, 'type' => $data['type'],'title' => $data['title'],'order_id' => $orderId));
                        }

                        $headers = array(
                            'Authorization: key='.$API_ACCESS_KEY,
                            'Content-Type: application/json'
                        );
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                        $result = curl_exec($ch);
                        $result = json_decode($result,TRUE);
                        Log::debug('notification check', ['data' => $result]);
                        if ($result === FALSE) {
                            die('Curl failed: ' . curl_error($ch));
                        }
                        if ($result['success'] == 1) {
                            curl_close($ch);
                        } else {
                            curl_close($ch);
                        }
                    }
                }
            }
            return true;
        }
    }

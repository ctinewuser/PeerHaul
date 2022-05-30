<?php

/**
 * Upload image
 */
function uploadImage($image, $folder = null)
{
   $destination = public_path() . '/uploads/' . $folder;
    $filename = checkFileExist($destination, $image);
    if (!$image->move($destination, $filename)) {
        return "Error in uploading file ";
    }
    return $folder = $filename;
}


/**
 * Delete image from folder
 */
function deleteImage($folder, $image)
{
    $destination = public_path() . '/uploads/' . $folder;
    $filename = $destination . '/' . $image;
    if (file_exists($filename)) {
        \File::delete($filename);
        $thumb = explode('.', $image);
        if (!empty($thumb)) {
            $thumbnail = $destination . '/thumbnail/' . $thumb[0] . '.jpg';
            if (file_exists($thumbnail)) {
                \File::delete($thumbnail);
            }
        }
    }
}


/**
 * Check file exist
 */
function checkFileExist($destination, $image)
{
    $file = $image->getClientOriginalName();
    $fileData = pathinfo($file);
    $fileOrgName = $fileData['filename'];
    $filename = preg_replace('/\s+/', '_', $fileOrgName . '_' . time() . '.' . $image->getClientOriginalExtension());
    if (!\File::exists($destination)) {
        \File::makeDirectory($destination, 0777, true, true);
    }
    $i = 1;
   
    while (\File::exists($destination . '/' . $filename)) {
        $fileNameWithoutExtenssion = basename(time() . '.' . $image->getClientOriginalName(), '.' . $image->getClientOriginalExtension());
        $filename = $fileNameWithoutExtenssion . $i . '.' . $image->getClientOriginalExtension();
        $i++;
    }
    return $filename;
}

/**
 * Get image property
 */
function getImageProperties($name, $folder)
{
    $returnData = [];
    $destination = public_path() . '/uploads/' . $folder . '/' . $name;
    if (\File::exists($destination)) {
        $data = getImageSize($destination);
        if (!empty($data)) {
            $returnData['width'] = $data[0];
            $returnData['height'] = $data[1];
        }
    }
    return $returnData;
}

/**
 * Remove image
 */
function removeImage($destination, $image)
{
    if (\File::exists($destination . '/' . $image)) {
        \File::delete($destination . '/' . $image);
        return true;
    }
    return false;
}

/**
 * Check image url and set image path
 */
function checkUserImage($image, $folder, $role = null)
{

    if ($role == 'user') {
        $src = url('public/images/default_images/default-user.jpg');
    } else {
        $src = url('public/images/default_images/default-user.jpg');
    }
    $fileName = public_path() . '/uploads/' . $folder . '/' . $image;
    if (!empty($image) && file_exists($fileName)) {
        $src = url('public' . '/uploads/' . $folder . '/' . $image);
    }
    return $src;
}

    /*
     * get image url and set image path
     */

    function getUploadedImage($image, $folder = null, $type = null) {
        $src = url('public/images/default_images/default-user.jpg');
        $fileName = public_path().'/uploads/'.$folder.'/'.$image;
        if (!empty($image) && file_exists($fileName)) {
            $src = url('public/uploads/'.$folder.'/'.$image);
        }
        return $src;
    }

function base64($data)
{
    return rtrim(strtr(base64_encode(json_encode($data)), '+/', '-_'), '=');
}

function getLatLong($address){
    //$address = "Kathmandu, Nepal";
    $url = "https://maps.google.com/maps/api/geocode/json?address=".urlencode($address)."&key=".env('GOOGLE_KEY');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $responseJson = curl_exec($ch);
    curl_close($ch);
    $response = json_decode($responseJson);
    if ($response->status == 'OK') {
        $latitude = $response->results[0]->geometry->location->lat;
        $longitude = $response->results[0]->geometry->location->lng;
        return ["latitude"=>$latitude,"longitude"=>$longitude];

        // echo 'Latitude: ' . $latitude;
        // echo '<br />';
        // echo 'Longitude: ' . $longitude;
    } else {
        return $response->status;
        //var_dump($response);
    }
}

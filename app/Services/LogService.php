<?php

namespace App\Services;
use App\Models\Log;
/**
 * Log Service
 */
class LogService
{
	public function create($user_id, $html_warna, $html_icon, $title, $message, $charge)
    {
    	$log = Log::create([
    		'user_id' => $user_id,
    		'html_warna' => $html_warna,
    		'html_icon' => $html_icon,
    		'title' => $title,
    		'message' => $message,
    		'charge' => $charge
    	]);

    	return $log;
    }
}
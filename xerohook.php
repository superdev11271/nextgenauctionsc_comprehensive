<?php

use App\Http\Controllers\XeroController;
use Illuminate\Support\Facades\Log;
require "bootapp.php";

return (new XeroController)->xeroWebHookEndPoint();

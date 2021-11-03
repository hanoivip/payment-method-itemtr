<?php

use Illuminate\Support\Facades\Route;

Route::any('/api/callback', 'Hanoivip\PaymentMethodItemtr\ItemtrController@callback');
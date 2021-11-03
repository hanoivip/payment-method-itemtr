<?php

use Illuminate\Support\Facades\Route;

Route::any('/itemtr', 'Hanoivip\PaymentMethodItemtr\ItemtrController@callback');
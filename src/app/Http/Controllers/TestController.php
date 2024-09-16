<?php

namespace App\Http\Controllers;

use App\Http\Resources\TestResource;
use App\Models\Client;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * This is Great.
     */
    public function __invoke(Request $request)
    {
        return new TestResource(Client::find(1));
    }
}

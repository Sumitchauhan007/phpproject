<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\RedirectResponse;

class ShortUrlController extends Controller
{
    public function show(string $slug): RedirectResponse
    {
        $url = Url::where('slug', $slug)->firstOrFail();

        return redirect()->away($url->destination);
    }
}

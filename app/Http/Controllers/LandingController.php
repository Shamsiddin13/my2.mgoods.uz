<?php

namespace App\Http\Controllers;

use App\Models\Landing;
use App\Models\Product;
use App\Models\Stream;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RetailCRM;

class LandingController extends Controller
{
    public function show($link)
    {
        // Fetch landing details using Eloquent
        $landing = Landing::where('article', $link)->first();

        $stream = null;
        $product = null;

        if ($landing) {
            // Fetch product details
            $product = $landing->product;
        } else {
            // Check the stream table
            $stream = Stream::where('link', $link)->first();

            if ($stream) {
                $landing = $stream->landing;
                $product = $landing ? Product::where('article', $landing->article)->first() : null;
            }
        }

        if (empty($landing)) {
            abort(404, 'Landing page not found.');
        }

        return view('landing', compact('landing', 'product', 'stream'));
    }

    public function send(Request $request)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'source'    => 'required|string',
            'store'     => 'required|string',
            'article'   => 'required|string',
            'pixel_id'  => 'nullable|string',
            'two_plus_one'  => 'nullable|string',
            'link'  => 'nullable|string',
            'region'    => 'required|string',
            'name'      => 'required|string|min:4',
            'phone'     => 'required|string',
        ]);

        // Retrieve the hidden fields
        $source    = $validatedData['source'];
        $store     = $validatedData['store'];
        $article   = $validatedData['article'];
        $pixel_id  = $validatedData['pixel_id'] ?? '';
        $two_plus_one  = $validatedData['two_plus_one'] ?? '';
        $link  = $validatedData['link'] ?? '';

        $site_code = $store;
        $item_id   = $article;

        // Initialize the RetailCRM integration
        $integration = new \App\Models\RetailCRM($site_code, $item_id);

        // Get the order from the request data
        $order = $integration->getOrderFromPost();

        // Check if the order is a duplicate
        if ($integration->isDuplicate($order)) {
            // Redirect to the duplicate page with the pixel_id
            return redirect()->route('landing.duplicate', ['pixel_id' => $pixel_id]);
        }

        // Send the order to CRM
        $integration->sendToCrm($order);

        // Redirect to the thank you page with the pixel_id
        return redirect()->route('landing.thanks', ['pixel_id' => $pixel_id]);
    }

    public function duplicate(Request $request)
    {
        $pixel_id = $request->input('pixel_id', '');
        return view('duplicate', compact('pixel_id'));
    }

    public function thanks(Request $request)
    {
        $pixel_id = $request->input('pixel_id', '');
        return view('thanks', compact('pixel_id'));
    }
}

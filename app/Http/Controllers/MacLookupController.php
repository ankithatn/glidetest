<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MacLookupController extends Controller
{
    public function lookupSingle($macAddress)
    {
        $vendor = $this->lookupVendor($macAddress);
        return response()->json(['mac_address' => $macAddress, 'vendor' => $vendor]);
    }

    public function lookupMultiple(Request $request)
    {
        $macAddresses = $request->input('mac_addresses');
        $results = [];

        foreach ($macAddresses as $macAddress) {
            $vendor = $this->lookupVendor($macAddress);
            $results[] = ['mac_address' => $macAddress, 'vendor' => $vendor];
        }

        return response()->json(['results' => $results]);
    }

    private function lookupVendor($macAddress)
    {
        // Remove any separators from the MAC address
        $macAddress = str_replace([':', '-', '.'], '', $macAddress);

        // Check for MAC address randomization
        $randomChars = ['2', '6', 'A', 'E'];
        if (in_array(substr($macAddress, 1, 1), $randomChars)) {
            return 'Randomized MAC Address';
        }

        // Query the database for vendor information
        $vendor = DB::table('ieee_oui_data')
            ->where('assignment', '=', substr($macAddress, 0, 6))
            ->value('organization_name');

        return $vendor ?: 'Vendor not found';
    }
}

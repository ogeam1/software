<?php

namespace App\Http\Controllers\Vendor;

use App\Models\Category;
use App\Models\Generalsetting;use App\Models\Product;
use App\Models\Subcategory;
use App\Models\VendorOrder;
use App\Models\Verification;
use Illuminate\Http\Request;

class VendorController extends VendorBaseController
{

    //*** GET Request
    public function index()
    {
        $data['days'] = "";
        $data['sales'] = "";
        for ($i = 0; $i < 30; $i++) {
            $data['days'] .= "'" . date("d M", strtotime('-' . $i . ' days')) . "',";

            $data['sales'] .= "'" . VendorOrder::where('user_id', '=', $this->user->id)->where('status', '=', 'completed')->whereDate('created_at', '=', date("Y-m-d", strtotime('-' . $i . ' days')))->sum("price") . "',";
        }
        $data['pproducts'] = Product::where('user_id', '=', $this->user->id)->latest('id')->take(6)->get();
        $data['rorders'] = VendorOrder::where('user_id', '=', $this->user->id)->latest('id')->take(10)->get();
        $data['user'] = $this->user;
        $data['pending'] = VendorOrder::where('user_id', '=', $this->user->id)->where('status', '=', 'pending')->get();
        $data['processing'] = VendorOrder::where('user_id', '=', $this->user->id)->where('status', '=', 'processing')->get();
        $data['completed'] = VendorOrder::where('user_id', '=', $this->user->id)->where('status', '=', 'completed')->get();
        return view('vendor.index', $data);
    }

    public function profileupdate(Request $request)
    {

        //--- Validation Section
        $rules = [
            'shop_name' => 'unique:users,shop_name,' . $this->user->id,
            'owner_name' => 'required',
            "shop_number" => "required",
            "shop_address" => "required",
            "reg_number" => "required",
            "shop_image" => "mimes:jpeg,jpg,png,svg|max:3000",
        ];

        $request->validate($rules);

        $input = $request->all();
        $data = $this->user;

        if ($file = $request->file('shop_image')) {
            $extensions = ['jpeg', 'jpg', 'png', 'svg'];
            if (!in_array($file->getClientOriginalExtension(), $extensions)) {
                return response()->json(array('errors' => ['Image format not supported']));
            }
            $name = \PriceHelper::ImageCreateName($file);
            $file->move('assets/images/vendorbanner', $name);
            $input['shop_image'] = $name;
        }

        $data->update($input);
        return back()->with('success', 'Profile Updated Successfully');

    }

    // Spcial Settings All post requests will be done in this method
    public function socialupdate(Request $request)
    {
        //--- Logic Section
        $input = $request->all();
        $data = $this->user;
        if ($request->f_check == "") {
            $input['f_check'] = 0;
        }
        if ($request->t_check == "") {
            $input['t_check'] = 0;
        }

        if ($request->g_check == "") {
            $input['g_check'] = 0;
        }

        if ($request->l_check == "") {
            $input['l_check'] = 0;
        }
        $data->update($input);
        //--- Logic Section Ends
        //--- Redirect Section
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends

    }

    //*** GET Request
    public function profile()
    {
        $data = $this->user;
        return view('vendor.profile', compact('data'));
    }

    //*** GET Request
    public function ship()
    {
        $gs = Generalsetting::find(1);
        if ($gs->vendor_ship_info == 0) {
            return redirect()->back();
        }
        $data = $this->user;
        return view('vendor.ship', compact('data'));
    }

    //*** GET Request
    public function banner()
    {
        $data = $this->user;
        return view('vendor.banner', compact('data'));
    }

    //*** GET Request
    public function social()
    {
        $data = $this->user;
        return view('vendor.social', compact('data'));
    }

    //*** GET Request
    public function subcatload($id)
    {
        $cat = Category::findOrFail($id);
        return view('load.subcategory', compact('cat'));
    }

    //*** GET Request
    public function childcatload($id)
    {
        $subcat = Subcategory::findOrFail($id);
        return view('load.childcategory', compact('subcat'));
    }

    //*** GET Request
    public function verify()
    {
        $data = $this->user;
        if ($data->checkStatus()) {
            return redirect()->route('vendor-profile')->with('success', __('Your Account is already verified.'));
        }
        return view('vendor.verify', compact('data'));
    }

    //*** GET Request
    public function warningVerify($id)
    {
        $verify = Verification::findOrFail($id);
        $data = $this->user;
        return view('vendor.verify', compact('data', 'verify'));
    }

    //*** POST Request
    public function verifysubmit(Request $request)
    {
        //--- Validation Section
        $rules = [
            'attachments.*' => 'mimes:jpeg,jpg,png,svg|max:10000',
        ];
        $customs = [
            'attachments.*.mimes' => __('Only jpeg, jpg, png and svg images are allowed'),
            'attachments.*.max' => __('Sorry! Maximum allowed size for an image is 10MB'),
        ];

        $request->validate($rules, $customs);

        $data = new Verification();
        $input = $request->all();

        $input['attachments'] = '';
        $i = 0;
        if ($files = $request->file('attachments')) {
            foreach ($files as $key => $file) {
                $name = \PriceHelper::ImageCreateName($file);
                if ($i == count($files) - 1) {
                    $input['attachments'] .= $name;
                } else {
                    $input['attachments'] .= $name . ',';
                }
                $file->move('assets/images/attachments', $name);

                $i++;
            }
        }
        $input['status'] = 'Pending';
        $input['user_id'] = $this->user->id;
        if ($request->verify_id != '0') {
            $verify = Verification::findOrFail($request->verify_id);
            $input['admin_warning'] = 0;
            $verify->update($input);
        } else {

            $data->fill($input)->save();
        }

        return back()->with('success', __('Verification request sent successfully.'));
        //--- Redirect Section Ends
    }

}

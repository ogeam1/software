<?php

namespace App\Http\Controllers\Api\Front;

use App\Classes\GeniusMailer;
use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Http\Resources\BlogResource;use App\Http\Resources\FaqResource;
use App\Http\Resources\FeaturedBannerResource;
use App\Http\Resources\FeaturedLinkResource;
use App\Http\Resources\OrderTrackResource;
use App\Http\Resources\PageResource;
use App\Http\Resources\PartnerResource;
use App\Http\Resources\ProductlistResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\SliderResource;use App\Models\ArrivalSection;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Currency;
use App\Models\Faq;
use App\Models\FeaturedBanner;
use App\Models\FeaturedLink;
use App\Models\Generalsetting;
use App\Models\Language;
use App\Models\Order;
use App\Models\Page;
use App\Models\Pagesetting;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Service;
use App\Models\Slider;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Validator;

class FrontendController extends Controller
{
    // Display Sliders, Featured Links, Featured Banners, Services, Banners & Partners

    public function section_customization()
    {
        try {
            $data = Pagesetting::find(1)->toArray();
            return response()->json(['status' => true, 'data' => $data, 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    // Display All Type Of Products

    public function vendor_products(Request $request, $vendor_id)
    {
        try {
            $user = User::where('id', $vendor_id)->first();
            if ($request->type) {
                if ($request->type == 'normal' || $request->type == "affiliate") {
                    $prods = Product::whereUserId($user->id)->whereProductType($request->type)->get();
                } else {
                    $prods = Product::whereUserId($user->id)->get();
                }
            } else {
                $prods = Product::whereUserId($user->id)->get();
            }

            return response()->json(['status' => true, 'data' => ProductlistResource::collection($prods), 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function defaultLanguage()
    {
        try {
            $language = Language::where('is_default', '=', 1)->first();
            if (!$language) {
                return response()->json(['status' => true, 'data' => [], 'error' => ['message' => 'No Language Found']]);
            }
            $data_results = file_get_contents(public_path() . '/assets/languages/' . $language->file);
            $lang = json_decode($data_results);
            return response()->json(['status' => true, 'data' => ['basic' => $language, 'languages' => $lang], 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function language($id)
    {
        try {
            $language = Language::find($id);
            if (!$language) {
                return response()->json(['status' => true, 'data' => [], 'error' => ['message' => 'No Language Found']]);
            }
            $data_results = file_get_contents(public_path() . '/assets/languages/' . $language->file);
            $lang = json_decode($data_results);
            return response()->json(['status' => true, 'data' => ['basic' => $language, 'languages' => $lang], 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function languages()
    {
        try {
            $languages = Language::all();
            return response()->json(['status' => true, 'data' => $languages, 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function defaultCurrency()
    {
        try {
            $currency = Currency::where('is_default', '=', 1)->first();
            if (!$currency) {
                return response()->json(['status' => true, 'data' => [], 'error' => ['message' => 'No Currency Found']]);
            }
            return response()->json(['status' => true, 'data' => $currency, 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function currency($id)
    {
        try {
            $currency = Currency::find($id);
            if (!$currency) {
                return response()->json(['status' => true, 'data' => [], 'error' => ['message' => 'No Currency Found']]);
            }
            return response()->json(['status' => true, 'data' => $currency, 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function currencies()
    {
        try {
            $currencies = Currency::all();
            return response()->json(['status' => true, 'data' => $currencies, 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function sliders()
    {
        try {
            $sliders = Slider::all();
            return response()->json(['status' => true, 'data' => SliderResource::collection($sliders), 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function featuredLinks()
    {
        try {
            $featuredLinks = FeaturedLink::all();
            return response()->json(['status' => true, 'data' => FeaturedLinkResource::collection($featuredLinks), 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function featuredBanners()
    {
        try {
            $featuredBanners = FeaturedBanner::all();
            return response()->json(['status' => true, 'data' => FeaturedBannerResource::collection($featuredBanners), 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function services()
    {
        try {
            $services = Service::where('user_id', '=', 0)->get();
            return response()->json(['status' => true, 'data' => ServiceResource::collection($services), 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function banners(Request $request)
    {

        try {

            $rules = [
                'type' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'data' => [], 'error' => $validator->errors()]);
            }

            $type = $request->type;
            $checkType = in_array($type, ['TopSmall', 'BottomSmall', 'Large']);

            if (!$checkType) {
                return response()->json(['status' => false, 'data' => [], 'error' => ["message" => "This type doesn't exists."]]);
            }

            if ($request->type == 'TopSmall') {
                $banners = Banner::where('type', '=', 'TopSmall')->get();
            } elseif ($request->type == 'BottomSmall') {
                $banners = Banner::where('type', '=', 'BottomSmall')->get();
            } elseif ($request->type == 'Large') {
                $ps = Pagesetting::first();
                $large_banner['image'] = asset('assets/images/' . $ps->big_save_banner1);
                $large_banner['title'] = $ps->big_save_banner_text;
                $large_banner['text'] = $ps->big_save_banner_subtitle;
                $large_banner['link'] = $ps->big_save_banner_link1;

                return response()->json(['status' => true, 'data' => $large_banner, 'error' => []]);
            }
            return response()->json(['status' => true, 'data' => BannerResource::collection($banners), 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function partners()
    {
        try {
            $partners = Partner::all();
            return response()->json(['status' => true, 'data' => PartnerResource::collection($partners), 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    // Display Sliders, Featured Links, Featured Banners, Services, Banners & Partners Ends

    // Display All Type Of Products

    public function products(Request $request)
    {

        try {
            $input = $request->all();

            if (!empty($input)) {

                $type = isset($input['type']) ? $input['type'] : '';
                $typeCheck = !empty($type) && in_array($type, ['Physical', 'Digital', 'License', 'Listing']);
                $highlight = isset($input['highlight']) ? $input['highlight'] : '';
                $highlightCheck = !empty($highlight) && in_array($highlight, ['featured', 'best', 'top', 'big', 'is_discount', 'hot', 'latest', 'trending', 'sale']);
                $productType = isset($input['product_type']) ? $input['product_type'] : '';
                $productTypeCheck = !empty($productType) && in_array($productType, ['normal', 'affiliate']);
                $limit = isset($input['limit']) ? (int) $input['limit'] : 0;
                $paginate = isset($input['paginate']) ? (int) $input['paginate'] : 0;

                $prods = Product::whereStatus(1);

                if ($typeCheck) {
                    $prods = $prods->whereType($type);
                }

                if ($productTypeCheck) {
                    $prods = $prods->whereProductType($productType);
                }

                if ($highlightCheck) {
                    if ($highlight == 'featured') {
                        $prods = $prods->whereFeatured(1);
                    } else if ($highlight == 'best') {
                        $prods = $prods->whereBest(1);
                    } else if ($highlight == 'top') {
                        $prods = $prods->whereTop(1);
                    } else if ($highlight == 'big') {
                        $prods = $prods->whereBig(1);
                    } else if ($highlight == 'is_discount') {
                        $prods = $prods->whereIsDiscount(1);
                    } else if ($highlight == 'hot') {
                        $prods = $prods->whereHot(1);
                    } else if ($highlight == 'latest') {
                        $prods = $prods->whereLatest(1);
                    } else if ($highlight == 'trending') {
                        $prods = $prods->whereTrending(1);
                    } else {
                        $prods = $prods->whereSale(1);
                    }
                }

                if ($limit != 0) {
                    $prods = $prods->where('status', 1)->take($limit);
                }

                if ($paginate == 0) {
                    $prods = $prods->where('status', 1)->get();
                } else {
                    $prods = $prods->where('status', 1)->paginate($paginate);
                }

                return response()->json(['status' => true, 'data' => ProductlistResource::collection($prods)->response()->getData(true), 'error' => []]);
            } else {

                $prods = Product::where('status', 1)->get();
                return response()->json(['status' => true, 'data' => ProductlistResource::collection($prods), 'error' => []]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    // Display All Type Of Products Ends

    // Display Faq, Blog, Page

    public function faqs()
    {
        try {
            $faqs = Faq::all();
            return response()->json(['status' => true, 'data' => FaqResource::collection($faqs), 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function blogs(Request $request)
    {
        try {
            if($request->type == 'latest'){
                $blogs = Blog::orderby('id','desc')->take(6)->get();
            }else{
                $blogs = Blog::all();
            }
            
            return response()->json(['status' => true, 'data' => BlogResource::collection($blogs), 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function pages()
    {
        try {
            $pages = Page::all();
            return response()->json(['status' => true, 'data' => PageResource::collection($pages), 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    // Display Faq, Blog, Page Ends

    // Display All Settings

    public function settings(Request $request)
    {
    
        try {

            $rules = [
                'name' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'data' => [], 'error' => $validator->errors()]);
            }

            $name = $request->name;
            $checkSettings = in_array($name, ['generalsettings', 'pagesettings', 'socialsettings']);
            if (!$checkSettings) {
                return response()->json(['status' => false, 'data' => [], 'error' => ["message" => "This setting doesn't exists."]]);
            }

            $setting = DB::table($name)->first();
            return response()->json(['status' => true, 'data' => $setting, 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    // Display All Settings Ends

    // Display Order Tracks

    public function ordertrack(Request $request)
    {
        try {
            $rules = [
                'order_number' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'data' => [], 'error' => $validator->errors()]);
            }

            $order_number = $request->order_number;

            $order = Order::where('order_number', $order_number)->first();
            if (!$order) {
                return response()->json(['status' => false, 'data' => [], 'error' => ["message" => "Order not found."]]);
            }

            return response()->json(['status' => true, 'data' => OrderTrackResource::collection($order->tracks), 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    // Display Order Tracks Ends

    // Send Email To Admin

    public function contactmail(Request $request)
    {
        try {
            //--- Validation Section

            $rules =
                [
                'name' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
                'message' => 'required',

            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'data' => [], 'error' => $validator->errors()]);
            }

            $gs = Generalsetting::find(1);

            // Login Section
            $ps = DB::table('pagesettings')->where('id', '=', 1)->first();
            $subject = "Email From Of " . $request->name;
            $to = $ps->contact_email;
            $name = $request->name;
            $phone = $request->phone;
            $from = $request->email;
            $msg = "Name: " . $name . "\nEmail: " . $from . "\nPhone: " . $request->phone . "\nMessage: " . $request->message;
            if ($gs->is_smtp) {
                $data = [
                    'to' => $to,
                    'subject' => $subject,
                    'body' => $msg,
                ];

                $mailer = new GeniusMailer();
                $mailer->sendCustomMail($data);
            } else {
                $headers = "From: " . $name . "<" . $from . ">";
                mail($to, $subject, $msg, $headers);
            }
            // Login Section Ends

            // Redirect Section
            return response()->json(['status' => true, 'data' => ['message' => 'Email Sent Successfully!'], 'error' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => true, 'data' => [], 'error' => ['message' => $e->getMessage()]]);
        }
    }

    // Send Email To Admin Ends

    public function deal()
    {
        $gs = Generalsetting::findOrFail(1);
        $data['title'] = $gs->deal_title;
        $data['deal_details'] = $gs->deal_details;
        $data['time'] = $gs->deal_time;
        $data['image'] = url('/') . '/assets/images/' . $gs->deal_background;
        $data['link'] = $gs->link;
        return response()->json(['status' => true, 'data' => $data, 'error' => []]);
    }

    public function arrival()
    {
        $ArrivalSection = ArrivalSection::get()->toArray();
        foreach ($ArrivalSection as $key => $value) {
            $ArrivalSection[$key]['photo'] = url('/') . '/assets/images/banners/' . $value['photo'];
        }

        return response()->json(['status' => true, 'data' => $ArrivalSection, 'error' => []]);
    }



}

<?php

namespace App\Http\Controllers\Front\Package;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\JobEmailPackage;
use App\Models\Job;
use App\Models\PurchasedPackage;
use Carbon\Carbon; 
use App\Models\Activity;
use App\Models\OffCode;
use App\Models\Transaction;


class EmailController extends Controller
{
    public function index()
    {
        $emailPackages  = JobEmailPackage::where('status_fa', 'active')->orderBy('sort', 'ASC')->get();

        $jobs           = Job::where('status_fa', 'active')->orderBy('created_at', 'ASC')->get();
        
        $user_package   = '';
        if ( auth()->user() ) {
            $user_package   = PurchasedPackage::where( 'status', 'active' )->where( 'user_id', auth()->user()->id )
            ->where( 'expire_date', '>', Carbon::now() )->where( 'emails_number', '>', 0)->first();
        }
        return view('front.package.email', compact('emailPackages', 'jobs', 'user_package'),
            ['title' => 'لیست پکیج های ایمیل']);
    }

    public function store(Request $request)
    {
        $message = [
            'job_id.required'       => 'فیلد شغل الزامی است.',
            'package_id.required'   => 'پکیج مورد نظر را انتخاب کنید.',
        ];
        $request->validate([
            'job_id'                => 'required|exists:job_email_packages,id',
            'package_id'            => "required"
        ], $message);

        $emailPackage = JobEmailPackage::where('id', $request->package_id)->firstOrFail();
        //todo status should change when pay the package
        // کد تخفیف
        $discount_code      = '';
        // میلغ تخفیف
        $discount_amount    = 0;
        // مبلغ نهایی
        $amountـpayable     = $emailPackage->price;

        if ($request->discount_codeـid) {
            $discount_code  = $request->discount_codeـid;
            $off = OffCode::where('status','active')->where('code', $discount_code)->first();
            // اگر کد وجود داشت
            if ( $off ) {
                $discount_amount = ($amountـpayable * $off->value) / 100;
                // اگر کد یکبار مصرف بود
                if ($off->type_off=='one') {
                    if (auth()->user()->transactions()->where('off_code', $discount_code)->count() > 0) {
                        $discount_amount = 0;
                    }
                }
                $amountـpayable = $amountـpayable - $discount_amount;
            }
        }

        $purchasedPackage = PurchasedPackage::create([
            'user_id'               => auth()->user()->id,
            'job_id'                => $request->job_id,
            'package_id'            => $request->package_id,
            'discount_codeـid'      => $discount_code,
            'discount_amount'       => $discount_amount,
            'amountـpayable'        => $amountـpayable,
            'status'                => 'active',
            'emails_number'         => $emailPackage->email_num,
            'send_emails_number'    => 0,
            'expire_date'           => Carbon::now()->addMonth($emailPackage->date)->toDateTimeString(),
        ]);

        if ($purchasedPackage) {
            //store report
            $activity               = new Activity();
            $activity->user_id      =  auth()->user()->id;
            $activity->type         = 'store';
            $activity->text         = "کاربر (".auth()->user()->name.") : پکیج ایمیلی : (".$emailPackage->title.") را خریداری کرده است.";
            auth()->user()->activity()->save($activity);

            Transaction::create([
                'user_id'           => auth()->user()->id,
                'package_name'      => 'job_email_packages',
                'amount'            => $amountـpayable,
                'off_code'          => $discount_code,
            ]);

            return redirect()->back()->with('flash_message', 'پکیج مورد نظر با موفقیت ثبت گردید.');
        }

        return redirect()->back()->with('err_message', 'عملیات خرید پکیج با خطا مواجه شده است، دوباره تلاش کنید!');
        
    }

}

<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Models\OffCode;
use App\Models\User;
use App\Models\Activity;
use App\Http\Requests\Setting\OffCodeRequest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class OffCodeController extends Controller
{
    public function controller_title($type)
    {
        switch ($type) {
            case 'index':
                return 'لیست کد تخفیف';
                break;
            case 'create':
                return 'افزودن کد تخفیف';
                break;
            case 'edit':
                return 'ویرایش کد تخفیف';
                break;
            case 'url_back':
                return route('admin.off-code.index');
                break;
            default:
                return '';
                break;
        }
    }

    public function __construct()
    {
        $this->middleware('permission:off_code_list', ['only' => ['index', 'show']]);
        $this->middleware('permission:off_code_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:off_code_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:off_code_delete', ['only' => ['destroy']]);
        $this->middleware('permission:off_code_status', ['only' => ['status']]);
    }

    public function index()
    {
        $items = OffCode::orderByDesc('id')->get();
        return view('admin.setting.off_code.index', compact('items'), ['title' => $this->controller_title('index')]);
    }

    public function show($id)
    {
    }

    public function create()
    {
        $url_back = $this->controller_title('url_back');
        $users=User::orderByDesc('id')->get();
        return view('admin.setting.off_code.create', compact('url_back','users'), ['title' => $this->controller_title('create')]);
    }

    public function store(OffCodeRequest $request)
    {
        try {
            $item = OffCode::create([
                'user_id' => $request->input('user_id'),
                'reagent' => $request->input('reagent'),
                'title' => $request->input('title'),
                'code' => $request->input('code'),
                'type_off' => $request->input('type_off'),
                'type' => $request->input('type'),
                'value' => $request->input('value'),
                'create_user_id' => auth()->id(),
            ]);

            //store report
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->type = 'store';
            $activity->text = ' کد تخفیف : ' . '(' . $item->title . ')' . ' را ثبت کرد';
            $item->activity()->save($activity);

            return redirect($this->controller_title('url_back'))->with('flash_message', 'اطلاعات با موفقیت افزوده شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای افزودن به مشکل خوردیم، مجدد تلاش کنید');
        }
    }

    public function edit($id)
    {
        $url_back = $this->controller_title('url_back');
        $item = OffCode::findOrFail($id);
        $users=User::orderByDesc('id')->get();
        $title=$this->controller_title('edit').' '.$item->title;
        return view('admin.setting.off_code.edit', compact('url_back', 'item','users'), ['title' => $title]);
    }

    public function update(OffCodeRequest $request, $id)
    {
        $item = OffCode::findOrFail($id);;
        try {
            $item->user_id = $request->input('user_id');
            $item->reagent = $request->input('reagent');
            $item->title = $request->input('title');
            $item->code = $request->input('code');
            $item->type_off = $request->input('type_off');
            $item->type = $request->input('type');
            $item->value = $request->input('value');
            $item->update();

            //store report
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->type = 'update';
            $activity->text = ' کد تخفیف : ' . '(' . $item->title . ')' . ' را ویرایش کرد';
            $item->activity()->save($activity);
            return redirect($this->controller_title('url_back'))->with('flash_message', 'اطلاعات با موفقیت ویرایش شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای ویرایش به مشکل خوردیم، مجدد تلاش کنید');
        }
    }

    public function destroy($id)
    {
        $item = OffCode::findOrFail($id);
        $old_title=$item->title;
        try {
            $item->delete();

            //store report
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->type = 'delete';
            $activity->text = ' کد تخفیف : ' . '(' . $old_title . ')' . ' را حذف کرد';
            $item->activity()->save($activity);
            return redirect($this->controller_title('url_back'))->with('flash_message', 'اطلاعات با موفقیت حذف شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای حذف به مشکل خوردیم، مجدد تلاش کنید');
        }
    }
    public function status($id,$status)
    {
        $item = OffCode::findOrFail($id);
        $old_title=$item->title;
        try {
            $item->status=$status;
            $item->update();

            //store report
            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->type = 'delete';
            if($status=='active')
            {
                $activity->text = ' کد تخفیف : ' . '(' . $old_title . ')' . ' را فعال کرد';
            }
            else
            {
                $activity->text = ' کد تخفیف : ' . '(' . $old_title . ')' . ' را غیرفعال کرد';
            }
            $item->activity()->save($activity);
            return redirect($this->controller_title('url_back'))->with('flash_message', 'اطلاعات با موفقیت حذف شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای حذف به مشکل خوردیم، مجدد تلاش کنید');
        }
    }
}

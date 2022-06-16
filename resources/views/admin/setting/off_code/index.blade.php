@extends('layouts.master',['tbl'=>true])
@section('title')
    {{$title}}
@endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            تنظیمات
        @endslot
        @slot('title')
            {{$title}}
        @endslot
    @endcomponent
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">{{$title}}</h4>
                    @can('off_code_create')
                    <div class="flex-shrink-0">
                        <div class="form-check form-switch form-switch-right form-switch-md">
                            <a href="{{route('admin.off-code.create')}}" class="btn btn-primary">افزودن</a>
                        </div>
                    </div>
                        @endcan
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table  table-vcenter text-nowrap table-bordered border-bottom tbl_1">
                            <thead>
                            <tr>
                                <th class="border-bottom-0">ردیف</th>
                                <th class="border-bottom-0">عنوان</th>
                                <th class="border-bottom-0">کد</th>
                                <th class="border-bottom-0">مقدار</th>
                                <th class="border-bottom-0">استفاده کننده</th>
                                <th class="border-bottom-0">چگونگی عملکرد/خرید</th>
                                <th class="border-bottom-0">چگونگی عملکرد/معرف</th>
                                @can('off_code_status')
                                    <th class="border-bottom-0">وضعیت</th>
                                @endcan
                                <th class="border-bottom-0">کاربر ثبت کننده</th>
                                @canany(['off_code_edit','off_code_delete'])
                                <th class="border-bottom-0">عملیات</th>
                                @endcan
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $key=>$item)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$item->title}}</td>
                                    <td>{{$item->code}}</td>
                                    <td>{{$item->value}} {{$item->type=='toman'?' تومان':' درصد'}}</td>
                                    <td>@if($item->user_id==null) همه @else {{$item->user?$item->user->name:'حذف شده'}} (#{{$item->user_id}})@endif</td>
                                    <td>{{$item->type_off=='one'?' اولین خرید':' همه خریدها'}}</td>
                                    <td>{{$item->reagent=='no'?' همه':' دارای معرف'}}</td>
                                    @can('off_code_status')
                                        <td>
                                            @if($item->status=='active')
                                                <span class="text-success me-2">فعال</span>
                                                <a href="{{route('admin.off-code.status',[$item->id,'pending'])}}"  data-bs-toggle="tooltip" data-bs-html="true"  data-bs-placement="top"
                                                   title="<b>غیرفعال شود؟</b>">
                                                    <i class="fas fa-close text-danger"></i>
                                                </a>
                                            @else
                                                <span class="text-danger me-2">غیرفعال</span>
                                                <a href="{{route('admin.off-code.status',[$item->id,'active'])}}"  data-bs-toggle="tooltip" data-bs-html="true"  data-bs-placement="top"
                                                   title="<b>فعال شود؟</b>">
                                                    <i class="fas fa-check text-success"></i>
                                                </a>
                                            @endif
                                        </td>
                                    @endcan
                                    <td>{{$item->user_create?$item->user_create->name:'__'}} </td>
                                    @canany(['off_code_edit','off_code_delete'])
                                    <td>
                                        <div class="d-flex">
                                            @can('off_code_edit')
                                                <a href="{{route('admin.off-code.edit',$item->id)}}"
                                                   class="action-btns1" data-bs-toggle="tooltip" data-bs-html="true"  data-bs-placement="top"
                                                   title="<b>ویرایش</b>">
                                                    <i class="fas fa-edit  text-success"></i>
                                                </a>
                                            @endcan
                                                @can('off_code_delete')
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['admin.off-code.destroy', $item->id] ]) !!}
                                                    <button class="action-btns1" data-bs-toggle="tooltip"
                                                            data-bs-placement="top" title="حذف"
                                                            onclick="return confirm('برای حذف مطمئن هستید؟')">
                                                        <i class="fas fa-trash text-danger"></i>
                                                    </button>
                                                    {!! Form::close() !!}
                                                @endcan
                                        </div>
                                    </td>
                                    @endcan
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')

@endsection

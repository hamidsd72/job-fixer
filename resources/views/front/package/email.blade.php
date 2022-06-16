@extends('layouts.front')
@section('styles') @endsection
@section('body')

<style>
    .text-custom-red {
        color: #e6260e;
    }
    .bg-custom-red {
        background: #e6260e;
        color: white;
    }
    .text-custom-blue {
        color: #1546ad;
    }
    .bg-custom-blue {
        background: #1546ad;
        color: white;
    }
    .email-package-one {

    }
    .email-package-one .card {
        
    }
    .email-package-one .card-header h4 {
        font-weight: bold !important;
    }
    .email-package-one .card-body h3 {
        
    }
    .email-package-one .card-body .list-unstyled {
        
    }
    .email-package-one button {
        
    }

</style>



<div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center" style="max-width: 1280px;">
    <h1>پکیج های ایمیل</h1>
    <p class="lead pt-2">پکیج مورد نظر خود را انتخاب کنید</p>
</div>

<div class="container mb-4">
    <div class="row justify-center card-deck mb-3 text-center">

        @foreach($emailPackages as $package)

            @if($package->vip == 'active')
                @php $vipBG = 'vip-package-background'; $vipColor = 'vip-package-color'; $btnVip ='btn-package-vip' @endphp
            @endif

            {{-- <div class="col-lg-4 col-md-4  col-sm-12 card mb-4 shadow-sm p-0 {!! $vipBG ?? '' !!}">
                <div class="card-header">
                    <h4 class="my-0 font-weight-normal {!! $vipColor ?? '' !!}">{{$package->title}}</h4>
                </div>
                <div class="card-body">
                    <h3 class="card-title pricing-card-title {!! $vipColor ?? '' !!}">{{number_format($package->price)}}
                        <small class=" {!! $vipColor ?? '' !!}">/ {{$package->fa_duration}}</small></h3>
                    <div class="list-unstyled package-text-body mt-3 mb-4 {!! $vipColor ?? '' !!}">
                        {!! $package->text !!}
                    </div>
                    <button type="button" data-packageId="{{$package->id}}"
                            class=" {!! $btnVip ?? '' !!} btn-package-buyer btn btn-lg btn-block btn-primary open-modal-buy-email-package">خرید
                    </button>
                </div>
            </div> --}}
            <div class="col-md-4 email-package-one {!! $vipBG ?? '' !!}">
                <div class="card m-0">
                    <div class="card-header">
                        <h4 class="text-center mt-2 {!! $vipColor ?? '' !!}">{{$package->title}}</h4>
                    </div>
                    <div class="card-body p-4">
                        <h3 class="card-title pricing-card-title text-center text-custom-red {!! $vipColor ?? '' !!}">
                            <small class=" {!! $vipColor ?? '' !!}">{{$package->fa_duration}}</small>
                        </h3>
                        <h3 class="card-title pricing-card-title text-center {!! $vipColor ?? '' !!}">
                            {{number_format($package->price)}}
                        </h3>
                        <div class="list-unstyled package-text-body mt-3 mb-4 text-center {!! $vipColor ?? '' !!}">
                            {!! $package->text !!}
                        </div>
                        {{-- <button type="button" data-packageId="{{$package->id}}"
                                class=" {!! $btnVip ?? '' !!} btn-package-buyer btn btn-lg btn-block btn-primary open-modal-buy-email-package">خرید
                        </button> --}}
                        <button type="button" data-packageId="{{$package->id}}" data-toggle="modal" data-target="#modal-buy-email-package{{$package->id}}"
                                class=" {!! $btnVip ?? '' !!} btn-package-buyer btn btn-lg btn-block btn-primary open-modal-buy-email-package">خرید پکیج
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="modal-buy-email-package{{$package->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-right" style="width: 100%;" id="exampleModalLabel">فورم خرید پکیج {{number_format($package->price)}}</h5>
                        </div>
                        @if(auth()->check())
                            <form action="{{route('front.packages.email.store')}}" method="POST" class="modal-content">
                                @csrf
                                <div class="modal-body">
                                    @if ( ($user_package ?? '') && ($user_package->package_id == $package->id) )
                                        <div class="alert alert-primary text-center px-5" role="alert">
                                            {{'این پکیج توسط شما خریداری شده و تا تاریخ '.g2j($user_package->expire_date,'Y/m/d').' اعتبار و '.$user_package->emails_number.' ایمیل دارد'}}
                                        </div>
                                    @endif
                                    <input type="hidden" name="package_id" value="{{$package->id}}">
                                    <label for="discount_codeـid">کد تخفیف</label>
                                    <input type="text" name="discount_codeـid" class="form-control form-select" placeholder="368H54GtmR093Kikj">
                                    <label for="job">شغل مورد نظر را انتخاب کنید</label>
                                    <select class="form-control form-select select2" id="job" name="job_id" aria-label="Default select example">
                                        @foreach($jobs as $job)
                                            <option value="{{$job->id}}" @if($jobs[0]->id == $job->id) selected @endif>{{$job->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">برگشت</button>
                                    <button type="submit" class="btn btn-primary">ثبت درخواست خرید</button>
                                </div>
                            </form>
                        @else
                            <div class="modal-body">
                                <div class="alert alert-primary text-center px-5" role="alert">
                                    کاربر گرامی برای خرید پکیج ابتدا باید وارد حساب کاربری خود شوید
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="{{route('register')}}" class="btn btn-success">ثبت نام</a>
                                <a  href="{{route('login')}}" class="btn bg-custom-blue" >ورود به حساب</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
        @endforeach

    </div>


</div>



    {{-- <!-- Modal -->
    <div class="modal fade" id="modal-buy-email-package" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            @if(auth()->check())
                <form action="{{route('front.packages.email.store')}}" method="POST" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">شغل مورد نظر خود را انتخاب کنید</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <input type="hidden" name="package_id" value="{{old('package_id')}}">
                        <label for="job">شغل مورد نظر را انتخاب کنید</label>
                        <select class="form-select" id="job" name="job_id" aria-label="Default select example">
                            <option value="" selected>انتخاب کنید</option>
                            @foreach($jobs as $job)
                                <option value="{{$job->id}}">{{$job->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">برگشت</button>
                        <button type="submit" class="btn btn-primary">ثبت درخواست خرید</button>
                    </div>
                </form>
            @else
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-primary" role="alert">
                            کاربر گرامی برای خرید پکیج ابتدا باید وارد حساب کاربری خود شوید!
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a  href="{{route('login')}}" class="btn btn-primary" >ورود</a>
                        <a href="{{route('register')}}" class="btn btn-secondary">ثبت نام</a>
                    </div>
                </div>
            @endif
        </div>
    </div> --}}
@endsection
@section('scripts')
    @if ($errors->any())
        <script>
            $(document).ready(function (){
                $('#modal-buy-email-package').modal('toggle');
            })
        </script>
    @endif
@endsection



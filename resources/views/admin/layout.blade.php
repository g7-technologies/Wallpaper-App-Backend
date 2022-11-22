<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ env( "APP_NAME" ) }} {{ env( "APP_ENV" ) }}: Administrator panel</title>

        <script src="{{ asset('vendor/jquery/jquery-1.10.2.min.js')}}"></script>
    <script src="{{ asset('vendor/jquery/jquery-ui.min.js')}}"></script>
    <script src="{{ asset('vendor/popper/popper.min.js')}}"></script>
    <script src="{{ asset('vendor/bootstrap/bootstrap.min.js')}}"></script>
    <script src="{{ asset('vendor/trumbowyg/trumbowyg.min.js')}}"></script>
    <script src="{{ asset('vendor/jquery-timepicker-1.3.2/jquery.timepicker.min.js')}}"></script>
    <script src="{{ asset('vendor/select2-4.0.2/dist/js/select2.min.js')}}"></script>
    @yield('scripts')

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link href="{{ asset('vendor/jquery-timepicker-1.3.2/jquery.timepicker.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('vendor/select2-4.0.2/dist/css/select2.min.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('vendor/jquery/jquery-ui.min.css')}}">
    <link rel="stylesheet" href="{{ asset('vendor/trumbowyg/ui/trumbowyg.min.css')}}">
    <link href="{{ asset('vendor/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css')}}" rel="stylesheet" />
    <link rel="shortcut icon" href="{{ asset('favicon.png')}}">

    <style>
        .container{
            max-width: 1500px;
        }
        .badge{
            text-transform: uppercase;
        }
        .buffer-0{
            margin: 0;
        }
        .top-space-10{
            padding-top: 10px;
        }
        .top-space{
            padding-top: 20px;
        }
        .bottom-space{
            padding-bottom: 20px;
        }
        .bottom-space-10{
            padding-bottom: 10px;
        }
        .top-buffer{
            margin-top: 20px;
        }
        .top-buffer-10{
            margin-top: 10px;
        }
        .right-buffer{
            margin-right: 20px;
        }
        .right-buffer-10{
            margin-right: 10px;
        }
        .bottom-buffer{
            margin-bottom: 20px;
        }
        .bottom-buffer-10{
            margin-bottom: 10px;
        }
        .left-buffer{
            margin-left: 20px;
        }
        .left-buffer-10{
            margin-left: 10px;
        }
        .round{
            border-radius: 50%;
            /*border: 1px solid #ccc;*/
        }
        .sq-60{
            height: 60px;
            width: 60px;
        }
        .h-50{
            height: 50px;
        }
        .sq-30{
            height: 30px;
            width: 30px;            
        }
        .sq-100{
            height: 100px;
            width: 100px;
        }
        .cursor{
            cursor: pointer;
        }
        .font11{
            font-size: 11px;
        }
        .font16{
            font-size: 16px;
        }
        .font18{
            font-size: 18px;
        }
        .font20{
            font-size: 20px;
        }
        .font22{
            font-size: 22px;
        }
        .bold{
            font-weight: bold;
        }
        /** trumbowyg editor */
        .trumbowyg-box,
        .trumbowyg-editor {
            min-height: 150px;
            width: 100%;
            margin: 0;
            margin-bottom: 40px;
        }
        .trumbowyg-editor,
        .trumbowyg-textarea {
            min-height: 150px;
            width: 100%;
            margin: 0;
        }
        .w-100p{
            width: 100%;
        }
        .min-w-150{
            min-width: 150px;
        }
        .upload-button{
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }
        .breaks{
            word-wrap: break-word;
        }
        .html-content img{
            max-width: 100%;
        }
        .label-gray{
            font-weight: normal;
            background-color: #aaa;
        }
        .tag{
            color: #000 !important;
            font-size:10px;
            text-transform: uppercase;
            letter-spacing:.15em
        }
        .state-label {
            text-transform: uppercase;letter-spacing: .15em; font-size: 9px; display: inline-block; vertical-align: middle; font-weight: normal;line-height: 14px;
        }
        .inline-block{
            display: inline-block;
        }
        .up{
            text-transform: uppercase;
        }
        .badge-secondary{
            border: 1px solid;
        }
    </style>

    @yield('styles')
</head>

<body>
    <div class='top-buffer'>
        <div class='container'>
            <div class='row'>
                <div class='col-lg-8 offset-lg-2'>
                    @include( 'admin.include.notice' )
                    @include( 'admin.include.errors' )
                </div>
            </div>
            <div class='row top-buffer'>
                <div class='col-lg-2'>
                    <a href="{{ route( 'admin.index' ) }}" class="badge badge-secondary">Admin panel</a>
                    <hr/>
                    @if( $cur_user = Auth::user() )
                        <div class="bottom-buffer">
                          {{ $cur_user->name }}
                          <div class="font11 bottom-buffer-10">
                            <span class="text-muted">
                                {{ \App\User::$roles[ $cur_user->role ] }}</span><br/>
                            <b>{{ $cur_user->email }}</b><br/>
                            <a style="display: inline-block; margin-top:10px;text-decoration: none;color: #cc0033;font-size: 13px" href="{{ route( 'front.auth.logout' ) }}"><span class='glyphicon glyphicon-off'></span> Log off</a>
                          </div>
                        </div>
                    @endif
                    <hr/>
                    <p>
                        <!-- <a href="{{ route( 'admin.app_settings.index' ) }}"><span class='badge badge-primary'>App Settings</span></a>
                        <hr/> -->
                        <a href="{{ route( 'admin.app_installs.index' ) }}"><span class='badge badge-primary'>App installs</span></a><br>
                        <!-- <a href="{{ route( 'admin.anonymous.index' ) }}"><span class='badge badge-primary'>Anonymous</span></a><br/> -->
                        <a href="{{ route( 'admin.users.index' ) }}"><span class='badge badge-primary'>Users</span></a><br/>
                        <hr/>
                        <a href="{{ route( 'admin.tags.index' ) }}"><span class='badge badge-primary'>Tags</span></a><br/>
                        <a href="{{ route( 'admin.categories.index' ) }}"><span class='badge badge-primary'>Categories</span></a><br/>
                        <!-- <a href="{{ route( 'admin.category_quotes.index' ) }}"><span class='badge badge-primary'>Quotes</span></a><br/> -->
                        <a href="{{ route( 'admin.wallpapers.index' ) }}"><span class='badge badge-primary'>Wallpapers</span></a><br/>
                        <!-- <hr/> -->
                        <!-- <a href="{{ route( 'admin.cache.index' ) }}"><span class='badge badge-primary'>Cache</span></a><br/>
                        <a href="{{ route( 'admin.cache.categories_list' ) }}"><span class='badge badge-primary'>Update Categories List Hash</span></a>
                        <hr/>
                        <a href="{{ route( 'admin.subscriptions.index' ) }}"><span class='badge badge-primary'>Subscriptions</span></a><br/>
                        <a href="{{ route( 'admin.non_consumables.index' ) }}"><span class='badge badge-primary'>Non consumables</span></a><br/>
                        <a href="{{ route( 'admin.free_access.index' ) }}"><span class='badge badge-primary'>Free Access</span></a><br/>
                        <hr/>
                        <a href="{{ route( 'admin.receipts.stats' ) }}"><span class='badge badge-primary'>Renewal Stats</span></a><br/>
                        <a href="{{ route( 'admin.receipts.billing_retries' ) }}"><span class='badge badge-primary'>Billing retries</span></a><br/> -->
                    </p>
                    <hr/>
                </div>
                <div class='col-lg-10'>
                    @yield( 'content' )
                </div>
            </div>
        </div>
    </div>
</body>
</html>
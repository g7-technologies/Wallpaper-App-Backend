<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <title>{{ env( 'APP_NAME' ) }}</title>

        <script src="{{asset ('vendor/jquery/jquery-1.10.2.min.js')}}"></script>
    <script src="{{asset ('vendor/jquery/jquery-ui.min.js')}}"></script>
    <script src="{{asset ('vendor/bootstrap/bootstrap.min.js')}}"></script>
    <script src="{{asset ('vendor/trumbowyg/trumbowyg.min.js')}}"></script>
    <script src="{{asset ('vendor/jquery-timepicker-1.3.2/jquery.timepicker.min.js')}}"></script>
    <script src="{{asset ('vendor/select2-4.0.2/dist/js/select2.min.js')}}"></script>
    @yield('scripts')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/fontawesome/4.6.3/css/font-awesome.min.css" integrity="sha256-AIodEDkC8V/bHBkfyxzolUMw57jeQ9CauwhVW6YJ9CA=" crossorigin="anonymous">
    <link href="{{asset ('vendor/jquery-timepicker-1.3.2/jquery.timepicker.min.css')}}" rel="stylesheet" />
    <link href="{{asset ('vendor/select2-4.0.2/dist/css/select2.min.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset ('vendor/bootstrap/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset ('vendor/jquery/jquery-ui.min.css')}}">
    <link rel="stylesheet" href="{{asset ('vendor/trumbowyg/ui/trumbowyg.min.css')}}">
    <link href="{{asset ('/vendor/select2-bootstrap-theme-master/dist/select2-bootstrap.min.css')}}" rel="stylesheet" />

    <link rel="shortcut icon" href="{{asset ('favicon.png')}}">

    <style>
        .max-w-1500{
            max-width: 1500px;
        }
        .red{
            color: #f20c64;
        }
        .red-hover:hover{
            color: #f20c64;
        }
        .bg-raspberry{
            background-color: #cc0033;
        }
        .sq-20{
            height: 20px;
            width: 20px;
        }
        .white{
            color: #fff;
        }
        .white-hover:hover{
            color: #fff;
        }
        .bg-white{
            background-color: #fff;
        }
        .dark-gray{
            color: #313131;
        }
        .gray{
            color: #666666;
        }
        .color-danger{
            color: #d9534f !important;
        }
        .blue{
            color: #2a99eb;
        }
        .bg-blue{
            background-color: #2a99eb;
        }
        .dark-blue{
            color: #3c40e9;
        }
        .bg-dark-blue{
            background-color: #3c40e9;
        }
        .light-green{
            color: #1ff595;
        }
        .bg-light-green{
            background-color: #1ff595;
        }
        .bg-555{
            background-color: #555;
        }
        .bg-999{
            background-color: #999;
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
            color: #777;
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
    </style>

    @yield('styles')
</head>

<body>
    <div class='top-buffer'>
        <div class='container'>
            <div class='row'>
                <div class='col-lg-8 offset-lg-2'>
                    @include( 'front.include.notice' )
                    @include( 'front.include.errors' )
                </div>
            </div>
            <div class='row top-buffer'>
                <div class='col-lg-12'>
                    @yield( 'content' )
                </div>
            </div>
        </div>
    </div>
</body>
</html>
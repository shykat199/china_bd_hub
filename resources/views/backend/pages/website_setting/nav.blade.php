<div class="content-tab-title">
    <h4>{{__('Website Setting')}}</h4>
</div>
<!-- Tab Manu Start  -->
<div class="nav nav-tabs p-tab-manu" id="nav-tab" role="tablist">
    <button class="nav-link @if(Request::is('admin/website_setting/header'))active @endif" id="header-tab" data-bs-toggle="tab" data-bs-target="#header"
            type="button" role="tab" Area-controls="header" Area-selected="true"
            @if(url()->full()!=route('backend.website_setting.header')) onclick="location.href='{{route('backend.website_setting.header')}}'" @endif
    >{{__('Header')}}
    </button>
    <button class="nav-link @if(Request::is('admin/website_setting/pages'))active @endif" id="pages-tab" data-bs-toggle="tab" data-bs-target="#pages" type="button"
            role="tab" Area-controls="pages" Area-selected="false"
            @if(url()->full()!=route('backend.website_setting.pages')) onclick="location.href='{{route('backend.website_setting.pages')}}'" @endif
    >{{__('Pages')}}
    </button>
    <button class="nav-link @if(Request::is('admin/website_setting/appearance'))active @endif" id="appearance-tab" data-bs-toggle="tab" data-bs-target="#appearance"
            type="button" role="tab" Area-controls="appearance" Area-selected="false"
            @if(url()->full()!=route('backend.website_setting.appearance')) onclick="location.href='{{route('backend.website_setting.appearance')}}'" @endif
    >{{__('Appearance')}}
    </button>
    <button class="nav-link @if(Request::is('admin/website_setting/announcements','admin/website_setting/announcements/*'))active @endif" id="announcements-tab" data-bs-toggle="tab" data-bs-target="#announcements" type="button" role="tab" Area-controls="announcements" Area-selected="false" @if(url()->full()!=route('backend.announcements.index')) onclick="location.href='{{route('backend.announcements.index')}}'" @endif
    >{{__('Announcements')}}
    </button>
</div>

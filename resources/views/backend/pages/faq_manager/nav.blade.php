<div class="content-tab-title">
    <h4>{{__('FAQ manager')}}</h4>
</div>
<!-- Tab Manu Start -->
<div class="nav nav-tabs p-tab-manu" id="nav-tab" role="tablist">
    <button class="nav-link @if(Request::is('admin/faq_category'))active @endif" id="faq-category-tab" data-bs-toggle="tab"
            data-bs-target="#faq-category" type="button" role="tab" Area-controls="faq-category"
            Area-selected="false"
            @if(url()->full()!=route('backend.faq_category.index')) onclick="location.href='{{route('backend.faq_category.index')}}'" @endif
    >{{__('FAQ Category')}}
    </button>
    <button class="nav-link @if(Request::is('admin/faq_subcategory'))active @endif" id="faq-subcategory-tab" data-bs-toggle="tab"
            data-bs-target="#faq-subcategory" type="button" role="tab" Area-controls="faq-subcategory"
            Area-selected="false"
            @if(url()->full()!=route('backend.faq_subcategory.index')) onclick="location.href='{{route('backend.faq_subcategory.index')}}'" @endif
    >{{__('FAQ SubCategory')}}
    </button>
    <button class="nav-link @if(Request::is('admin/faq_content','admin/faq_content/*'))active @endif" id="questions-and-answer-tab" data-bs-toggle="tab"
            data-bs-target="#questions-and-answer" type="button" role="tab"
            Area-controls="questions-and-answer" Area-selected="false"
            @if(url()->full()!=route('backend.faq_content.index')) onclick="location.href='{{route('backend.faq_content.index')}}'" @endif
    >{{__('Questions and Answer')}}
    </button>

</div>

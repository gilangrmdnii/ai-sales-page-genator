{{-- Template dispatcher: include the chosen template's partial --}}
@include('salespages.templates.' . $page->templateKey())

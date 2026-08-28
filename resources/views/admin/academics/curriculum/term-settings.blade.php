@extends('admin.layouts.app')
@section('title','Term Settings')
@section('content') @include('admin.academics.curriculum._form-list',['kind'=>'term-settings']) @endsection
@push('scripts')
<script>
(function () {
    const row = document.querySelector('#curriculum-table thead tr');
    if (!row) return;
    const headers = [...row.cells];
    if (headers.length < 3) return;
    headers[0].textContent = 'Name';
    headers[1].textContent = 'Action';
    headers[1].classList.remove('cursor-pointer');
    headers[2].remove();
})();
</script>
@endpush
@include('admin.academics.curriculum._sort-arrows')

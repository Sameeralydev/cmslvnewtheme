@extends('admin.layouts.app')
@section('title','Domain Modules')
@section('content') @include('admin.academics.curriculum._form-list',['kind'=>'domain-modules']) @endsection

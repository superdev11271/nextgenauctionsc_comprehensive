@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
      <div class="col-md-6">
        <h1 class="h3">{{translate('All Permissions')}}</h1>
      </div>
      @can('add_staff')
        <div class="col-md-6 text-md-right">
            <a href="{{ route('permissions.create') }}" class="btn btn-circle btn-info">
                <span>{{translate('Add New Permissions')}}</span>
            </a>
        </div>
      @endcan
    </div>
  </div>
  
  <div class="card">
    <form class="" id="sort_sellers" action="" method="GET">
      <div class="card-header row gutters-5">
        <div class="col">
          <h5 class="mb-md-0 h6">{{ translate('Permissions') }}</h5>
        </div>
  
        <div class="col-md-3">
          <div class="form-group mb-0">
            <input type="text" class="form-control" id="search" name="search" @isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name & Enter') }}">
          </div>
        </div>
      </div>
  
      <div class="card-body">
        <table class="table aiz-table mb-0">
          <thead>
            <tr>
  
              <th>#</th>
              <th>{{translate('Name')}}</th>
              <th width="10%">{{translate('Options')}}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($data as $key => $value)
            <tr>
              <td>{{ ($key+1) + ($data->currentPage() - 1)*$data->perPage() }}</td>
              <td>{{$value->name}}</td>
              <td>
                <a href="{{route('permissions.edit', [$value->id])}}" class="btn btn-soft-primary btn-icon btn-circle btn-sm" title="{{ translate('Edit Permission') }}">
                  <i class="las la-edit"></i>
                </a>
                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete" data-href="{{route('permissions.destroy', encrypt($value->id))}}" title="{{ translate('Delete') }}">
                  <i class="las la-trash"></i>
                </a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        <div class="aiz-pagination">
          {{ $data->appends(request()->input())->links() }}
        </div>
      </div>
    </form>
  </div>
@endsection
@section('modal')
    @include('modals.delete_modal')
@endsection

@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3">{{ translate('Tamplates') }}</h1>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="mb-0 fw-600">{{ translate('All Tamplates') }}</h6>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th data-breakpoints="lg">#</th>
                        <th>Name</th>
                        <th>Subject</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($emailTemplates as $key => $emailTemplate)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td class="text-uppercase">{{ $emailTemplate->name }}</td>
                            <td>{{ $emailTemplate->subject }}</td>
                            <td class="text-right">
                                <a href="{{ route('email-templates.edit', $emailTemplate->id) }}"
                                    class="btn btn-icon btn-circle btn-sm btn-soft-primary" title="Edit">
                                    <i class="las la-pen"></i>
                                </a>
                                @if ($emailTemplate->name == 'custom')
                                    @if (DB::table('jobs')->where('queue', 'web_push_notification')->count())
                                        <button class="btn btn-circle btn-sm btn-soft-danger">
                                            Processing...
                                        </button>
                                    @else
                                        <a href="{{ route('app-translations.store') }}"
                                            class="btn btn-circle btn-sm btn-soft-primary">
                                            Notify All Users
                                        </a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection

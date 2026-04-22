@extends('backend.layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Chat Tamplates') }}</h1>
            </div>

            <div class="col-md-6 text-md-right">
                <button onclick="create()" class="btn btn-circle btn-info">
                    <span>{{ translate('Add New Tamplate') }}</span>
                </button>
            </div>

        </div>
    </div>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Tamplates') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th data-breakpoints="lg" width="10%">#</th>
                        <th>{{ translate('Tamplate') }}</th>
                        <th data-breakpoints="lg">{{ translate('Used By') }}</th>
                        <th data-breakpoints="lg">{{ translate('Amount Required') }}</th>
                        <th width="10%" class="text-right">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tamplates as $key => $tamplate)
                        <tr>
                            <td>{{ $key + 1 + ($tamplates->currentPage() - 1) * $tamplates->perPage() }}</td>
                            <td>{{ $tamplate->message }}</td>
                            <td>{{ $tamplate->used_by }}</td>
                            <td>{{ $tamplate->with_amount == 1 ? 'Required' : 'Not Required' }}</td>
                            <td class="text-right">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm edit_btn" {{-- href="route('staffs.edit', encrypt(staff->id))" --}}
                                    onclick="edit(this)" data-id="{{ $tamplate->id }}"
                                    data-message="{{ $tamplate->message }}" data-used_by="{{ $tamplate->used_by }}"
                                    data-with_amount={{ $tamplate->with_amount }} title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                    data-href="{{ route('chat.tamplate.delete', $tamplate->id) }}"
                                    title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $tamplates->links() }}
            </div>
        </div>
    </div>
    <!-- Button trigger modal -->

    <!-- Modal -->
    <div class="modal fade " id="tamplateModal" tabindex="-1" aria-labelledby="tamplateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tamplateModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="modalForm" action="">
                        @csrf
                        <div class="form-group row">
                            <label class="col-xxl-3 col-from-label fs-13">Tamplate</label>
                            <div class="col-xxl-9">
                                <input type="text" id="message" placeholder="Write Tamplate like: I can max bid $$"
                                    name="message" class="form-control" required="">
                            </div>
                        </div>
                        <div class="form-group row" id="brand">
                            <label class="col-xxl-3 col-from-label fs-13">Tamplate is for:</label>
                            <div class="col-xxl-9">
                                <select class="form-control " name="used_by" id="used_by" data-live-search="true">
                                    <option value="seller">Sellers</option>
                                    <option value="bidder">Bidders</option>
                                </select>
                                {{-- <small class="text-muted">You can choose a brand if you&#039;d like to display your product by
                                brand.</small> --}}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-6 col-from-label">With Amount</label>
                            <div class="col-md-6">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox" id="with_amount" name="with_amount" value="1">
                                    <span></span>
                                </label>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    @include('modals.delete_modal')
@endsection


@section('script')
    <script>
        function create() {
            $("#modalForm").attr("action", '{{ route('chat.tamplate.store') }}');
            $("#modalForm").trigger('reset');
            $("#tamplateModalLabel").text("Create")
            $("#tamplateModal").modal("show");
        }


        function edit(element) {
            $("#tamplateModalLabel").text("Edit")
            let id = $(element).data('id')
            var url = '{{ route('chat.tamplate.update', ':id') }}'
            let newUrl = url.replace(":id", id)
            $("#modalForm").attr("action", newUrl);
            $("#tamplateModal").modal("show");

            let message = $(element).data('message')
            let used_by = $(element).data('used_by')
            let with_amount = $(element).data('with_amount')

            // Fill the form fields with the data
            $('#message').val(message);
            $('#used_by').val(used_by);
            $('#with_amount').prop("checked",with_amount);
        }
    </script>
@endsection

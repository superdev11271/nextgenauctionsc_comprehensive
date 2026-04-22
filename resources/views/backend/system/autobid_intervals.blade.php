@extends('backend.layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('Autobid Intervals') }}</h1>
            </div>

            <div class="col-md-6 text-md-right">
                <button onclick="create()" class="btn btn-circle btn-info">
                    <span>{{ translate('Add New Interval') }}</span>
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
            <h5 class="mb-0 h6">{{ translate('Autobid Intervals') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th data-breakpoints="lg" width="10%">#</th>
                        <th>{{ translate('Min Bid') }}</th>
                        <th>{{ translate('Max Bid') }}</th>
                        <th>{{ translate('Increment') }}</th>
                        <th width="10%" class="text-right">{{ translate('Options') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($intervals as $key => $interval)
                        <tr>
                            <td>{{ $key + 1 + ($intervals->currentPage() - 1) * $intervals->perPage() }}</td>
                            <td>{{ $interval->min_bid }}</td>
                            <td>{{ $interval->max_bid }}</td>
                            <td>{{ $interval->increment }}</td>
                            <td class="text-right">
                                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm edit_btn" {{-- href="route('staffs.edit', encrypt(staff->id))" --}}
                                    onclick="edit(this)" data-id="{{ $interval->id }}"
                                    data-min_bid="{{ $interval->min_bid }}"
                                    data-max_bid="{{ $interval->max_bid }}"
                                    data-increment={{ $interval->increment }}
                                    title="{{ translate('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                                <a href="#" class="btn btn-soft-danger btn-icon btn-circle btn-sm confirm-delete"
                                    data-href="{{ route('autobid.delete', $interval->id) }}"
                                    title="{{ translate('Delete') }}">
                                    <i class="las la-trash"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $intervals->links() }}
            </div>
        </div>
    </div>
    <!-- Button trigger modal -->

    <!-- Modal -->
    <div class="modal fade " id="intervalModal" tabindex="-1" aria-labelledby="intervalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="intervalModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="modalForm" action="">
                        @csrf
                        <div class="form-group row">
                            <label class="col-xxl-3 col-from-label fs-13">Minimum Bid</label>
                            <div class="col-xxl-9">
                                <input type="text" id="min_bid" placeholder="Minimum breakpoint: ex 0,6,11"
                                    name="min_bid" class="form-control" required="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-xxl-3 col-from-label fs-13">Maximum Bid</label>
                            <div class="col-xxl-9">
                                <input type="text" id="max_bid" placeholder="Maximum breakpoint: ex 5,10,20"
                                    name="max_bid" class="form-control" required="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-xxl-3 col-from-label fs-13">Increment</label>
                            <div class="col-xxl-9">
                                <input type="text" id="increment" placeholder="Increments: ex 1,2,5"
                                    name="increment" class="form-control" required="">
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
            $("#modalForm").attr("action", '{{ route('autobid.store') }}');
            $('#max_bid').val("");
            $('#min_bid').val("");
            $('#increment').val("");
            $("#intervalModalLabel").text("Create")
            $("#intervalModal").modal("show");
        }

        function edit(element) {
            $("#intervalModalLabel").text("Edit")
            let id = $(element).data('id')
            var url = '{{ route('autobid.update', ':id') }}'
            let newUrl = url.replace(":id", id)
            $("#modalForm").attr("action", newUrl);
            $("#intervalModal").modal("show");

            let max_bid = $(element).data('max_bid')
            let min_bid = $(element).data('min_bid')
            let increment = $(element).data('increment')

            // Fill the form fields with the data
            $('#max_bid').val(max_bid);
            $('#min_bid').val(min_bid);
            $('#increment').val(increment);
        }
    </script>
@endsection

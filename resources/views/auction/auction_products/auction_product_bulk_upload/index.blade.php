@extends('backend.layouts.app')

@section('content')

    <div class="aiz-titlebar mt-2 mb-4">
      <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h3">{{ translate('Bulk Auction Products Upload') }}</h1>
        </div>
      </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Errors:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <div class="card">
        <div class="card-body">
            <table class="table aiz-table mb-0 footable footable-1 breakpoint-xl" style="font-size: 14px; background-color: rgb(204, 229, 255); border-color: rgb(184, 218, 255);">
                <tbody>
            <tr> 
                <td style="display: table-cell;" class="footable-first-visible footable-last-visible">1. Generate the skeleton file by selecting category, it will automaticaly download in your system , and fill it with data.</td></tr><tr>
                    
                <td style="display: table-cell;" class="footable-first-visible footable-last-visible">2. You can download the example file to understand how the data must be filled.</td></tr><tr>
                    
                <td style="display: table-cell;" class="footable-first-visible footable-last-visible">3. Once you have downloaded and filled the skeleton file, upload it in the form below and submit.</td></tr><tr>
                  
                <td style="display: table-cell;" class="footable-first-visible footable-last-visible">4. After uploading products you need to edit them and set products images and choices.</td></tr><tr>
                    
                <td style="display: table-cell;" class="footable-first-visible footable-last-visible text-danger" >5. This skeleton file is generated for the selected category. Please don't change the category_id because the attributes are dynamically appended to this file according to the category."</td>
                
            </tr>

            <tr>
                <td style="display: table-cell;" class="footable-first-visible footable-last-visible">
                    <form action="{{route('admin.generate_auction_sheet')}}" method="post">
                        @csrf
                        <div class="h-250px overflow-auto c-scrollbar-light">
                            <select name="category_id" class="form-control" value="">
                                @foreach ($categories as $category)
                                    <option disabled value="{{ $category->id }}">{{ $category->name }}</option>
                                    @foreach ($category->childrenCategories as $childCategory)
                                        <option value="{{ $childCategory->id }}">
                                            -- {{ $childCategory->name }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-primary">Generate Format</button>
                    </form>
                </td>
            </tr>
                
            </tbody>
        </table>
        
        <div class="alert" style="color: #004085;background-color: #cce5ff;border-color: #b8daff;margin-bottom:0;margin-top:10px;">
            <strong>{{translate('Step 2')}}:</strong>
            <p>1. {{translate('Category and Brand should be in numerical id')}}.</p>
            <p>2. {{translate('You can download the pdf to get Category and Brand id')}}.</p>
        </div>
        <br>
        <div class="">
            <a href="{{ route('pdf.download_category') }}"><button class="btn btn-info">{{translate('Download Category')}}</button></a>
            <a href="{{ route('pdf.download_brand') }}"><button class="btn btn-info">{{translate('Download Brand')}}</button></a>
        </div>
        <br>
            
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="col text-center text-md-left">
                <h5 class="mb-md-0 h6">{{ translate('Upload CSV File') }}</h5>
            </div>
        </div>
        <div class="card-body">
            <form class="form-horizontal" action="{{ route('admin.bulk_auction_product_upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <label class="col-md-2 col-form-label">{{ translate('CSV') }}</label>
                    <div class="col-sm-10">
                        <div class="custom-file">
    						<label class="custom-file-label">
    							<input type="file" name="bulk_file" class="custom-file-input" required>
    							<span class="custom-file-name">{{ translate('Choose File')}}</span>
    						</label>
    					</div>
                    </div>
                </div>
                <div class="form-group mb-0 text-right">
                    <button type="submit" class="btn btn-primary">{{translate('Upload CSV')}}</button>
                </div>
            </form>
        </div>
    </div>
   
@endsection

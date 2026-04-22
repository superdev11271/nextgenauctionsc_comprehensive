@extends('backend.layouts.app')

@section('content')

<div class="aiz-titlebar text-left mt-2 mb-3">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3">{{ translate('About us') }}</h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">{{ translate('About us Setting') }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Title -->
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ translate('Title') }}</label>
                        <div class="col-md-8">
                            <input type="hidden" name="types[]" value="about_title">
                            <input type="text" oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'')" class="form-control" placeholder="{{ translate('Title') }}" name="about_title" value="{{ get_setting('about_title') }}">
                        </div>
                    </div>

                    <!-- About Image -->
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ translate('Heading Image') }}</label>
                        <div class="col-md-8">
                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="types[]" value="about_heading_image">
                                <input type="hidden" name="about_heading_image" class="selected-files" value="{{ get_setting('about_heading_image') }}">
                            </div>
                            <div class="file-preview box sm"></div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">{{ translate('Description') }}</label>
                        <div class="col-md-8">
                            <input type="hidden" name="types[]" value="about_description">
                            <textarea class="aiz-text-editor" name="about_description" placeholder="Type..." data-min-height="150">
                                {!! get_setting('about_description', null) !!}
                            </textarea>
                        </div>
                    </div>

                    <!-- Initial About Section -->
                    @php
                       
                        $aboutSections = json_decode(get_setting('about_sections'), true);
                        if($aboutSections ){
                            $aboutdata = group_about_sections($aboutSections);
                        }
                        if (!empty($aboutdata)) {
                            $firstSection = array_shift($aboutdata);
                            $otherSections = $aboutdata;
                        } else {
                            $firstSection = null;
                            $remainingSections = [];
                        }
                    @endphp
                    <div id="about-section-template" class="about-section">
                        <div class="border-top pt-3">
							<input type="hidden" name="types[]" value="about_sections">
                            
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">{{ translate('Title') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" placeholder="{{ translate('Title') }}" name="about_sections[][title]" oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'')" value=" {{$firstSection['title'] ?? ''}}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">{{ translate('Heading') }}</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" placeholder="{{ translate('Heading') }}" name="about_sections[][heading]" oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'')" value=" {{$firstSection['heading'] ?? ''}}">
                                </div>
                            </div>

                            <!-- About Image -->
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">{{ translate('About Image') }}</label>
                                <div class="col-md-8">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                        <input type="hidden" name="about_sections[][about_image]" class="selected-files" value="{{ $firstSection['about_image'] ?? ''}}">
                                    </div>
                                    <div class="file-preview box sm"></div>
                                </div>
                            </div>

                            <!-- About Content -->
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">{{ translate('Description') }} <i class="las la-info text-danger" data-toggle="tooltip" title="The system takes the first sentence from this information and displays it as a heading in the respective section of the frontend about page."></i> </label>
                                <div class="col-md-8">
                                    <textarea class="aiz-text-editor" name="about_sections[][aboutus_content]" placeholder="Type..." data-min-height="150">
                                        {!! $firstSection['aboutus_content'] ?? '' !!}
                                    </textarea>
                                </div>
                            </div>
                            
                            <!-- Remove Section Button -->
                            <div class="form-group row">
                                <div class="col-md-12 text-right">
                                    <button type="button" class="btn btn-danger btn-sm delete-section d-none" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                                        <i class="las la-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="more-about-sections">
                        @if(isset($otherSections))
                            @foreach($otherSections as $section)
                            <div class="border-top pt-3">

                                <div class="about-section">
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">{{ translate('Title') }}</label>
                                        <div class="col-md-8">
                                            
                                            <input type="text" class="form-control" placeholder="{{ translate('Title') }}" name="about_sections[][title]" oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'')" value="{{ $section['title'] ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">{{ translate('Heading') }}</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" placeholder="{{ translate('Heading') }}" name="about_sections[][heading]" oninput="this.value=this.value.replace(/[^A-Za-z\s]/g,'')" value=" {{$section['heading'] ?? ''}}">
                                        </div>
                                    </div>
                                    <!-- About Image -->
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">{{ translate('About Image') }}</label>
                                        <div class="col-md-8">
                                            <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse') }}</div>
                                                </div>
                                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                                <input type="hidden" name="about_sections[][about_image]" class="selected-files" value="{{ $section['about_image'] ?? ''}}">
                                            </div>
                                            <div class="file-preview box sm"></div>
                                        </div>
                                    </div>

                                    <!-- About Content -->
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label">{{ translate('Description') }} <i class="las la-info text-danger" data-toggle="tooltip" title="The system takes the first sentence from this information and displays it as a heading in the respective section of the frontend about page."></i> </label>
                                        <div class="col-md-8">
                                            <textarea class="aiz-text-editor" name="about_sections[][aboutus_content]" placeholder="Type..." data-min-height="150">
                                                {!! $section['aboutus_content'] ?? '' !!}
                                            </textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12 text-right">
                                            <div class="col-md-12 text-right">
                                                <button type="button" class="btn btn-danger btn-sm delete-section" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">
                                                    <i class="las la-trash"></i>
                                                </button>
                                            </div>                                    
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @endif

                    </div>

                    <button class="btn btn-success add-about-section" type="button" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;"> + {{ translate('Add More') }} </button>

                    <div class="mt-4 text-right">
                        <button type="submit" class="btn btn-success w-230px btn-md rounded-2 fs-14 fw-700 shadow-success" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;">{{ translate('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function() {
        var aboutSectionTemplate = $("#about-section-template").clone().removeAttr('id').hide();

        aboutSectionTemplate.find('.delete-section').on('click', function() {
            $(this).closest(".about-section").remove(); 
        });

        $('.add-about-section').on('click', function() {
            var newSection = aboutSectionTemplate.clone().show(); 
            newSection.find('.delete-section').removeClass('d-none');
            $("#more-about-sections").append(newSection);
        });

        $("#more-about-sections").on('click', '.delete-section', function() {
            $(this).closest(".about-section").remove();
        });
    });
</script>
@endsection

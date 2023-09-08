<section id="office_detail_section" class="parallax-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="brand-card">
                    <div class="brand-card-header bg-stack-overflow p-0 pt-1">
                        <h6 style="color: white; font-weight:bold" id="section_title">कार्यक्रम प्रगतिहरु</h6>
                    </div>
                    <form method="POST" action="{{ url('admin/office-report') }}" accept-charset="UTF-8" target="_blank">
                        @csrf
                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="report_type" class="font-weight-bold">विवरण प्रकार</label>
                                    <select class="form-control" name="report_type" id="report_type">
                                        <option value="milestone" selected>योजना क्रियाकलाप अनुसार प्रगति विवरण</option>
                                        <option value="progress">वित्तीय तथा भौतिक प्रगति विवरण</option>
                                        <option value="law">ऐन कानुन निर्माणको अवस्था</option>
                                        {{-- <option value="bidding">सार्वजनिक खरीद तथा ठेक्का व्यवस्थापन</option> --}}
                                        <option value="office">कार्यालय व्यवस्थापन विवरण</option>
                                        <option value="darbandi">जनसक्ति दरबन्दी विवरण</option>
                                        <option value="ministry-budget">मन्त्रालय बजेट विवरण</option>
                                        <option value="office-initiative">पहल/अपेक्षाहरु</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="ministry_id">मन्त्रालयको नाम</label>
                                    <select class="form-control" id="ministry_id" name="ministry_id">
                                        <option value=""> -Select Ministry -</option>
                                        @foreach ($ministries as $ministry)
                                            <option value="{{ $ministry->id }}"> {{ $ministry->name_lc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="fiscal_year_id">आर्थिक वर्ष</label>
                                    <select class="form-control" id="fiscal_year_id" name="fiscal_year_id">
                                       
                                        @foreach ($fiscal_years as $option)
                                            @if (intval($fiscal_year_id) === $option->getKey())
                                                <option value="{{ $fiscal_year_id }}" selected>{{ $option->code }}</option>
                                            @else
                                                <option value="{{ $option->getKey() }}">{{ $option->code }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Print Pdf</button>
                            <button type="button" onclick="excelPrint()" class="btn btn-primary">Print excel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

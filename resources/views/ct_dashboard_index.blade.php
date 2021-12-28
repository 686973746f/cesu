@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{route('ct.dashboard.index')}}" method="GET">
        <div class="card">
            <div class="card-header">Contact Tracing</div>
            <div class="card-body">
                <div class="form-group">
                    <label for="pid">Search Case Investigation Form ID</label>
                    <input type="text" class="form-control" name="pid" id="pid" value="{{(request()->input('pid'))}}">
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </div>
    </form>

    @if(request()->input('pid') && $search_is_valid)
    <div class="card mt-3">
        <div class="card-header">Displaying Contact Tracing Result for </div>
        <div class="card-body">
            <div id="accordianId" role="tablist" aria-multiselectable="true">
                <div class="card">
                    <div class="card-header" role="tab" id="section1HeaderId">
                        <div class="d-flex justify-content-between">
                            <div>
                                <a data-toggle="collapse" data-parent="#accordianId" href="#section1ContentId" aria-expanded="true" aria-controls="section1ContentId">
                                    {{$form->records->getName()}}
                                </a>
                            </div>
                            <div>
                                <a href="{{route('forms.edit', ['form' => $form->id])}}">View CIF</a>
                            </div>
                        </div>
                        

                    </div>
                    <div id="section1ContentId" class="collapse in" role="tabpanel" aria-labelledby="section1HeaderId">
                        <div class="card-body">
                            <div class="card">
                                <div class="card-header">Primary Close Contact</div>
                                <div class="card-body">
                                    @foreach($form->getContactTracingList() as $k => $pri)
                                    <div id="acc_{{$k}}" role="tablist" aria-multiselectable="true">
                                        <div class="card {{($loop->last) ? '' : 'mb-3'}}">
                                            <div class="card-header" role="tab" id="section1HeaderId">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <a data-toggle="collapse" data-parent="#acc_{{$k}}" href="#sec_{{$k}}" aria-expanded="true" aria-controls="sec_{{$k}}">
                                                            {{$k+1}}.) {{$pri->records->getName()}}
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <a href="{{route('forms.edit', ['form' => $pri->id])}}">View CIF</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="sec_{{$k}}" class="collapse in" role="tabpanel" aria-labelledby="section1HeaderId">
                                                <div class="card-body">
                                                    <div class="card">
                                                        <div class="card-header">Secondary Close Contact</div>
                                                        <div class="card-body">
                                                            @if($pri->getContactTracingList())
                                                                @foreach($pri->getContactTracing() as $x)
                                                                <div id="accordianId" role="tablist" aria-multiselectable="true">
                                                                    <div class="card">
                                                                        <div class="card-header" role="tab" id="section1HeaderId">
                                                                            <a data-toggle="collapse" data-parent="#accordianId" href="#section1ContentId" aria-expanded="true" aria-controls="section1ContentId">
                                                                                {{$x->records->getName()}}
                                                                            </a>
                                                                        </div>
                                                                        <div id="section1ContentId" class="collapse in" role="tabpanel" aria-labelledby="section1HeaderId">
                                                                            <div class="card-body">
                                                                                
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                            @else
                                                            No Secondary Close Contact Found.
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
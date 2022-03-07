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
        <div class="card-header">Exposure History Found for {{$form->form->records->getName()}}</div>
        <div class="card-body">
            <div class="card">
                <div class="card-header">Primary CC - Total: {{$form->getPrimaryCCList($form->form_id)->count()}}</div>
                <div class="card-body">
                    <div id="mainacc" role="tablist" aria-multiselectable="true">
                        @forelse($form->getPrimaryCCList($form->form_id) as $q)
                        <div class="card {{($loop->first) ? '' : 'mt-3'}}">
                            <div class="card-header" role="tab" id="sectionid_{{$loop->iteration}}">
                                <div class="row">
                                    <div class="col-md-4">
                                        <a data-toggle="collapse" data-parent="#mainacc" href="#sectioncontent_{{$loop->iteration}}" aria-expanded="true" aria-controls="sectioncontent_{{$loop->iteration}}">
                                            Primary CC #{{$loop->iteration}} - {{$q->records->getName()}}
                                        </a>
                                    </div>
                                    <div class="col-md-4 text-center">Exposure Date</div>
                                    <div class="col-md-4 text-right"><a href="{{route('forms.edit', ['form' => $q->id])}}">View CIF</a></div>
                                </div>
                                
                            </div>
                            <div id="sectioncontent_{{$loop->iteration}}" class="collapse in" role="tabpanel" aria-labelledby="sectionid_{{$loop->iteration}}">
                                <div class="card-body">
                                    <div class="card">
                                        <div class="card-header">Secondary CC - Total: {{$form->getPrimaryCCList($q->id)->count()}}</div>
                                        <div class="card-body">
                                            <div id="secacc" role="tablist" aria-multiselectable="true">
                                                @forelse($form->getPrimaryCCList($q->id) as $r)
                                                <div class="card">
                                                    <div class="card-header" role="tab" id="section1HeaderId">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <a data-toggle="collapse" data-parent="#secacc" href="#section2content_{{$loop->iteration}}" aria-expanded="true" aria-controls="section2content_{{$loop->iteration}}">
                                                                    Secondary CC #{{$loop->iteration}} - {{$r->records->getName()}}
                                                                </a>
                                                            </div>
                                                            <div class="col-md-4 text-center">Exposure Date</div>
                                                            <div class="col-md-4 text-right"><a href="{{route('forms.edit', ['form' => $r->id])}}">View CIF</a></div>
                                                        </div>
                                                    </div>
                                                    <div id="section2content_{{$loop->iteration}}" class="collapse in" role="tabpanel" aria-labelledby="section1HeaderId">
                                                        <div class="card-body">
                                                            <div class="card">
                                                                <div class="card-header">Tertiary CC - Total: {{$form->getPrimaryCCList($r->id)->count()}}</div>
                                                                <div class="card-body">
                                                                    @forelse($form->getPrimaryCCList($r->id) as $s)
                                                                    <a href="{{route('forms.edit', ['form' => $s->id])}}">{{$s->records->getName()}} | Exposure Date: </a>
                                                                    @empty
                                                                    <p class="text-center">No Tertiary CC Found under {{$r->records->getName()}}.</p>
                                                                    @endforelse
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @empty
                                                <p class="text-center">No Secondary CC Found under {{$q->records->getName()}}.</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-center">No Primary CC Found.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
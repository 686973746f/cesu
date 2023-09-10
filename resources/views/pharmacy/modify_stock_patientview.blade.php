@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="" method="GET" id="myForm">
            <div class="card">
                <div class="card-header"><b>Issuance of Meds to Patient</b></div>
                <div class="card-body">
                    @if(session('msg'))
                    <div class="alert alert-{{session('msgtype')}}" role="alert">
                        {{session('msg')}}
                    </div>
                    @endif
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td class="bg-light">Name of Patient</td>
                                <td class="text-center"><b><a href="{{route('pharmacy_view_patient', $d->id)}}">{{$d->getName()}}</a></b></td>
                                <td class="bg-light">Patient ID</td>
                                <td class="text-center">#{{$d->id}}</td>
                            </tr>
                            <tr>
                                <td class="bg-light">Age / Sex</td>
                                <td class="text-center">{{$d->getAge()}} / {{$d->sg()}}</td>
                                <td class="bg-light">Birthdate</td>
                                <td class="text-center">{{date('m/d/Y', strtotime($d->bdate))}}</td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <div class="form-group">
                        <label for="">Input or Scan QR of Meds to Issue</label>
                        <input type="text" class="form-control" name="meds" id="meds" autocomplete="off" autofocus>
                    </div>
                    <hr>
                    <div class="form-group">
                      <label for="alt_meds_id">Or manually select from the Inventory</label>
                      <select class="form-control" name="alt_meds_id" id="alt_meds_id">
                        <option value="" disabled {{(is_null(old('alt_meds_id'))) ? 'selected' : ''}}>Choose...</option>
                        @foreach($meds_list as $m)
                        <option value="{{$m->pharmacysupplymaster->sku_code}}">{{$m->pharmacysupplymaster->name}}</option>
                        @endforeach
                      </select>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-block">Submit</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        $('#alt_meds_id').select2({
            theme: 'bootstrap',
        });

        $(document).ready(function () {
            $("#myForm").submit(function (event) {
                var medsValue = $("#meds").val();
                var altMedsValue = $("#alt_meds_id").val();

                // Check if either field is empty
                if (medsValue === "" && altMedsValue === null) {
                    // Prevent the form from submitting
                    event.preventDefault();
                    alert("Please scan or manually input the item to issue before proceeding.");
                }
            });
        });
    </script>
@endsection
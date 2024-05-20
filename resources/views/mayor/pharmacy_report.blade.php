@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <div><b>Report Dashboard</b></div>
                        <div>{{$display_flavor}}</div>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#changeDuration">Change Period</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header">
                                <div><b>Gender and Age Group of Requestors</b></div>
                                <div>{{$display_flavor}}</div>
                            </div>
                            <div class="card-body">
                                <canvas id="age_group_chart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header">
                                <div><b>Types of Requestors</b></div>
                                <div>{{$display_flavor}}</div>
                            </div>
                            <div class="card-body">
                                <canvas id="concerns_chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th colspan="3">
                                        <div>Top Fast Moving Meds</div>
                                        <div>{{$display_flavor}}</div>
                                    </th>
                                </tr>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Item Name</th>
                                    <th>Total Stocks Issued</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach($fm_array as $ind => $fm)
                                @if($ind+1 <= 10)
                                <tr>
                                    <td>#{{$loop->iteration}}</td>
                                    <td><b>{{$fm['name']}}</b></td>
                                    <td>{{$fm['qty_total']}} {{Str::plural('PC', $fm['qty_total'])}}</td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-6">
                        <table class="table table-striped table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th colspan="4">
                                        <div>Top 10 Requestors in Entities (BHS/Hospitals/Others)</div>
                                        <div>{{$display_flavor}}</div>
                                    </th>
                                </tr>
                                <tr class="text-center">
                                    <th>Top</th>
                                    <th>Name</th>
                                    <th>Issued Quantity (in Pieces)</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                @foreach($entities_arr as $ind => $r)
                                @if($ind+1 <= 10)
                                <tr>
                                    <td>#{{$ind+1}}</td>
                                    <td>{{$r['name']}}</td>
                                    <td>{{$r['issued_qty_total']}}</td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="" method="GET">
        <div class="modal fade" id="changeDuration" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="year"><b class="text-danger">*</b>Select Year</label>
                            <select class="form-control" name="year" id="year" required>
                                @foreach(range(date('Y'), 2023) as $y)
                                    <option value="{{$y}}" {{($selected_year == $y) ? 'selected' : ''}}>{{$y}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="type"><b class="text-danger">*</b>Select Type</label>
                            <select class="form-control" name="type" id="type" required>
                                <option value="YEARLY" {{($selected_type == 'YEARLY') ? 'selected' : ''}}>YEARLY (CURRENT)</option>
                                <option value="QUARTERLY" {{($selected_type == 'QUARTERLY') ? 'selected' : ''}}>QUARTERLY</option>
                                <option value="MONTHLY" {{($selected_type == 'MONTHLY') ? 'selected' : ''}}>MONTHLY</option>
                            </select>
                        </div>
                        <div class="form-group d-none" id="squarter">
                            <label for="quarter"><b class="text-danger">*</b>Select Quarter</label>
                            <select class="form-control" name="quarter" id="quarter">
                                <option value="1" {{($selected_quarter == '1') ? 'selected' : ''}}>1ST QUARTER</option>
                                <option value="2" {{($selected_quarter == '2') ? 'selected' : ''}}>2ND QUARTER</option>
                                <option value="3" {{($selected_quarter == '3') ? 'selected' : ''}}>3RD QUARTER</option>
                                <option value="4" {{($selected_quarter == '4') ? 'selected' : ''}}>4TH QUARTER</option>
                            </select>
                        </div>
                        <div class="form-group d-none" id="smonth">
                            <label for="month"><b class="text-danger">*</b>Select Month</label>
                            <select class="form-control" name="month" id="month">
                                <option value="1" {{($selected_month == '1') ? 'selected' : ''}}>JANUARY</option>
                                <option value="2" {{($selected_month == '2') ? 'selected' : ''}}>FEBRUARY</option>
                                <option value="3" {{($selected_month == '3') ? 'selected' : ''}}>MARCH</option>
                                <option value="4" {{($selected_month == '4') ? 'selected' : ''}}>APRIL</option>
                                <option value="5" {{($selected_month == '5') ? 'selected' : ''}}>MAY</option>
                                <option value="6" {{($selected_month == '6') ? 'selected' : ''}}>JUNE</option>
                                <option value="7" {{($selected_month == '7') ? 'selected' : ''}}>JULY</option>
                                <option value="8" {{($selected_month == '8') ? 'selected' : ''}}>AUGUST</option>
                                <option value="9" {{($selected_month == '9') ? 'selected' : ''}}>SEPTEMBER</option>
                                <option value="10" {{($selected_month == '10') ? 'selected' : ''}}>OCTOBER</option>
                                <option value="11" {{($selected_month == '11') ? 'selected' : ''}}>NOVEMBER</option>
                                <option value="12" {{($selected_month == '12') ? 'selected' : ''}}>DECEMBER</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-block">Generate</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        //AGE GROUP BAR CHART
        var male_set = {{json_encode($age_group_set_male)}};
        var female_set = {{json_encode($age_group_set_female)}};

        var ctx = document.getElementById('age_group_chart').getContext('2d');
        var data = {
            labels: ['< 10', '11-20', '21-30', '31-40', '41-50', '51-60', '> 61'],
            datasets: [
                {
                    label: 'Male',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    data: male_set, // Your data for Dataset 1
                },
                {
                    label: 'Female',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    data: female_set, // Your data for Dataset 2
                },
            ],
        };

        var options = {
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
                },
            },
        };

        var stackedBarChart = new Chart(ctx, {
            type: 'bar',
            data: data,
            options: options,
        });

        //REASONS PIE
        var labels = [
            @foreach ($reason_array as $reason)
            '{{ $reason['name'] }}',
            @endforeach
        ];

        var reasonCount = {{json_encode(array_column($reason_array, 'count'))}};

        var dynamicColors = [];
        for (var i = 0; i < reasonCount.length; i++) {
            dynamicColors.push('rgba(' +
                Math.floor(Math.random() * 256) + ',' +
                Math.floor(Math.random() * 256) + ',' +
                Math.floor(Math.random() * 256) + ',' +
                '0.6)');
        }

        var data = {
            labels: labels,
            datasets: [{
                data: reasonCount, // Values for each slice of the pie
                backgroundColor: dynamicColors,
            }],
        };

        // Get the canvas element
        var ctx = document.getElementById('concerns_chart').getContext('2d');

        // Create the pie chart
        var myPieChart = new Chart(ctx, {
            type: 'pie',
            data: data,
        });

        $('#type').change(function (e) { 
            e.preventDefault();
            if($(this).val() == 'YEARLY') {
                $('#squarter').addClass('d-none');
                $('#smonth').addClass('d-none');
                $('#sweek').addClass('d-none');

                $('#quarter').prop('required', false);
                $('#month').prop('required', false);
                $('#week').prop('required', false);

                $('#div2').addClass('col-md-8');
                $('#div2').removeClass('col-md-4');
                $('#div3').addClass('d-none');
            }
            else if($(this).val() == 'QUARTERLY') {
                $('#squarter').removeClass('d-none');
                $('#smonth').addClass('d-none');
                $('#sweek').addClass('d-none');

                $('#quarter').prop('required', true);
                $('#month').prop('required', false);
                $('#week').prop('required', false);

                $('#div2').removeClass('col-md-8');
                $('#div2').addClass('col-md-4');
                $('#div3').removeClass('d-none');
            }
            else if($(this).val() == 'MONTHLY') {
                $('#squarter').addClass('d-none');
                $('#smonth').removeClass('d-none');
                $('#sweek').addClass('d-none');

                $('#div2').removeClass('col-md-8');
                $('#div2').addClass('col-md-4');
                $('#div3').removeClass('d-none');
            }
            else if($(this).val() == 'WEEKLY') {
                $('#squarter').addClass('d-none');
                $('#smonth').addClass('d-none');
                $('#sweek').removeClass('d-none');

                $('#div2').removeClass('col-md-8');
                $('#div2').addClass('col-md-4');
                $('#div3').removeClass('d-none');
            }
        }).trigger('change');
    </script>
@endsection
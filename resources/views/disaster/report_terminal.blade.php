 @extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><b>CSWD Terminal Report</b></div>
        <div class="card-body">
            <div><b>Table 1:</b> Number of affected municipality/city/ barangays, families, and individuals.</div>
            <table class="table table-striped table-bordered">
                <thead class="thead-light text-center">
                    <tr>
                        <th rowspan="2">CITY OF GENERAL TRIAS</th>
                        <th colspan="4">Number of Affected</th>
                    </tr>
                    <tr>
                        <th>Families</th>
                        <th>Individuals</th>
                        <th>Male</th>
                        <th>Female</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <div><b>Table 2:</b> Number of evacuation centers by City/Municipality with a corresponding number of displaced families and persons sheltered.</div>
            <table class="table table-striped table-bordered">
                <thead class="thead-light text-center">
                    <tr>
                        <th rowspan="2">CITY OF GENERAL TRIAS</th>
                        <th rowspan="2">Number of Evacuation Centers</th>
                        <th colspan="2">Inside ECs</th>
                    </tr>
                    <tr>
                        <th>Families</th>
                        <th>Individuals</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <div><b>Table 3:</b> Number of displaced families and persons that opted to stay in the houses of their relatives/friends.</div>
            <table class="table table-striped table-bordered">
                <thead class="thead-light text-center">
                    <tr>
                        <th rowspan="2">CITY OF GENERAL TRIAS</th>
                        <th colspan="2">OUTSIDE ECs</th>
                    </tr>
                    <tr>
                        <th>Families</th>
                        <th>Individuals</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <div><b>Table 4:</b> Number of displaced families and persons inside and outside the displacement site.</div>
            <table class="table table-striped table-bordered">
                <thead class="thead-light text-center">
                    <tr>
                        <th rowspan="2">CITY OF GENERAL TRIAS</th>
                        <th colspan="2">NUMBER OF DISPLACED</th>
                    </tr>
                    <tr>
                        <th>Families</th>
                        <th>Individuals</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <div><b>Table 5:</b> Number of displaced families and persons inside and outside the displacement site.</div>
            <table class="table table-striped table-bordered">
                <thead class="thead-light text-center">
                    <tr>
                        <th>CITY OF GENERAL TRIAS</th>
                        <th>Infant (0-6 mos)</th>
                        <th>Toddler (7 mos. - 2 y/o)</th>
                        <th>Preschoolers (3-5 y/o)</th>
                        <th>School Age (6-12 y/o)</th>
                        <th>Teenage (13-17 y/o)</th>
                        <th>Adult (18-59 y/o)</th>
                        <th>Senior Citizen (60 above)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <div><b>Table 7:</b> Summary of the sectoral breakdown of the displaced population inside the Evacuation Centers.</div>
            <table class="table table-striped table-bordered">
                <thead class="thead-light text-center">
                    <tr>
                        <th>CITY OF GENERAL TRIAS</th>
                        <th>PWD</th>
                        <th>Lactating Mother</th>
                        <th>Pregnant</th>
                        <th>Solo Parent</th>
                        <th>4Ps Beneficiary</th>
                        <th>Indigenous People</th>
                        <th>Child Headed Family</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <div><b>Table 8:</b> Number of totally and partially damaged houses.</div>
            <table class="table table-striped table-bordered">
                <thead class="thead-light text-center">
                    <tr>
                        <th rowspan="2">CITY OF GENERAL TRIAS</th>
                        <th colspan="3">NUMBER OF DAMAGED HOUSES</th>
                    </tr>
                    <tr>
                        <th>TOTAL</th>
                        <th>TOTALLY DAMAGED</th>
                        <th>PARTIALLY DAMAGED</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <div><b>Table 9:</b> Summary of the sectoral breakdown of the displaced population inside the Evacuation Centers.</div>
            <table class="table table-striped table-bordered">
                <thead class="thead-light text-center">
                    <tr>
                        <th rowspan="2">CITY OF GENERAL TRIAS</th>
                        <th colspan="6">COST OF ASSISTANCE</th>
                    </tr>
                    <tr>
                        <th>NGO</th>
                        <th>LGU</th>
                        <th>PROVINCE</th>
                        <th>DSWD</th>
                        <th>OTHERS</th>
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection
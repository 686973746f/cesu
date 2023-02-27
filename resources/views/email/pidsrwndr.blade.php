<div>
    <p>Good Day!</p>
    <p>Please see the attached file for the PIDSR Weekly Notifiable Diseases Report.</p>
    <p>List:</p>
    @foreach($list as $l)
        @php
        $afp_list = [];
        if($l['type'] == 'AFP') {
            array_push($afp_list, $l);
        } else if($l['type'] == 'AFP') {
            array_push($afp_list, $l);
        }   
        @endphp
    @endforeach
    <p>-</p>
    <p>Note: Computer Generated file, Do Not Reply. Made possible by Christian James Historillo.</p>
</div>
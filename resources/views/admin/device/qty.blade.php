<table class="table">
    <thead>
    <tr>
        <th>Package</th>
        <th>Min Qty</th>
        <th>Max Qty</th>
    </tr>
    </thead>
    <tbody>
    @foreach($combinations as $combination)
        @php
            $comb=implode('-',$combination);
        @endphp
        <tr>
            <td>{{$packageName[$combination[0]]}}-{{$roomName[$combination[1]]}}</td>
            <td><input class="form-control" type="text"
                       value="{{(!empty($selected_pkgs[$comb]))?$selected_pkgs[$comb]['min_qty']:''}}"
                       name="package[{{$comb}}][min_qty]"/></td>
            <td><input class="form-control" type="text"
                       value="{{(!empty($selected_pkgs[$comb]))?$selected_pkgs[$comb]['max_qty']:''}}"
                       name="package[{{implode('-',$combination)}}][max_qty]"/></td>
        </tr>
    @endforeach
    </tbody>
</table>

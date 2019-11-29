<script>
    $(document).ready(function(){
        $('#update').click(function(){
            event.preventDefault();
            var url = "{{url('/employees/editcoverletterpdf/update/')}}";

            $('#coverfrom').attr('action',url);

            $('#coverfrom')[0].submit();

        });
    });
    </script>



    <div class="col-sm-3">
        <button class="btn btn-danger" id="update">Update</button>
    </div>

    /employee/editvisaform/update





      {{--<td data-label="Category">--}}
                                        {{--<?php $arr = ''; ?>--}}
                                        {{--@if($d->newworker === 1)--}}
                                            {{--<?php $arr = 'New Worker'.', ' ?>--}}
                                        {{--@endif--}}
                                        {{--@if($d->newcontract === 1)--}}
                                            {{--<?php $arr .= 'New Contract'.', ' ?>--}}
                                        {{--@endif--}}
                                        {{--@if($d->cancelletion === 1)--}}
                                            {{--<?php $arr .= 'Cancellation'.', ' ?>--}}
                                        {{--@endif--}}
                                        {{--<?php echo rtrim(implode(',',array_unique(explode(',', $arr))), ', '); ?>--}}
                                    {{--</td>--}}

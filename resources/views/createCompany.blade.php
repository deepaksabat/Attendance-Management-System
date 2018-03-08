@extends('login')

@section('content')
    {!! HTML::style('css/chosen.css') !!}
    {!! HTML::script('js/chosen.jquery.min.js') !!}
    <script>
        $(function() {
            $('.chosen-select').chosen();
            $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
        });
    </script>

    <?php
$timezones = array(
        'Pacific/Midway'       => "(GMT-11:00) Midway Island",
        'US/Samoa'             => "(GMT-11:00) Samoa",
        'US/Hawaii'            => "(GMT-10:00) Hawaii",
        'US/Alaska'            => "(GMT-09:00) Alaska",
        'US/Pacific'           => "(GMT-08:00) Pacific Time (US &amp; Canada)",
        'America/Tijuana'      => "(GMT-08:00) Tijuana",
        'US/Arizona'           => "(GMT-07:00) Arizona",
        'US/Mountain'          => "(GMT-07:00) Mountain Time (US &amp; Canada)",
        'America/Chihuahua'    => "(GMT-07:00) Chihuahua",
        'America/Mazatlan'     => "(GMT-07:00) Mazatlan",
        'America/Mexico_City'  => "(GMT-06:00) Mexico City",
        'America/Monterrey'    => "(GMT-06:00) Monterrey",
        'Canada/Saskatchewan'  => "(GMT-06:00) Saskatchewan",
        'US/Central'           => "(GMT-06:00) Central Time (US &amp; Canada)",
        'US/Eastern'           => "(GMT-05:00) Eastern Time (US &amp; Canada)",
        'US/East-Indiana'      => "(GMT-05:00) Indiana (East)",
        'America/Bogota'       => "(GMT-05:00) Bogota",
        'America/Lima'         => "(GMT-05:00) Lima",
        'America/Caracas'      => "(GMT-04:30) Caracas",
        'Canada/Atlantic'      => "(GMT-04:00) Atlantic Time (Canada)",
        'America/La_Paz'       => "(GMT-04:00) La Paz",
        'America/Santiago'     => "(GMT-04:00) Santiago",
        'Canada/Newfoundland'  => "(GMT-03:30) Newfoundland",
        'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",
        'Greenland'            => "(GMT-03:00) Greenland",
        'Atlantic/Stanley'     => "(GMT-02:00) Stanley",
        'Atlantic/Azores'      => "(GMT-01:00) Azores",
        'Atlantic/Cape_Verde'  => "(GMT-01:00) Cape Verde Is.",
        'Africa/Casablanca'    => "(GMT) Casablanca",
        'Europe/Dublin'        => "(GMT) Dublin",
        'Europe/Lisbon'        => "(GMT) Lisbon",
        'Europe/London'        => "(GMT) London",
        'Africa/Monrovia'      => "(GMT) Monrovia",
        'Europe/Amsterdam'     => "(GMT+01:00) Amsterdam",
        'Europe/Belgrade'      => "(GMT+01:00) Belgrade",
        'Europe/Berlin'        => "(GMT+01:00) Berlin",
        'Europe/Bratislava'    => "(GMT+01:00) Bratislava",
        'Europe/Brussels'      => "(GMT+01:00) Brussels",
        'Europe/Budapest'      => "(GMT+01:00) Budapest",
        'Europe/Copenhagen'    => "(GMT+01:00) Copenhagen",
        'Europe/Ljubljana'     => "(GMT+01:00) Ljubljana",
        'Europe/Madrid'        => "(GMT+01:00) Madrid",
        'Europe/Paris'         => "(GMT+01:00) Paris",
        'Europe/Prague'        => "(GMT+01:00) Prague",
        'Europe/Rome'          => "(GMT+01:00) Rome",
        'Europe/Sarajevo'      => "(GMT+01:00) Sarajevo",
        'Europe/Skopje'        => "(GMT+01:00) Skopje",
        'Europe/Stockholm'     => "(GMT+01:00) Stockholm",
        'Europe/Vienna'        => "(GMT+01:00) Vienna",
        'Europe/Warsaw'        => "(GMT+01:00) Warsaw",
        'Europe/Zagreb'        => "(GMT+01:00) Zagreb",
        'Europe/Athens'        => "(GMT+02:00) Athens",
        'Europe/Bucharest'     => "(GMT+02:00) Bucharest",
        'Africa/Cairo'         => "(GMT+02:00) Cairo",
        'Africa/Harare'        => "(GMT+02:00) Harare",
        'Europe/Helsinki'      => "(GMT+02:00) Helsinki",
        'Europe/Istanbul'      => "(GMT+02:00) Istanbul",
        'Asia/Jerusalem'       => "(GMT+02:00) Jerusalem",
        'Europe/Kiev'          => "(GMT+02:00) Kyiv",
        'Europe/Minsk'         => "(GMT+02:00) Minsk",
        'Europe/Riga'          => "(GMT+02:00) Riga",
        'Europe/Sofia'         => "(GMT+02:00) Sofia",
        'Europe/Tallinn'       => "(GMT+02:00) Tallinn",
        'Europe/Vilnius'       => "(GMT+02:00) Vilnius",
        'Asia/Baghdad'         => "(GMT+03:00) Baghdad",
        'Asia/Kuwait'          => "(GMT+03:00) Kuwait",
        'Africa/Nairobi'       => "(GMT+03:00) Nairobi",
        'Asia/Riyadh'          => "(GMT+03:00) Riyadh",
        'Asia/Tehran'          => "(GMT+03:30) Tehran",
        'Europe/Moscow'        => "(GMT+04:00) Moscow",
        'Asia/Baku'            => "(GMT+04:00) Baku",
        'Europe/Volgograd'     => "(GMT+04:00) Volgograd",
        'Asia/Muscat'          => "(GMT+04:00) Muscat",
        'Asia/Tbilisi'         => "(GMT+04:00) Tbilisi",
        'Asia/Yerevan'         => "(GMT+04:00) Yerevan",
        'Asia/Kabul'           => "(GMT+04:30) Kabul",
        'Asia/Karachi'         => "(GMT+05:00) Karachi",
        'Asia/Tashkent'        => "(GMT+05:00) Tashkent",
        'Asia/Kolkata'         => "(GMT+05:30) Kolkata",
        'Asia/Kathmandu'       => "(GMT+05:45) Kathmandu",
        'Asia/Yekaterinburg'   => "(GMT+06:00) Ekaterinburg",
        'Asia/Almaty'          => "(GMT+06:00) Almaty",
        'Asia/Dhaka'           => "(GMT+06:00) Dhaka",
        'Asia/Novosibirsk'     => "(GMT+07:00) Novosibirsk",
        'Asia/Bangkok'         => "(GMT+07:00) Bangkok",
        'Asia/Jakarta'         => "(GMT+07:00) Jakarta",
        'Asia/Krasnoyarsk'     => "(GMT+08:00) Krasnoyarsk",
        'Asia/Chongqing'       => "(GMT+08:00) Chongqing",
        'Asia/Hong_Kong'       => "(GMT+08:00) Hong Kong",
        'Asia/Kuala_Lumpur'    => "(GMT+08:00) Kuala Lumpur",
        'Australia/Perth'      => "(GMT+08:00) Perth",
        'Asia/Singapore'       => "(GMT+08:00) Singapore",
        'Asia/Taipei'          => "(GMT+08:00) Taipei",
        'Asia/Ulaanbaatar'     => "(GMT+08:00) Ulaan Bataar",
        'Asia/Urumqi'          => "(GMT+08:00) Urumqi",
        'Asia/Irkutsk'         => "(GMT+09:00) Irkutsk",
        'Asia/Seoul'           => "(GMT+09:00) Seoul",
        'Asia/Tokyo'           => "(GMT+09:00) Tokyo",
        'Australia/Adelaide'   => "(GMT+09:30) Adelaide",
        'Australia/Darwin'     => "(GMT+09:30) Darwin",
        'Asia/Yakutsk'         => "(GMT+10:00) Yakutsk",
        'Australia/Brisbane'   => "(GMT+10:00) Brisbane",
        'Australia/Canberra'   => "(GMT+10:00) Canberra",
        'Pacific/Guam'         => "(GMT+10:00) Guam",
        'Australia/Hobart'     => "(GMT+10:00) Hobart",
        'Australia/Melbourne'  => "(GMT+10:00) Melbourne",
        'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby",
        'Australia/Sydney'     => "(GMT+10:00) Sydney",
        'Asia/Vladivostok'     => "(GMT+11:00) Vladivostok",
        'Asia/Magadan'         => "(GMT+12:00) Magadan",
        'Pacific/Auckland'     => "(GMT+12:00) Auckland",
        'Pacific/Fiji'         => "(GMT+12:00) Fiji",
);
?>
    <div class="col-md-3">
    </div>
    <div class="col-md-6" >

            {!! Form::open(array('accept-charset' => 'utf-8', 'class' => 'form-horizontal', 'url' => 'login/create-company')) !!}
            <fieldset>
                <div class="form-group">
                    <label for="company_name" class="col-sm-4 control-label">Company Name</label>
                    <div class="col-sm-6">
                        <input type="text" required class="form-control input" name="company_name" id="company_name" placeholder="company name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="company_user_name" class="col-sm-4 control-label">Company User Name</label>
                    <div class="col-sm-6">
                        <input type="text" required class="form-control input" name="username" id="company_user_name" placeholder="company user name">
                    </div>
                </div>
                <div class="form-group">
                    <label for="company_pass" class="col-sm-4 control-label">User Password</label>
                    <div class="col-sm-6">
                        <input type="password" required class="form-control" id="password" name="password" placeholder="User password">
                    </div>
                </div>
                <div class="form-group">
                    <label for="company_email" class="col-sm-4 control-label">Company Email</label>
                    <div class="col-sm-6">
                        <input type="email" required class="form-control input" id="company_email" name="company_email" placeholder="Company Email">
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone" class="col-sm-4 control-label">Phone Number</label>
                    <div class="col-sm-6">
                        <input type="text" required class="form-control number" id="phone" name="phone" placeholder="Phone Number">
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone" class="col-sm-4 control-label">Time Zone</label>
                    <div class="col-sm-6">
                        <select name="time_zone" required class="form-control chosen-select" tabindex="2">
                            <option value="">Please select a timezone</option>
                            <?php foreach($timezones as $key => $timezone){ ?>
                                <option value="<?php echo $key?>"><?php echo $timezone?></option>
                         <?php   }?>
                        </select>
                    </div>
                </div>

                <div class="form-group form-actions">
                    <div class="col-sm-offset-4 col-sm-6">
                        <button type="submit" class="btn btn-primary">Create</button>
                        <button class="btn btn-default" type="reset">Cancel</button>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
    <div class="col-md-3">
    </div>
    <script>
        $(function() {
            $(".input").bind("keyup blur", function() {
                var $th = $(this);
                $th.val($th.val().replace(/[^A-z0-9,#. _@-]/g, function(str) {
                    return '';
                }));
            });
        })
        $(function() {
            $(".number").bind("keyup blur", function() {
                var $th = $(this);
                $th.val($th.val().replace(/[^0-9-+.]/g, function(str) {
                    return '';
                }));
            });
        })
    </script>
<?php $message_error=Session::get('flashError'); if ($message_error) { ?>
<script type="text/javascript">
    $(document).ready(function() {
        $.pnotify.defaults.styling = "bootstrap3";
        $.pnotify({
            title: 'Error',
            text: '<?php echo $message_error ?>',
            type: 'error',
            delay: 3000

        });
    });
</script>

<?php } ?>
<?php $message_success=Session::get('flashSuccess');; if ($message_success) { ?>
<script type="text/javascript">
    $(document).ready(function() {
        $.pnotify.defaults.styling = "bootstrap3";
        $.pnotify({
            title: 'Message',
            text: '<?php echo $message_success ?>',
            type: 'success',
            delay: 3000

        });
    });
</script>

<?php } ?>
@endsection

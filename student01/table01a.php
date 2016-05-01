<?php

echo showForm();

function showForm()
{
    return "<!DOCTYPE html><html>
        <head><title>Table01</title></head><body><style>
        .robotext {font-weight: bold; font-size: 9pt; color: #999999; font-family: Arial, Helvetica, sans-serif; text-decoration: none}
        .robolink:link {font-weight: bold; font-size: 9pt; color: #999999; font-family: Arial, Helvetica, sans-serif; text-decoration: none}
        .robolink:hover {font-weight: bold; font-size: 9pt; color: #979653; font-family: Arial, Helvetica, sans-serif; text-decoration: underline}
        .robolink:visited {font-weight: bold; font-size: 9pt; color: #979653; font-family: Arial, Helvetica, sans-serif; text-decoration: none}
        </style>
        <script language='Javascript'>
            function validate(){
                var allok = true;
                document.basic.Submit.disabled='disabled';
                return true;}
        </script>

        <form name='basic' method='Post' action='table01.php' onSubmit='return validate();'>
        <table border=0 cellpadding=5 cellspacing=0>
        <tr><td>user_id</td><td><input type='edit' name='user_id' value='' size=20></td></tr>
        <tr><td>location_id</td><td><input type='edit' name='location_id' value='' size=20></td></tr>
        <tr><td>ins_co</td><td><input type='edit' name='ins_co' value='' size=20></td></tr>
        <tr><td>ins_agency</td><td><input type='edit' name='ins_agency' value='' size=20></td></tr>
        <tr><td>ins_agency_phone</td><td><input type='edit' name='ins_agency_phone' value='' size=20></td></tr>
        <tr><td>ins_agency_www</td><td><input type='edit' name='ins_agency_www' value='' size=20></td></tr>
        <tr><td>amount_paid</td><td><input type='edit' name='amount_paid' value='' size=20></td></tr>
        <tr><td>amount_per</td><td><input type='edit' name='amount_per' value='' size=20></td></tr>
        <tr><td>address</td><td><input type='edit' name='address' value='' size=20></td></tr>
        <tr><td>city</td><td><input type='edit' name='city' value='' size=20></td></tr>
        <tr><td>state</td><td><input type='edit' name='state' value='' size=20></td></tr>
        <tr><td>zip_code</td><td><input type='edit' name='zip_code' value='' size=20></td></tr>
        <tr><td>year_built</td><td><input type='edit' name='year_built' value='' size=20></td></tr>
        <tr><td>exterior</td><td><input type='edit' name='exterior' value='' size=20></td></tr>
        <tr><td>condition</td><td><input type='edit' name='condition' value='' size=20></td></tr>
        <tr><td>structure_type</td><td><input type='edit' name='structure_type' value='' size=20></td></tr>
        <tr><td>weather_risk</td><td><input type='edit' name='weather_risk' value='' size=20></td></tr>
        <tr><td>fire_risk</td><td><input type='edit' name='fire_risk' value='' size=20></td></tr>
        <tr><td>owner_smokes</td><td><input type='edit' name='owner_smokes' value='' size=20></td></tr>
        <tr><td>owner_credit</td><td><input type='edit' name='owner_credit' value='' size=20></td></tr>
        <tr><td>owner_claims</td><td><input type='edit' name='owner_claims' value='' size=20></td></tr>
        <tr><td align=center><input type='reset' name='Reset' value='Reset'></td><td align=center><input type=submit name='Submit' value='Submit'></td></tr>
        <tr><td colspan=2 class=robotext><a href='http://www.phpform.info' class='robolink'>HTML/PHP Form Generator</a> from ROBO Design Solutions</td></tr>
        </table></form></body></html>";
}
?>
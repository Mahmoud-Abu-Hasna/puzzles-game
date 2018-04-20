<?php
// Turn on output buffering
ob_start();
//Get the ipconfig details using system commond
system('ipconfig /all');

// Capture the output into a variable
$mycom = ob_get_contents();
// Clean (erase) the output buffer
ob_clean();

$findme = "Physical";
//Search the "Physical" | Find the position of Physical text
$pmac = strpos($mycom, $findme);

// Get Physical Address
$mac = substr($mycom, ($pmac + 36), 17);
?>
<form id="submit_mac" method="post" action="{{ route('postReferralFromUser') }}" hidden >
    {{ csrf_field() }}
    <input type="hidden" name="mac_address" value="<?= $mac ?>" />
    <input type="hidden" name="referral_code" value="{{ $referral_code }}" />
</form>

<div> redirecting.....</div>
<script>


    setTimeout(submitMacForm, 500);


    function submitMacForm() {
        document.getElementById("myForm").submit();
    }
</script>
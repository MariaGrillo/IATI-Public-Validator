Ruleset:
<form method="get">
<input type="hidden" name="test" value="rulesets" />
<select name="ruleset">
    <option value="standard">Standard</option>
    <option value="dfid">DFID</option>
</select>
<input type="submit" value="Go" class="btn btn-primary"/>
</form>


<?php


if (array_key_exists('ruleset', $_GET) && in_array($_GET['ruleset'], array('dfid', 'standard'))) {
?>

<p>
    <?php if ($_GET['ruleset'] == 'dfid') { ?>
    <a href="https://gist.github.com/Bjwebb/9683484">
         DFID Ruleset</a>
    <?php } else if ($_GET['ruleset'] == 'standard') { ?>
    <a href="https://gist.github.com/Bjwebb/9952268">
         Standard Ruleset</a>
    <?php } ?>
</p>

<ul class="nav nav-tabs" id="myTab">
    <li class="active"><a href="#status">Status</a></li>
    <li><a href="#details">Details</a></li>
</ul>
 
<div class="tab-content">
  <div class="tab-pane active" id="status">
<?php

    require_once 'IATI-Rulesets/testrules.php';

    $file_path = $_SESSION['uploadedfilepath']; //Sanitise/Check this?
    require_once 'functions/get_xml.php';
    $xml = get_xml($file_path);
    if($xml === FALSE) return FALSE; // Need this to prevent entity security problem

    $ruleset = json_decode(file_get_contents('IATI-Rulesets/rulesets/'.$_GET['ruleset'].'.json'));
    $activity_results = array();
    $activity_nodes = $xml->childNodes->item(0)->getElementsByTagName('iati-activity');
    $activities = count($activity_nodes);
    $activities_with_errors = 0;
    $rules = 0;
    $rules_failed = 0;
    foreach ($activity_nodes as $activity) {
        $doc = new DOMDocument;
        $doc->appendChild($doc->importNode($activity,true));
        $result = test_ruleset_dom($ruleset, $doc);
        $rules += $result['rules_total'];
        $rules_failed += $result['rules_failed'];
        if ($result['rules_failed'] > 0)  $activities_with_errors += 1;
        $result['iati-identifier'] = $activity->getElementsByTagName('iati-identifier')->item(0)->textContent;
        $activity_results[] = $result;
    }
    if ($activities_with_errors == 0) { ?>
        <div class="alert alert-success">
            <strong>Great.</strong>
            This file passes this ruleset.
        </div>
    <?php }
    else { ?>
        <div class="alert alert-error">
            This file does NOT pass this ruleset.
        </div>
    <?php }
    echo "$activities_with_errors/$activities activities have errors </br>";
    echo "Overall $rules_failed/$rules tests failed.</br>";
    if ($rules_failed > 0) {
?>
    <table class="table">
        <thead>
            <th>Activity ID</th>
            <th>Tests Failed</th>
            <th>Tests Total</th>
        </thead>
        <tbody>
        <?php foreach ($activity_results as $result) {
            if ($result['rules_failed'] > 0) { ?>
            <tr>
                <td><?php echo $result['iati-identifier']; ?></td>
                <td><?php echo $result['rules_failed']; ?></td>
                <td><?php echo $result['rules_total']; ?></td>
            </tr>
        <?php }
        } ?>
        </tbody>
    </table>
<?php } ?>
  </div>
  <div class="tab-pane" id="details">
    <?php if ($rules_failed > 0) {?>
    <table class="table">
        <thead>
            <th>Activity ID</th>
            <th>Context</th>
            <th>Element/Attribute</th>
            <th>Problem</th>
            <th>Tested If</th>
        </thead>
        <tbody>
<?php
        foreach ($activity_results as $result) {
            foreach ($result['errors'] as $error) { ?>
            <tr>
                <td><?php echo $result['iati-identifier']; ?></td>
                <td><?php echo $error['context']; ?></td>
                <td><?php if ($error['rule'] == 'date_order') { echo $error['case']->less.'<br/>'.$error['case']->more; }
                          else { foreach ($error['case']->paths as $path) { echo $path.'<br/>'; } } ?></td>
                <td><?php if ($error['rule'] == 'no_more_than_one') echo 'More than one';
                          else if ($error['rule'] == 'atleast_one') echo 'Missing';
                          else if ($error['rule'] == 'sum') echo "Don't sum to ".$error['case']->sum;
                          else if ($error['rule'] == 'date_order') echo 'Dates in wrong order';
                          else if ($error['rule'] == 'startswith') echo "Doesn't start with ".$error['case']->start;
                          else if ($error['rule'] == 'unique') echo 'Not unique';
                          else echo $error['rule']; ?></td>
                <td><?php if (isset($error['case']->condition)) echo $error['case']->condition; ?></td>
            </tr><?php
            }
        }
?>
        </tbody>
    </table>
    <?php } ?>
  </div>
</div>
<?php } ?>

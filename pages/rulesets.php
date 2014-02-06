Ruleset:
<form method="get">
<input type="hidden" name="test" value="rulesets" />
<select name="ruleset">
    <option value="dfid">DFID</option>
</select>
<input type="submit" value="Go" class="btn btn-primary"/>
</form>

<table>
<thead>
    <th>ID</th>
    <th>Test Name</th>
    <th>Test Paths</th>
    <th>Test Condition</th>
</thead>
<tbody>
<?php

function print_table_row($id, $errors) {
    foreach ($errors as $error) { ?>
    <tr>
        <td><?php echo $id; ?></td>
        <td><?php echo $error['rule']; ?></td>
        <td><?php foreach ($error['case']->paths as $path) { echo $path.'<br/>'; } ?></td>
        <td><?php echo $error['case']->condition; ?></td>
    </tr>
<?php }
}

if (array_key_exists('ruleset', $_GET) && in_array($_GET['ruleset'], array('dfid'))) {
    require_once 'IATI-Rulesets/testrules.php';

    $file_path = $_SESSION['uploadedfilepath']; //Sanitise/Check this?
    require_once 'functions/get_xml.php';
    $xml = get_xml($file_path);
    if($xml === FALSE) return FALSE; // Need this to prevent entity security problem

    $ruleset = json_decode(file_get_contents('IATI-Rulesets/rulesets/'.$_GET['ruleset'].'.json'));
    foreach ($xml->childNodes->item(0)->getElementsByTagName('iati-activity') as $activity) {
        $doc = new DOMDocument;
        $doc->appendChild($doc->importNode($activity,true));
        $errors = test_ruleset_dom($ruleset, $doc);
        if (count($errors) > 0) {
            print_table_row($activity->getElementsByTagName('iati-identifier')->item(0)->textContent, $errors);
        }
    }
}

?>
</tbody>
</table>

<?php
@session_start();
include_once("adminsecurity.php");

$alreadyImportedEmails		=	array();
$importedClients			=	array();
$csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

if (!empty($_FILES['csvfiles']['name']) && in_array($_FILES['csvfiles']['type'], $csvMimes)) {
    $filename = $_FILES["csvfiles"]["tmp_name"];

    if ($_FILES["csvfiles"]["size"] > 0) 
	{
        $i = 0;
        $file = fopen($filename, "r");
        while (($client = fgetcsv($file, 10000, ",")) !== FALSE) 
		{
            $i++;
            if ($i == 1) 
			{
                continue;
            }

            $email = trim($client[1]);

            if (in_array($email, $alreadyImportedEmails)) {
                // Skip this client if the email is already imported
                continue;
            }

            $clientDivisionName = trim($client[0]);
            $name = trim($client[2]);
            $phone = trim($client[3]);
            $status = trim($client[4]);
            $groups = trim($client[5]);
            $pkdivisionids = 0;
            
            if (!empty($clientDivisionName)) {
                // Check if the division name exists in the database
                $division = $AdminDAO->getrows('tbldivision', "pkdivisionid", "divisionname = '$clientDivisionName' {$ownershipcheck}");
                if (!empty($division)) {
                    $pkdivisionids = $division[0]['pkdivisionid'];
                } else {
                    // Division doesn't exist, insert it
                    $fields = array('divisionname', 'fkowneraddressbookid');
                    $values = array($clientDivisionName,1);
                    $pkdivisionids = $AdminDAO->insertrow("tbldivision", $fields, $values);
                }
            }
		     $existingClientsEmail = $AdminDAO->getrows('tblcrmclients', "*", "clientemail = '$email'");
             if (empty($existingClientsEmail)) 
			 {
                // Insert the client data into the database
                $fields = array('fkdivisionid', 'clientemail', 'clientname', 'clientphone', 'fkcrmstatusid', 'fkowneraddressbookid');
                $values = array($pkdivisionids, $email, $name, $phone, $status, 1);
                $clientId = $AdminDAO->insertrow("tblcrmclients", $fields, $values);

                $importedClients[] = $email;
                $alreadyImportedEmails[] = $email;
            }
        
        }
    }


    ob_start();
    ?>
    <div class="row">
        <div class="col-md-12">
            <?php
            if (!empty($importedClients)) 
			{
                $label = " client";
                if (count($importedClients) > 1) 
				{
                    $label = " clients";
                }
                ?>
                <div class="alert alert-success" role="alert">
                    <?php echo count($importedClients) . $label; ?> has been saved.
                </div>
                <br />
                <?php
            } else {
                $label = " client";
                if (count($alreadyImportedEmails) > 1) {
                    $label = " clients";
                }
                ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo count($alreadyImportedEmails) . $label; ?> already imported 
                </div>
                <br />
                <?php
            }
            ?>
        </div>
    </div>
    <?php
    $extra_html = ob_get_clean();
    echo json_encode(array(
        "extra_html" => $extra_html,
        "pkerrorid" => "7",
        "messagetype" => "2",
        "messagetext" => ""
    ));
} else 
{
    msg(234);
}

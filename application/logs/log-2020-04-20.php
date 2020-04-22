<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2020-04-20 02:30:55 --> Query error: Column 'status' in where clause is ambiguous - Invalid query: SELECT *
FROM `appointments`
INNER JOIN `service` ON `appointments`.`serviceID` = `service`.`serviceID`
INNER JOIN `agent` ON `service`.`agentID` = `agent`.`agentID`
WHERE `agent`.`agentID` = '1'
AND `appointmentID` = '24'
AND `status` = 2
ERROR - 2020-04-20 02:54:48 --> 404 Page Not Found: Dashboard/complete_appointment
ERROR - 2020-04-20 03:00:31 --> 404 Page Not Found: Settings/index

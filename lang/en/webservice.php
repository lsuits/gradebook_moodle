<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for component 'webservice', language 'en', branch 'MOODLE_20_STABLE'
 *
 * @package   webservice
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['accessexception'] = 'Access control exception';
$string['accessnotallowed'] = 'Access to web service not allowed';
$string['actwebserviceshhdr'] = 'Active web service protocols';
$string['addaservice'] = 'Add service';
$string['addcapabilitytousers'] = 'Check users capability';
$string['addcapabilitytousersdescription'] = 'To use web services, users need to have two different capabilities: \'/webservice:createtoken\' and a capability matching the web service protocols (\'webservice/rest:use\', \'webservice/soap:use\', ...). <br/>Advice: one way to set this up is to create a new \'Web Service\' system role with the \'webservice:createtoken\' capability and some protocol capabilities. Then assign this system role to the web service user.';
$string['addfunction'] = 'Add function';
$string['addfunctionhelp'] = 'Select the function to add to the service.';
$string['addfunctions'] = 'Add functions';
$string['addfunctionsdescription'] = 'On the <strong>Manage service</strong> page, click the <strong>Functions</strong> link for the newly created service. Add some functions to the service.';
$string['addrequiredcapability'] = 'Assign/Unassign the required capability';
$string['addservice'] = 'Add a new service: {$a->name} (id: {$a->id})';
$string['addservicefunction'] = 'Add functions to the service "{$a}"';
$string['allusers'] = 'All users';
$string['amftestclient'] = 'AMF test client';
$string['apiexplorer'] = 'API explorer';
$string['apiexplorernotavalaible'] = 'API explorer not available yet.';
$string['arguments'] = 'Arguments';
$string['authmethod'] = 'Authentication method';
$string['configwebserviceplugins'] = 'For security reasons, only protocols that are in use should be enabled.';
$string['context'] = 'Context';
$string['createservicedescription'] = 'A service is a set of web service functions. You will allow the user to access to a new service. On the <strong>Add service</strong> page check \'Enable\' and \'Authorised users\' options. Select \'No required capability\'.';
$string['createserviceforusersdescription'] = 'A service is a set of web service functions. You will allow users to access to a new service. On the <strong>Add service</strong> page check \'Enable\' and uncheck \'Authorised users\' options. Select \'No required capability\'.';
$string['createtoken'] = 'Create token';
$string['createtokenforuser'] = 'Create a token for a user';
$string['createtokenforuserdescription'] = 'On the <strong>Manage token</strong> page, click on \'Add\'. Then select the created user and service.';
$string['createuser'] = 'Create a specific user';
$string['createuserdescription'] = 'You need to create a specific user for the system controlling Moodle.';
$string['default'] = 'Default to "{$a}"';
$string['deleteaservice'] = 'Delete service';
$string['deleteservice'] = 'Delete the service: {$a->name} (id: {$a->id})';
$string['deleteserviceconfirm'] = 'Deleting a service will also delete the tokens related to this service. Do you really want to delete external service "{$a}"?';
$string['deletetokenconfirm'] = 'Do you really want to delete this web service token for <strong>{$a->user}</strong> on the service <strong>{$a->service}</strong>?';
$string['disabledwarning'] = 'All web service protocols are disabled.  The "Enable web services" setting can be found in Advanced features.';
$string['doc'] = 'Documentation';
$string['docaccessrefused'] = 'You are not allowed to see the documentation for this token';
$string['documentation'] = 'web service documentation';
$string['editaservice'] = 'Edit service';
$string['editservice'] = 'Edit the service: {$a->name} (id: {$a->id})';
$string['enabled'] = 'Enabled';
$string['enabledocumentation'] = 'Enable developer documentation';
$string['enabledocumentationdescription'] = 'Check it to give to the developers of the external system access to a detailed web services documentation. The developers only see the documentation of the service they have access to.';
$string['enableprotocols'] = 'Enable protocols';
$string['enableprotocolsdescription'] = 'At least one protocol should be enabled. In counter part, even though Moodle takes a very good care about security issues, more you have enabled protocols, more your Moodle site is subject to external attacks.';
$string['enablews'] = 'Enable web services';
$string['enablewsdescription'] = 'Web services must be enabled in Advanced features.';
$string['entertoken'] = 'Enter a security key/token:';
$string['error'] = 'Error: {$a}';
$string['errorcatcontextnotvalid'] = 'You cannot execute functions in the category context (category id:{$a->catid}). The context error message was: {$a->message}';
$string['errorcodes'] = 'Error message';
$string['errorcoursecontextnotvalid'] = 'You cannot execute functions in the course context (course id:{$a->courseid}). The context error message was: {$a->message}';
$string['errorinvalidparam'] = 'The param "{$a}" is invalid.';
$string['errorinvalidparamsapi'] = 'Invalid external api parameter';
$string['errorinvalidparamsdesc'] = 'Invalid external api description';
$string['errorinvalidresponseapi'] = 'Invalid external api response';
$string['errorinvalidresponsedesc'] = 'Invalid external api response description';
$string['errormissingkey'] = 'Missing required key in single structure: {$a}';
$string['errornotemptydefaultparamarray'] = 'The web service description parameter named \'{$a}\' is an single or multiple structure. The default can only be empty array. Check web service description.';
$string['erroronlyarray'] = 'Only arrays accepted.';
$string['erroroptionalparamarray'] = 'The web service description parameter named \'{$a}\' is an single or multiple structure. It can not be set as VALUE_OPTIONAL. Check web service description.';
$string['errorresponsemissingkey'] = 'Error in response - Missing following required key in a single structure: {$a}';
$string['errorscalartype'] = 'Scalar type expected, array or object received.';
$string['errorunexpectedkey'] = 'Unexpected keys detected in parameter array.';
$string['execute'] = 'Execute';
$string['executewarnign'] = 'WARNING: if you press execute your database will be modified and changes can not be reverted automatically!';
$string['externalservice'] = 'External service';
$string['externalservicefunctions'] = 'External service functions';
$string['externalservices'] = 'External services';
$string['externalserviceusers'] = 'External service users';
$string['failedtolog'] = 'Failed to log';
$string['function'] = 'Function';
$string['functions'] = 'Functions';
$string['generalstructure'] = 'General structure';
$string['checkusercapability'] = 'Check user capability';
$string['checkusercapabilitydescription'] = 'To use the web services, a user needs to have a capability matching the web service protocols (\'webservice/rest:use\', \'webservice/soap:use\', ...). <br/>Advice: one way to set this up is to create a new \'Web Service\' system role with protocol capabilities defined. Then you assign this system role to the web service user.';
$string['information'] = 'Information';
$string['invalidextparam'] = 'Invalid external api parameter: {$a}';
$string['invalidextresponse'] = 'Invalid external api response: {$a}';
$string['invalidiptoken'] = 'Invalid token - your IP is not supported';
$string['invalidtimedtoken'] = 'Invalid token - token expired';
$string['invalidtoken'] = 'Invalid token - token not found';
$string['invalidtokensession'] = 'Invalid session based token - session not found or expired';
$string['iprestriction'] = 'IP restriction';
$string['iprestriction_help'] = 'The user will need to call web service from the listed IPs.';
$string['key'] = 'Key';
$string['keyshelp'] = 'The keys are used to access your Moodle account from external applications.';
$string['manageprotocols'] = 'Manage protocols';
$string['managetokens'] = 'Manage tokens';
$string['missingcaps'] = 'Missing capabilities.';
$string['missingcaps_help'] = 'List of capabilities that the service functions require but that the user hasn\'t. You need to add these capabilities to this user in order to use the service.
Note: in some cases, some of these "required" capabilities could depend of a use case. For example: moodle_role_get_enrolled_users() function requires \'moodle/site:viewparticipants\' capability only for a site-wide request.';
$string['missingpassword'] = 'Missing password';
$string['missingusername'] = 'Missing username';
$string['nofunctions'] = 'This service has no functions.';
$string['norequiredcapability'] = 'No required capability';
$string['notoken'] = 'The token list is empty.';
$string['onesystemcontrolling'] = 'One system controlling Moodle with token';
$string['onesystemcontrollingdescription'] = 'The following steps help you to set up the Moodle web service for one system controlling Moodle  (e.g a student information system). These steps also help to set up the recommended token (security keys) authentication method.';
$string['operation'] = 'Operation';
$string['optional'] = 'Optional';
$string['phpparam'] = 'XML-RPC (PHP structure)';
$string['phpresponse'] = 'XML-RPC (PHP structure)';
$string['postrestparam'] = 'PHP code for REST (POST request)';
$string['potusers'] = 'Not authorised users';
$string['potusersmatching'] = 'Not authorised users matching';
$string['print'] = 'Print All';
$string['protocol'] = 'Protocol';
$string['removefunction'] = 'Remove';
$string['removefunctionconfirm'] = 'Do you really want to remove function "{$a->function}" from service "{$a->service}"?';
$string['requireauthentication'] = 'This method requires authentication with xxx permission.';
$string['required'] = 'Required';
$string['requiredcapability'] = 'Required capability';
$string['requiredcapability_help'] = 'If set, only users with the required capability can access the service.';
$string['requiredcaps'] = 'Required capabilities';
$string['resettokenconfirm'] = 'Do you really want to reset this web service key for <strong>{$a->user}</strong> on the service <strong>{$a->service}</strong>?';
$string['resettokenconfirmsimple'] = 'Do you really want to reset this key? Any saved links containing the old key will not work anymore.';
$string['response'] = 'Response';
$string['restcode'] = 'REST';
$string['restexception'] = 'REST';
$string['restparam'] = 'REST (POST parameters)';
$string['restrictedusers'] = 'Authorised users only';
$string['restrictedusers_help'] = 'If this settings is disabled, any users with the \'moodle/webservice:createtoken\' capability will be able to generate a token for this service in their \'Security keys\' page.
If this settings is enabled, you will choose which users can access this service. These users still need the \'moodle/webservice:createtoken\' capability to generate their own token in their \'Security keys\' page.';
$string['securitykey'] = 'Security key (token)';
$string['securitykeys'] = 'Security keys';
$string['selectauthorisedusers'] = 'Select authorised users';
$string['selectedcapability'] = 'Selected';
$string['selectedcapabilitydoesntexit'] = 'The currently set required capability ({$a}) doesn\'t exist any more. Please change it and save the changes.';
$string['selectservice'] = 'Select a service';
$string['selectspecificuser'] = 'Select a specific user';
$string['selectspecificuserdescription'] = 'On the <strong>Manage service</strong> page, click on \'Authorised users\' and add the user to the authorised users list. The required user capabilities will be displayed. We recommend to create a role for this service and to add the capabilities to the role, then assign the role to the user.';
$string['service'] = 'Service';
$string['servicehelpexplanation'] = 'A service is a set of functions. A service can be accessed by all users or just specified users. <strong>Custom services</strong> are services that you created. <strong>Default services</strong> are services that were added by default to Moodle. You can not change functions from a <strong>Default service</strong>.';
$string['servicename'] = 'Service name';
$string['servicesbuiltin'] = 'Built-in services';
$string['servicescustom'] = 'Custom services';
$string['serviceusers'] = 'Authorised users';
$string['serviceusersettings'] = 'User settings';
$string['serviceusersmatching'] = 'Authorised users matching';
$string['serviceuserssettings'] = 'Change settings for the authorised users';
$string['simpleauthlog'] = 'Simple authentication login';
$string['step'] = 'Step';
$string['testauserwithtestclientdescription'] = 'Simulate external access to the service using the web service test client. Before going there, log on as a user set with the "moodle/webservice:createtoken" capability, and get his security key (token) from his "my moodle" block. You will use this token in the test client. In the test client, also choose an enabled protocol with the token authentication. <strong>Warning: the functions that you test WILL BE EXECUTED for this user, be carefull what you choose to test!!!</strong>';
$string['testclient'] = 'Web service test client';
$string['testclientdescription'] = '* The web service test client <strong>executes</strong> the functions for <strong>REAL</strong>. Do not test functions that you don\'t know. <br/>* All existing web service functions are not yet implemented into the test client. <br/>* In order to check that a user cannot access some functions, you can test some functions that you didn\'t allow.<br/>* To see clearer error messages set the debugging to <strong>{$a->mode}</strong> into {$a->atag}<br/>* Access the {$a->amfatag}.';
$string['testwithtestclient'] = 'Test the service';
$string['testwithtestclientdescription'] = 'Simulate external access to the service using the web service test client. Use an enabled protocol with token authentication. <strong>Warning: the functions that you test WILL BE EXECUTED, be carefull what you choose to test!!!</strong>';
$string['token'] = 'Token';
$string['tokenauthlog'] = 'Token authentication';
$string['tokencreatedbyadmin'] = 'Can only be reset by administrator (*)';
$string['tokencreator'] = 'Creator';
$string['updateusersettings'] = 'Update';
$string['userasclients'] = 'Users as clients with token';
$string['userasclientsdescription'] = 'The following steps help you to set up the Moodle web service for users as clients. These steps also help to set up the recommended token (security keys) authentication method. In this use case, the user will generate his token from his <strong>Security keys</strong> profile page.';
$string['usermissingcaps'] = 'Missing capabilities: {$a}.';
$string['usernotallowed'] = 'The user is not allowed for this service. First you need to allow this user on the {$a}\'s allowed users administration page.';
$string['usersettingssaved'] = 'User settings saved';
$string['validuntil'] = 'Valid until';
$string['validuntil_help'] = 'If set, the service will be inactivated after this date for this user.';
$string['webservice'] = 'Web service';
$string['webservices'] = 'Web services';
$string['webservicesoverview'] = 'Overview';
$string['webservicetokens'] = 'Web service tokens';
$string['wrongusernamepassword'] = 'Wrong username or password';
$string['wsauthmissing'] = 'The web service authentication plugin is missing.';
$string['wsauthnotenabled'] = 'The web service authentication plugin is disabled.';
$string['wsclientdoc'] = 'Moodle web service client documentation';
$string['wsdocumentation'] = 'Web service documentation';
$string['wsdocumentationdisable'] = 'Web service documentation is disabled.';
$string['wsdocumentationintro'] = 'To create a client we advise you to read the {$a->doclink}';
$string['wsdocumentationlogin'] = 'or enter your web service username and password:';
$string['wspassword'] = 'Web service password';
$string['wsusername'] = 'Web service username';
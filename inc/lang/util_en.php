<?php
return array(
'site_name' => GWF_SITENAME,

# util/gdo and util/gwf fields
'created_at' => 'Created at',
'id' => 'ID',
'file' => 'File',
'title' => 'Title',
'description' => 'Description',
'message' => 'Message',
'captcha' => 'Captcha',
'user' => 'User',
'ip' => 'IP',
'username' => 'Username',
'gender' => 'Gender',
'email' => 'Email',
'email_fmt' => 'Email Format',
'language' => 'Language',
'country' => 'Country',
'password' => 'Password',
'user_real_name' => 'Real Name',
'name' => 'Name',
'enabled' => 'Enabled',
'sort' => 'Sort',
'version' => 'Version',
'path' => 'Path',
'retype' => 'Retype',
'user_allow_email' => 'Allow people to E-Mail me',
'birthdate' => 'Birthdate',
'url' => 'URL',

# Generic Buttons
'btn_send' => 'Send',
'btn_save' => 'Save',
'btn_upload' => 'Upload',

# Generic Messages
'msg_form_saved' => 'Your data has been safed successfully.',		
'msg_upgrading' => 'Upgrading Module %s to version %s.',
'msg_redirect' => 'You will be redirected to <i>%s</i> in %s seconds.', 
		
# Generic Errors
'err_db' => "Database Error: %s<br/>\n%s<br/>\n",
'err_token' => 'Your token is invalid.',
'err_csrf' => 'Your form token is invalid. Maybe you have tried to refresh a form submission or have cookie problems.',
'err_field_invalid' => 'Your value for %s is not accepted.',
'err_blank_response' => 'The module gave a blank response, which is unusual.',
'err_checkbox_required' => 'You have to checkmark this field in order to proceed.',
'err_strlen_between' => 'This string has to be between %s and %s characters in length.',
'err_form_invalid' => 'Your sent form is incomplete as it contains errors.',
'err_user_required' => 'You need to be authenticated before using this function.',
'err_upload_min_files' => 'You have to upload at least %s file(s).',
'err_upload_max_files' => 'You may not upload more than %s files(s).',
'err_permission_required' => 'You need <i>%s</i> permissions to access this function.',
'err_save_unpersisted_entity' => 'Tried to save an unpersisted entity of type <i>%s</i>.',
'err_file' => 'File not found: %s',
'err_already_authenticated' => 'You are already authenticated.',
'err_gdo_not_found' => 'Could not find %s with ID %s.',
'err_string_pattern' => 'Your input did not pass the pattern validation test for this field.',
'err_url_not_reachable' => 'Your entered URL is not reachable by this server.',
'err_method_disabled' => 'This function is currently disabled.',
		
# Permissions
'perm_admin' => 'Administrator',

# Dateformats
'df_day' => 'm/d/Y',
'df_short' => 'm/d/Y H:i',
'tu_s' => 's',
'tu_m' => 'm',
'tu_h' => 'h',
'tu_d' => 'd',
'tu_y' => 'y',

# Email formats
'enum_html' => 'HTML',
'enum_text' => 'Text',

# Gender
'enum_male' => 'male',
'enum_female' => 'female',
'enum_no_gender' => 'not specified',

);

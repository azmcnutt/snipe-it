<?php

namespace App\Models;

use App\Models\Setting;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Input;
use Log;

class Ldap extends Model
{
    /**
     * Makes a connection to LDAP using the settings in Admin > Settings.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v3.0]
     * @return connection
     */
    public static function connectToLdap()
    {
        $ldap_host = Setting::getSettings()->ldap_server;
        $ldap_version = Setting::getSettings()->ldap_version;
        $ldap_server_cert_ignore = Setting::getSettings()->ldap_server_cert_ignore;
        $ldap_use_tls = Setting::getSettings()->ldap_tls;

        // If we are ignoring the SSL cert we need to setup the environment variable
        // before we create the connection
        if ($ldap_server_cert_ignore == '1') {
            putenv('LDAPTLS_REQCERT=never');
        }

        // If the user specifies where CA Certs are, make sure to use them
        if (env('LDAPTLS_CACERT')) {
            putenv('LDAPTLS_CACERT='.env('LDAPTLS_CACERT'));
        }

        $connection = @ldap_connect($ldap_host);

        if (! $connection) {
            throw new Exception('Could not connect to LDAP server at '.$ldap_host.'. Please check your LDAP server name and port number in your settings.');
        }

        // Needed for AD
        ldap_set_option($connection, LDAP_OPT_REFERRALS, 0);
        ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, $ldap_version);
        ldap_set_option($connection, LDAP_OPT_NETWORK_TIMEOUT, 20);

        if (Setting::getSettings()->ldap_client_tls_cert && Setting::getSettings()->ldap_client_tls_key) {
            ldap_set_option($connection, LDAP_OPT_X_TLS_CERTFILE, Setting::get_client_side_cert_path());
            ldap_set_option($connection, LDAP_OPT_X_TLS_KEYFILE, Setting::get_client_side_key_path());
        }

        if ($ldap_use_tls=='1') {
            ldap_start_tls($connection);
        }


        return $connection;
    }


    /**
     * Binds/authenticates the user to LDAP, and returns their attributes.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v3.0]
     * @param $username
     * @param $password
     * @param bool|false $user
     * @return bool true    if the username and/or password provided are valid
     *              false   if the username and/or password provided are invalid
     *         array of ldap_attributes if $user is true
     */
    public static function findAndBindUserLdap($username, $password)
    {
        $settings = Setting::getSettings();
        $connection = self::connectToLdap();
        $ldap_username_field = $settings->ldap_username_field;
        $baseDn = $settings->ldap_basedn;
        $userDn = $ldap_username_field.'='.$username.','.$settings->ldap_basedn;

        if ($settings->is_ad == '1') {
            // Check if they are using the userprincipalname for the username field.
            // If they are, we can skip building the UPN to authenticate against AD
            if ($ldap_username_field == 'userprincipalname') {
                $userDn = $username;
            } else { // FIXME - we have to respect the new 'append AD domain to username' setting (which sucks.)
                // In case they haven't added an AD domain
                $userDn = ($settings->ad_domain != '') ? $username.'@'.$settings->ad_domain : $username.'@'.$settings->email_domain;
            }
        }

        $filterQuery = $settings->ldap_auth_filter_query.$username;
        $filter = Setting::getSettings()->ldap_filter; //TODO - this *does* respect the ldap filter, but I believe that AdLdap2 did *not*.
        $filterQuery = "({$filter}({$filterQuery}))";

        \Log::debug('Filter query: '.$filterQuery);

        if (! $ldapbind = @ldap_bind($connection, $userDn, $password)) {
            \Log::debug("Status of binding user: $userDn to directory: (directly!) ".($ldapbind ? "success" : "FAILURE"));
            if (! $ldapbind = self::bindAdminToLdap($connection)) { // TODO uh, this seems...dangerous? Why would we just switch over to the admin connection? That's too loose, I feel.
                    \Log::debug("Status of binding Admin user: $userDn to directory instead: ".($ldapbind ? "success" : "FAILURE"));
                    return false;
            }
        }

        if (! $results = ldap_search($connection, $baseDn, $filterQuery)) {
            throw new Exception('Could not search LDAP: ');
        }

        if (! $entry = ldap_first_entry($connection, $results)) {
            return false;
        }

        if (! $user = ldap_get_attributes($connection, $entry)) {
            return false;
        }

        return array_change_key_case($user);
    }

    /**
     * Binds/authenticates an admin to LDAP for LDAP searching/syncing.
     * Here we also return a better error if the app key is donked.
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v3.0]
     * @param bool|false $user
     * @return bool true    if the username and/or password provided are valid
     *              false   if the username and/or password provided are invalid
     */
    public static function bindAdminToLdap($connection)
    {
        $ldap_username = Setting::getSettings()->ldap_uname;

        $ldap_username     = Setting::getSettings()->ldap_uname;

        // Lets return some nicer messages for users who donked their app key, and disable LDAP
        try {
            $ldap_pass = \Crypt::decrypt(Setting::getSettings()->ldap_pword);
        } catch (Exception $e) {
            throw new Exception('Your app key has changed! Could not decrypt LDAP password using your current app key, so LDAP authentication has been disabled. Login with a local account, update the LDAP password and re-enable it in Admin > Settings.');
        }

        if (! $ldapbind = @ldap_bind($connection, $ldap_username, $ldap_pass)) {
            throw new Exception('Could not bind to LDAP: '.ldap_error($connection));
        }
    }


    /**
     * Parse and map LDAP attributes based on settings
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v3.0]
     *
     * @param $ldapatttibutes
     * @return array|bool
     */
    public static function parseAndMapLdapAttributes($ldapattributes)
    {
        //Get LDAP attribute config
        $ldap_result_username = Setting::getSettings()->ldap_username_field;
        $ldap_result_emp_num = Setting::getSettings()->ldap_emp_num;
        $ldap_result_last_name = Setting::getSettings()->ldap_lname_field;
        $ldap_result_first_name = Setting::getSettings()->ldap_fname_field;
        $ldap_result_email = Setting::getSettings()->ldap_email;
        $ldap_result_phone = Setting::getSettings()->ldap_phone;
        $ldap_result_jobtitle = Setting::getSettings()->ldap_jobtitle;
        $ldap_result_country = Setting::getSettings()->ldap_country;
        $ldap_result_dept = Setting::getSettings()->ldap_dept;
        // Get LDAP user data
        $item = [];
        $item['username'] = isset($ldapattributes[$ldap_result_username][0]) ? $ldapattributes[$ldap_result_username][0] : '';
        $item['employee_number'] = isset($ldapattributes[$ldap_result_emp_num][0]) ? $ldapattributes[$ldap_result_emp_num][0] : '';
        $item['lastname'] = isset($ldapattributes[$ldap_result_last_name][0]) ? $ldapattributes[$ldap_result_last_name][0] : '';
        $item['firstname'] = isset($ldapattributes[$ldap_result_first_name][0]) ? $ldapattributes[$ldap_result_first_name][0] : '';
        $item['email'] = isset($ldapattributes[$ldap_result_email][0]) ? $ldapattributes[$ldap_result_email][0] : '';
        $item['telephone'] = isset($ldapattributes[$ldap_result_phone][0]) ? $ldapattributes[$ldap_result_phone][0] : '';
        $item['jobtitle'] = isset($ldapattributes[$ldap_result_jobtitle][0]) ? $ldapattributes[$ldap_result_jobtitle][0] : '';
        $item['country'] = isset($ldapattributes[$ldap_result_country][0]) ? $ldapattributes[$ldap_result_country][0] : '';
        $item['department'] = isset($ldapattributes[$ldap_result_dept][0]) ? $ldapattributes[$ldap_result_dept][0] : '';

        return $item;
    }

    /**
     * Create user from LDAP attributes
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v3.0]
     * @param $ldapatttibutes
     * @return array|bool
     */
    public static function createUserFromLdap($ldapatttibutes)
    {
        $item = self::parseAndMapLdapAttributes($ldapatttibutes);

        // Create user from LDAP data
        if (! empty($item['username'])) {
            $user = new User;
            $user->first_name = $item['firstname'];
            $user->last_name = $item['lastname'];
            $user->username = $item['username'];
            $user->email = $item['email'];

            if (Setting::getSettings()->ldap_pw_sync == '1') {
                $user->password = bcrypt(Input::get('password'));
            } else {
                $pass = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 25);
                $user->password = bcrypt($pass);
            }

            $user->activated = 1;
            $user->ldap_import = 1;
            $user->notes = 'Imported on first login from LDAP';

            if ($user->save()) {
                return $user;
            } else {
                LOG::debug('Could not create user.'.$user->getErrors());
                throw new Exception('Could not create user: '.$user->getErrors());
            }
        }

        return false;
    }

    /**
     * Searches LDAP
     *
     * @author [A. Gianotto] [<snipe@snipe.net>]
     * @since [v3.0]
     * @param $base_dn
     * @param $count
     * @return array|bool
     */
    public static function findLdapUsers($base_dn = null, $count = -1)
    {
        $ldapconn = self::connectToLdap();
        $ldap_bind = self::bindAdminToLdap($ldapconn);
        // Default to global base DN if nothing else is provided.
        if (is_null($base_dn)) {
            $base_dn = Setting::getSettings()->ldap_basedn;
        }
        $filter = Setting::getSettings()->ldap_filter;

        // Set up LDAP pagination for very large databases
        $page_size = 500;
        $cookie = '';
        $result_set = [];
        $global_count = 0;

        // Perform the search
        do {

            // // Paginate (non-critical, if not supported by server)
            // if (! $ldap_paging = ldap_search($ldapconn, $page_size, false, $cookie)) { //FIXME! This command doesn't exist anymore? I don't know what to replace it with. maybe nothing?
            //     throw new Exception('Problem with your LDAP connection. Try checking the Use TLS setting in Admin > Settings. ');
            // }

            if ($filter != '' && substr($filter, 0, 1) != '(') { // wrap parens around NON-EMPTY filters that DON'T have them, for back-compatibility with AdLdap2-based filters
                $filter = "($filter)";
            } elseif ($filter == '') {
                $filter = '(cn=*)';
            }

            // HUGE thanks to this article: https://stackoverflow.com/questions/68275972/how-to-get-paged-ldap-queries-in-php-8-and-read-more-than-1000-entries
            // which helped me wrap my head around paged results!
            \Log::info("ldap conn is: ".$ldapconn." basedn is: $base_dn, filter is: $filter - count is: $count. page size is: $page_size");
            // if a $count is set and it's smaller than $page_size then use that as the page size
            $ldap_controls = [];
            if($count == -1) { //count is -1 means we have to employ paging to query the entire directory
                $ldap_controls = [['oid' => LDAP_CONTROL_PAGEDRESULTS, 'iscritical' => false, 'value' => ['size'=> $page_size, 'cookie' => $cookie]]];
            }
            $search_results = @ldap_search($ldapconn, $base_dn, $filter, [], 0, /* $page_size*/ -1, -1, LDAP_DEREF_NEVER, $ldap_controls);
            \Log::info("did the search run? I guess so if you got here!");
            if (! $search_results) {
                return redirect()->route('users.index')->with('error', trans('admin/users/message.error.ldap_could_not_search').ldap_error($ldapconn)); // FIXME this is never called in any routed context - only from the Artisan command. So this redirect will never work.
            }

            $errcode = null;
            $matcheddn = null;
            $errmsg = null;
            $referrals = null;
            $controls = [];
            ldap_parse_result($ldapconn, $search_results, $errcode , $matcheddn , $errmsg , $referrals, $controls);
            if (isset($controls[LDAP_CONTROL_PAGEDRESULTS]['value']['cookie'])) {
                // You need to pass the cookie from the last call to the next one
                $cookie = $controls[LDAP_CONTROL_PAGEDRESULTS]['value']['cookie'];
                \Log::info("okay, at least one more page to go!!!");
            } else {
                \Log::info("okay, we're out of pages - no cookie (or empty cookie) was passed");
                $cookie = '';
            }
            // Empty cookie means last page
        
            // Get results from page
            $results = ldap_get_entries($ldapconn, $search_results);
            if (! $results) {
                return redirect()->route('users.index')->with('error', trans('admin/users/message.error.ldap_could_not_get_entries').ldap_error($ldapconn)); // FIXME this is never called in any routed context - only from the Artisan command. So this redirect will never work.
            }

            // Add results to result set
            $global_count += $results['count'];
            $result_set = array_merge($result_set, $results);
            \Log::info("Total count is: $global_count");

            // ldap_search($ldapconn, $search_results, $cookie); // FIXME - this function is removed in PHP8
        } while ($cookie !== null && $cookie != '');

        // Clean up after search
        $result_set['count'] = $global_count;
        $results = $result_set;

        return $results;
    }
}

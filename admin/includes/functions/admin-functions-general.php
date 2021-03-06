<?php
function AdminLogIn( $email, $password ) {
    $encrypt = "new";

    $isValidPassword = isValidPassword( $password );

    $temp_str = getPass( $email, $password );

    $DBobject = new DBmanager(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD);
    $sql      = "SELECT * FROM tbl_admin WHERE admin_email = :email AND admin_password = :password AND admin_deleted IS NULL";
    $params   = array(
        "email"    => $email,
        "password" => $temp_str
    );
    if ( $res = $DBobject->wrappedSql( $sql, $params ) ) {
        $_SESSION['user']['admin']["id"]      = $res[0]["admin_id"];
        $_SESSION['user']['admin']["name"]    = $res[0]["admin_name"];
        $_SESSION['user']['admin']["surname"] = $res[0]["admin_surname"];
        $_SESSION['user']['admin']["email"]   = $res[0]["admin_email"];
        $_SESSION['user']['admin']["level"]   = $res[0]["admin_level"];
        $_SESSION['user']['admin']["token"]   = generatetoken();

        saveInLog( 'Login', 'tbl_admin', $res[0]["admin_id"] );
        $_SESSION['user']['admin']["strong_password"] = $isValidPassword;

        return true;
    } else {
        $temp_str2 = getOldPass( $email, $password );
        $sql       = "SELECT * FROM tbl_admin WHERE admin_email = :email AND admin_password = :password AND admin_deleted IS NULL AND (admin_encryption IS NULL OR admin_encryption != :encrypt)";
        $params    = array(
            "email"    => $email,
            "password" => $temp_str2,
            "encrypt"  => $encrypt
        );
        if ( $res = $DBobject->wrappedSql( $sql, $params ) ) {
            //Update Password record
            $usql   = "UPDATE tbl_admin SET admin_password = :password, admin_encryption = :encrypt WHERE admin_id = :uid";
            $params = array( "uid" => $res[0]["admin_id"], "password" => $temp_str, "encrypt" => $encrypt );
            $DBobject->wrappedSql( $usql, $params );

            $_SESSION['user']['admin']["id"]              = $res[0]["admin_id"];
            $_SESSION['user']['admin']["name"]            = $res[0]["admin_name"];
            $_SESSION['user']['admin']["surname"]         = $res[0]["admin_surname"];
            $_SESSION['user']['admin']["email"]           = $res[0]["admin_email"];
            $_SESSION['user']['admin']["level"]           = $res[0]["admin_level"];
            $_SESSION['user']['admin']["strong_password"] = $isValidPassword;
            saveInLog( 'Login', 'tbl_admin', $res[0]["admin_id"] );

            return true;
        } else {
            return false;
        }
    }

    return false;
}

function showVars() {
    foreach ( $_REQUEST as $key => $val ) {
        echo $key . "=" . $val . "<br><br>";
    }
}

function printr( $arr, $return = 0 ) {
    if ( $return == 0 ) {
        echo "<pre>";
        print_r( $arr );
        echo "</pre>";
    } else {
        $buf = print_r( $arr, 1 );

        return $buf;
    }

}

function get_type_from_extension( $ext ) {
    switch ( strtolower( $ext ) ) {
        case 'jpg':
        case 'jpeg':
        case 'gif':
        case 'png':
            return 'image';
        case 'pdf':
        case 'xls':
        case 'xlsx':
            return 'pdf';
        case 'doc':
        case 'docx':
        case 'txt':
            return 'doc';
        case 'swf':
        case 'flv':
            return 'video';
        case 'mp3':
            return 'audio';
        case 'csv':
            return 'csv';
    }
}

function checkAdminLogin( $admin_session, $updateAfter = 1, $logOutAfter = 6 ) {
    global $DBobject;
    if ( isset( $admin_session ) && ! empty( $admin_session ) && ! empty( $admin_session["id"] ) && ! empty( $admin_session["token"] ) ) {
        $log_sql    = "SELECT id, (case when ( ( TIME_TO_SEC(TIMEDIFF(now(), modified)) / 3600.0) > $updateAfter ) THEN 1 ELSE 0 END) as update_record from login_log WHERE admin_id = :admin_id AND token = :token AND modified BETWEEN DATE_SUB(NOW(), INTERVAL $logOutAfter HOUR) AND NOW()";
        $log_params = array(
            ":admin_id" => $admin_session["id"],
            ":token"    => $admin_session["token"]
        );
        if ( $log_res = $DBobject->wrappedSql( $log_sql, $log_params ) ) {
            if ( $log_res[0]['update_record'] == 1 ) {
                $sql = "UPDATE `login_log` SET `modified`=now() WHERE id = :id";
                $res = $DBobject->wrappedSql( $sql, array( "id" => $log_res[0]['id'] ) );
            }
        } else {
            logoutAdmin();
        }
    }
}

function logoutAdmin() {
    $_SESSION = null;
    session_destroy();
    session_start();
}


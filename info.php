<?php

/**
 * PHP Lite File Manger
 * Programmer | Webpage, inc.Permission
 * https:www.webpage.com.pk
 * https://www.two.com.pk/
 */

// Default language
$lang = 'en';

// Auth with login/password (set true/false to enable/disable it)
$use_auth = true;

// Users: array('Username' => 'Password', 'Username2' => 'Password2', ...)
$auth_users = array(
    'admin' => '$2y$10$1wXHgLfTggq4SUTkm5YGUuO771X106e.B0WgHXXkH72C0/LuY6ZSW', //admin
    'user' => '$2y$10$Fg6Dz8oH9fPoZ2jJan5tZuv6Z4Kp7avtQ9bDfrdRntXtPeiMAZyGO' //12345
);

// Readonly users (usernames array)
$readonly_users = array(
    'user'
);

//user specific directories
//array('Username' => 'Directory path', 'Username2' => 'Directory path', ...)
$directories_users = array();

// Show or hide files and folders that starts with a dot
$show_hidden_files = true;

// Enable highlight.js (https://highlightjs.org/) on view's page
$use_highlightjs = true;

// highlight.js style
$highlightjs_style = 'vs';

// Enable ace.js (https://ace.c9.io/) on view's page
$edit_files = true;

// Default timezone for date() and time() - http://php.net/manual/en/timezones.php
$default_timezone = 'Etc/UTC'; // UTC

// Root path for file manager
$root_path = $_SERVER['DOCUMENT_ROOT'];;

// Root url for links in file manager.Relative to $http_host. Variants: '', 'path/to/subfolder'
// Will not working if $root_path will be outside of server document root
$root_url = '';

// Server hostname. Can set manually if wrong
$http_host = $_SERVER['HTTP_HOST'];

// input encoding for iconv
$iconv_input_encoding = 'UTF-8';

// date() format for file modification date
$datetime_format = 'd.m.y H:i';

// allowed upload file extensions
$upload_extensions = ''; // 'gif,png,jpg'

//Array of files and folders excluded from listing
$GLOBALS['exclude_items'] = array();

// include user config php file
if (defined('FM_CONFIG') && is_file(FM_CONFIG)) {
    include(FM_CONFIG);
}

//--- EDIT BELOW CAREFULLY OR DO NOT EDIT AT ALL

// if fm included
if (defined('FM_EMBED')) {
    $use_auth = false;
} else {
    @set_time_limit(600);

    date_default_timezone_set($default_timezone);

    ini_set('default_charset', 'UTF-8');
    if (version_compare(PHP_VERSION, '5.6.0', '<') && function_exists('mb_internal_encoding')) {
        mb_internal_encoding('UTF-8');
    }
    if (function_exists('mb_regex_encoding')) {
        mb_regex_encoding('UTF-8');
    }

    session_cache_limiter('');
    session_name('filemanager');
    session_start();
}

if (empty($auth_users)) {
    $use_auth = false;
}

$is_https = isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1)
    || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https';

// clean $root_url
$root_url = fm_clean_path($root_url);

// abs path for site
defined('FM_ROOT_URL') || define('FM_ROOT_URL', ($is_https ? 'https' : 'http') . '://' . $http_host . (!empty($root_url) ? '/' . $root_url : ''));
defined('FM_SELF_URL') || define('FM_SELF_URL', ($is_https ? 'https' : 'http') . '://' . $http_host . $_SERVER['PHP_SELF']);

// logout
if (isset($_GET['logout'])) {
    unset($_SESSION['logged']);
    fm_redirect(FM_SELF_URL);
}

// Show image here
if (isset($_GET['img'])) {
    fm_show_image($_GET['img']);
}

// Auth
if ($use_auth) {
    if (isset($_SESSION['logged'], $auth_users[$_SESSION['logged']])) {
        // Logged
    } elseif (isset($_POST['fm_usr'], $_POST['fm_pwd'])) {
        // Logging In
        sleep(1);
        if (isset($auth_users[$_POST['fm_usr']]) && password_verify($_POST['fm_pwd'], $auth_users[$_POST['fm_usr']])) {
            $_SESSION['logged'] = $_POST['fm_usr'];
            fm_set_msg('You are logged in');
            fm_redirect(FM_SELF_URL . '?p=');
        } else {
            unset($_SESSION['logged']);
            fm_set_msg('Wrong password', 'error');
            fm_redirect(FM_SELF_URL);
        }
    } else {
        // Form
        unset($_SESSION['logged']);
        fm_show_header_login();
        fm_show_message();
        ?><style type="text/css">
            .login-panel {
                margin: 0 auto;
                  margin-top: 25%;

                }
        </style>
<div class="container">
            <div class="row">
                <div class="col-md-4 offset-4">
                    <div class="login-panel card">
                        <div class="card-header">
                            <h2 class="card-title text-center">File Manager</h2>
                        </div>
                        <div class="card-body">
                            <form role="form" action="" method="post" autocomplete="off">
                                <fieldset>
                                    <div class="form-group">
                                        <label for="fm_usr" class="sr-only">Username</label>
                                        <input class="form-control" placeholder="Username" id="fm_usr" name="fm_usr" type="text" autofocus required>
                                    </div>
                                    <div class="form-group">
                                        <label for="fm_pwd" class="sr-only">Password</label>
                                            <input type="password" id="fm_pwd" name="fm_pwd" class="form-control mt-2" placeholder="Password" required="">
                                    </div>
                                    <div class="checkbox mb-2">
                                        <label>
                                            <input type="checkbox" value="remember-me"> Remember me
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-success btn-block">Sign in</button>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- <div class="text-center">
            <form class="form-signin" action="" method="post" autocomplete="off">
                <img class="mb-4" src="#" alt="File Manager">
                <h1 class="h3 mb-3 font-weight-normal">File Manager</h1>
                <label for="fm_usr" class="sr-only">Username</label>
                <input type="text" id="fm_usr" name="fm_usr" class="form-control" placeholder="Username" required="" autofocus="">
                <label for="fm_pwd" class="sr-only">Password</label>
                <input type="password" id="fm_pwd" name="fm_pwd" class="form-control mt-2" placeholder="Password" required="">
                <div class="checkbox mb-3">
                    <label>
                        <input type="checkbox" value="remember-me"> Remember me
                    </label>
                </div>
                <button class="btn btn-lg btn-primary btn-block mt-4" type="submit">Sign in</button>
                <p class="mt-5 mb-3 text-muted">© Two Color Ocean Media</p>
            </form>
        </div> -->
        <?php
        fm_show_footer_login();
        exit;
    }
}

//update root path
if ($use_auth && isset($_SESSION['logged'])) {
    $root_path = isset($directories_users[$_SESSION['logged']]) ? $directories_users[$_SESSION['logged']] : $root_path;
}

// clean and check $root_path
$root_path = rtrim($root_path, '\\/');
$root_path = str_replace('\\', '/', $root_path);
if (!@is_dir($root_path)) {
    echo "<h1>Root path \"{$root_path}\" not found!</h1>";
    exit;
}

defined('FM_SHOW_HIDDEN') || define('FM_SHOW_HIDDEN', $show_hidden_files);
defined('FM_ROOT_PATH') || define('FM_ROOT_PATH', $root_path);
defined('FM_LANG') || define('FM_LANG', $lang);
defined('FM_EXTENSION') || define('FM_EXTENSION', $upload_extensions);
define('FM_READONLY', $use_auth && !empty($readonly_users) && isset($_SESSION['logged']) && in_array($_SESSION['logged'], $readonly_users));
define('FM_IS_WIN', DIRECTORY_SEPARATOR == '\\');

// always use ?p=
if (!isset($_GET['p']) && empty($_FILES)) {
    fm_redirect(FM_SELF_URL . '?p=');
}

// get path
$p = isset($_GET['p']) ? $_GET['p'] : (isset($_POST['p']) ? $_POST['p'] : '');

// clean path
$p = fm_clean_path($p);

// instead globals vars
define('FM_PATH', $p);
define('FM_USE_AUTH', $use_auth);
define('FM_EDIT_FILE', $edit_files);
defined('FM_ICONV_INPUT_ENC') || define('FM_ICONV_INPUT_ENC', $iconv_input_encoding);
defined('FM_USE_HIGHLIGHTJS') || define('FM_USE_HIGHLIGHTJS', $use_highlightjs);
defined('FM_HIGHLIGHTJS_STYLE') || define('FM_HIGHLIGHTJS_STYLE', $highlightjs_style);
defined('FM_DATETIME_FORMAT') || define('FM_DATETIME_FORMAT', $datetime_format);

unset($p, $use_auth, $iconv_input_encoding, $use_highlightjs, $highlightjs_style);

/*************************** ACTIONS ***************************/

//AJAX Request
if (isset($_POST['ajax']) && !FM_READONLY) {

    //search : get list of files from the current folder
    if (isset($_POST['type']) && $_POST['type'] == "search") {
        $dir = $_POST['path'];
        $response = scan($dir);
        echo json_encode($response);
    }

    //backup files
    if (isset($_POST['type']) && $_POST['type'] == "backup") {
        $file = $_POST['file'];
        $path = $_POST['path'];
        $date = date("dMy-His");
        $newFile = $file . '-' . $date . '.bak';
        copy($path . '/' . $file, $path . '/' . $newFile) or die("Unable to backup");
        echo "Backup $newFile Created";
    }

    exit;
}

// Delete file / folder
if (isset($_GET['del']) && !FM_READONLY) {
    $del = $_GET['del'];
    $del = fm_clean_path($del);
    $del = str_replace('/', '', $del);
    if ($del != '' && $del != '..' && $del != '.') {
        $path = FM_ROOT_PATH;
        if (FM_PATH != '') {
            $path .= '/' . FM_PATH;
        }
        $is_dir = is_dir($path . '/' . $del);
        if (fm_rdelete($path . '/' . $del)) {
            $msg = $is_dir ? 'Folder <b>%s</b> deleted' : 'File <b>%s</b> deleted';
            fm_set_msg(sprintf($msg, fm_enc($del)));
        } else {
            $msg = $is_dir ? 'Folder <b>%s</b> not deleted' : 'File <b>%s</b> not deleted';
            fm_set_msg(sprintf($msg, fm_enc($del)), 'error');
        }
    } else {
        fm_set_msg('Wrong file or folder name', 'error');
    }
    fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
}

// Create folder
if (isset($_GET['new']) && isset($_GET['type']) && !FM_READONLY) {
    $new = strip_tags($_GET['new']);
    $type = $_GET['type'];
    $new = fm_clean_path($new);
    $new = str_replace('/', '', $new);
    if ($new != '' && $new != '..' && $new != '.') {
        $path = FM_ROOT_PATH;
        if (FM_PATH != '') {
            $path .= '/' . FM_PATH;
        }
        if ($_GET['type'] == "file") {
            if (!file_exists($path . '/' . $new)) {
                @fopen($path . '/' . $new, 'w') or die('Cannot open file:  ' . $new);
                fm_set_msg(sprintf('File <b>%s</b> created', fm_enc($new)));
            } else {
                fm_set_msg(sprintf('File <b>%s</b> already exists', fm_enc($new)), 'alert');
            }
        } else {
            if (fm_mkdir($path . '/' . $new, false) === true) {
                fm_set_msg(sprintf('Folder <b>%s</b> created', $new));
            } elseif (fm_mkdir($path . '/' . $new, false) === $path . '/' . $new) {
                fm_set_msg(sprintf('Folder <b>%s</b> already exists', fm_enc($new)), 'alert');
            } else {
                fm_set_msg(sprintf('Folder <b>%s</b> not created', fm_enc($new)), 'error');
            }
        }
    } else {
        fm_set_msg('Wrong folder name', 'error');
    }
    fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
}

// Copy folder / file
if (isset($_GET['copy'], $_GET['finish']) && !FM_READONLY) {
    // from
    $copy = $_GET['copy'];
    $copy = fm_clean_path($copy);
    // empty path
    if ($copy == '') {
        fm_set_msg('Source path not defined', 'error');
        fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
    }
    // abs path from
    $from = FM_ROOT_PATH . '/' . $copy;
    // abs path to
    $dest = FM_ROOT_PATH;
    if (FM_PATH != '') {
        $dest .= '/' . FM_PATH;
    }
    $dest .= '/' . basename($from);
    // move?
    $move = isset($_GET['move']);
    // copy/move
    if ($from != $dest) {
        $msg_from = trim(FM_PATH . '/' . basename($from), '/');
        if ($move) {
            $rename = fm_rename($from, $dest);
            if ($rename) {
                fm_set_msg(sprintf('Moved from <b>%s</b> to <b>%s</b>', fm_enc($copy), fm_enc($msg_from)));
            } elseif ($rename === null) {
                fm_set_msg('File or folder with this path already exists', 'alert');
            } else {
                fm_set_msg(sprintf('Error while moving from <b>%s</b> to <b>%s</b>', fm_enc($copy), fm_enc($msg_from)), 'error');
            }
        } else {
            if (fm_rcopy($from, $dest)) {
                fm_set_msg(sprintf('Copyied from <b>%s</b> to <b>%s</b>', fm_enc($copy), fm_enc($msg_from)));
            } else {
                fm_set_msg(sprintf('Error while copying from <b>%s</b> to <b>%s</b>', fm_enc($copy), fm_enc($msg_from)), 'error');
            }
        }
    } else {
        fm_set_msg('Paths must be not equal', 'alert');
    }
    fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
}

// Mass copy files/ folders
if (isset($_POST['file'], $_POST['copy_to'], $_POST['finish']) && !FM_READONLY) {
    // from
    $path = FM_ROOT_PATH;
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }
    // to
    $copy_to_path = FM_ROOT_PATH;
    $copy_to = fm_clean_path($_POST['copy_to']);
    if ($copy_to != '') {
        $copy_to_path .= '/' . $copy_to;
    }
    if ($path == $copy_to_path) {
        fm_set_msg('Paths must be not equal', 'alert');
        fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
    }
    if (!is_dir($copy_to_path)) {
        if (!fm_mkdir($copy_to_path, true)) {
            fm_set_msg('Unable to create destination folder', 'error');
            fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
        }
    }
    // move?
    $move = isset($_POST['move']);
    // copy/move
    $errors = 0;
    $files = $_POST['file'];
    if (is_array($files) && count($files)) {
        foreach ($files as $f) {
            if ($f != '') {
                // abs path from
                $from = $path . '/' . $f;
                // abs path to
                $dest = $copy_to_path . '/' . $f;
                // do
                if ($move) {
                    $rename = fm_rename($from, $dest);
                    if ($rename === false) {
                        $errors++;
                    }
                } else {
                    if (!fm_rcopy($from, $dest)) {
                        $errors++;
                    }
                }
            }
        }
        if ($errors == 0) {
            $msg = $move ? 'Selected files and folders moved' : 'Selected files and folders copied';
            fm_set_msg($msg);
        } else {
            $msg = $move ? 'Error while moving items' : 'Error while copying items';
            fm_set_msg($msg, 'error');
        }
    } else {
        fm_set_msg('Nothing selected', 'alert');
    }
    fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
}

// Rename
if (isset($_GET['ren'], $_GET['to']) && !FM_READONLY) {
    // old name
    $old = $_GET['ren'];
    $old = fm_clean_path($old);
    $old = str_replace('/', '', $old);
    // new name
    $new = $_GET['to'];
    $new = fm_clean_path($new);
    $new = str_replace('/', '', $new);
    // path
    $path = FM_ROOT_PATH;
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }
    // rename
    if ($old != '' && $new != '') {
        if (fm_rename($path . '/' . $old, $path . '/' . $new)) {
            fm_set_msg(sprintf('Renamed from <b>%s</b> to <b>%s</b>', fm_enc($old), fm_enc($new)));
        } else {
            fm_set_msg(sprintf('Error while renaming from <b>%s</b> to <b>%s</b>', fm_enc($old), fm_enc($new)), 'error');
        }
    } else {
        fm_set_msg('Names not set', 'error');
    }
    fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
}

// Download
if (isset($_GET['dl'])) {
    $dl = $_GET['dl'];
    $dl = fm_clean_path($dl);
    $dl = str_replace('/', '', $dl);
    $path = FM_ROOT_PATH;
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }
    if ($dl != '' && is_file($path . '/' . $dl)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($path . '/' . $dl) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Connection: Keep-Alive');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($path . '/' . $dl));
        readfile($path . '/' . $dl);
        exit;
    } else {
        fm_set_msg('File not found', 'error');
        fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
    }
}

// Upload
if (!empty($_FILES) && !FM_READONLY) {
    $f = $_FILES;
    $path = FM_ROOT_PATH;
    $ds = DIRECTORY_SEPARATOR;
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }

    $errors = 0;
    $uploads = 0;
    $total = count($f['file']['name']);
    $allowed = (FM_EXTENSION) ? explode(',', FM_EXTENSION) : false;

    $filename = $f['file']['name'];
    $tmp_name = $f['file']['tmp_name'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $isFileAllowed = ($allowed) ? in_array($ext, $allowed) : true;

    $targetPath = $path . $ds;
    $fullPath = $path . '/' . $_REQUEST['fullpath'];
    $folder = substr($fullPath, 0, strrpos($fullPath, "/"));

    if (!is_dir($folder)) {
        $old = umask(0);
        mkdir($folder, 0777, true);
        umask($old);
    }

    if (empty($f['file']['error']) && !empty($tmp_name) && $tmp_name != 'none' && $isFileAllowed) {
        if (move_uploaded_file($tmp_name, $fullPath)) {
            die('Successfully uploaded');
        } else {
            die(sprintf('Error while uploading files. Uploaded files: %s', $uploads));
        }
    }
    exit();
}

// Mass deleting
if (isset($_POST['group'], $_POST['delete']) && !FM_READONLY) {
    $path = FM_ROOT_PATH;
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }

    $errors = 0;
    $files = $_POST['file'];
    if (is_array($files) && count($files)) {
        foreach ($files as $f) {
            if ($f != '') {
                $new_path = $path . '/' . $f;
                if (!fm_rdelete($new_path)) {
                    $errors++;
                }
            }
        }
        if ($errors == 0) {
            fm_set_msg('Selected files and folder deleted');
        } else {
            fm_set_msg('Error while deleting items', 'error');
        }
    } else {
        fm_set_msg('Nothing selected', 'alert');
    }

    fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
}

// Pack files
if (isset($_POST['group'], $_POST['zip']) && !FM_READONLY) {
    $path = FM_ROOT_PATH;
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }

    if (!class_exists('ZipArchive')) {
        fm_set_msg('Operations with archives are not available', 'error');
        fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
    }

    $files = $_POST['file'];
    if (!empty($files)) {
        chdir($path);

        if (count($files) == 1) {
            $one_file = reset($files);
            $one_file = basename($one_file);
            $zipname = $one_file . '_' . date('ymd_His') . '.zip';
        } else {
            $zipname = 'archive_' . date('ymd_His') . '.zip';
        }

        $zipper = new FM_Zipper();
        $res = $zipper->create($zipname, $files);

        if ($res) {
            fm_set_msg(sprintf('Archive <b>%s</b> created', fm_enc($zipname)));
        } else {
            fm_set_msg('Archive not created', 'error');
        }
    } else {
        fm_set_msg('Nothing selected', 'alert');
    }

    fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
}

// Unpack
if (isset($_GET['unzip']) && !FM_READONLY) {
    $unzip = $_GET['unzip'];
    $unzip = fm_clean_path($unzip);
    $unzip = str_replace('/', '', $unzip);

    $path = FM_ROOT_PATH;
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }

    if (!class_exists('ZipArchive')) {
        fm_set_msg('Operations with archives are not available', 'error');
        fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
    }

    if ($unzip != '' && is_file($path . '/' . $unzip)) {
        $zip_path = $path . '/' . $unzip;

        //to folder
        $tofolder = '';
        if (isset($_GET['tofolder'])) {
            $tofolder = pathinfo($zip_path, PATHINFO_FILENAME);
            if (fm_mkdir($path . '/' . $tofolder, true)) {
                $path .= '/' . $tofolder;
            }
        }

        $zipper = new FM_Zipper();
        $res = $zipper->unzip($zip_path, $path);

        if ($res) {
            fm_set_msg('Archive unpacked');
        } else {
            fm_set_msg('Archive not unpacked', 'error');
        }

    } else {
        fm_set_msg('File not found', 'error');
    }
    fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
}

// Change Perms (not for Windows)
if (isset($_POST['chmod']) && !FM_READONLY && !FM_IS_WIN) {
    $path = FM_ROOT_PATH;
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }

    $file = $_POST['chmod'];
    $file = fm_clean_path($file);
    $file = str_replace('/', '', $file);
    if ($file == '' || (!is_file($path . '/' . $file) && !is_dir($path . '/' . $file))) {
        fm_set_msg('File not found', 'error');
        fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
    }

    $mode = 0;
    if (!empty($_POST['ur'])) {
        $mode |= 0400;
    }
    if (!empty($_POST['uw'])) {
        $mode |= 0200;
    }
    if (!empty($_POST['ux'])) {
        $mode |= 0100;
    }
    if (!empty($_POST['gr'])) {
        $mode |= 0040;
    }
    if (!empty($_POST['gw'])) {
        $mode |= 0020;
    }
    if (!empty($_POST['gx'])) {
        $mode |= 0010;
    }
    if (!empty($_POST['or'])) {
        $mode |= 0004;
    }
    if (!empty($_POST['ow'])) {
        $mode |= 0002;
    }
    if (!empty($_POST['ox'])) {
        $mode |= 0001;
    }

    if (@chmod($path . '/' . $file, $mode)) {
        fm_set_msg('Permissions changed');
    } else {
        fm_set_msg('Permissions not changed', 'error');
    }

    fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
}

/*************************** /ACTIONS ***************************/

// get current path
$path = FM_ROOT_PATH;
if (FM_PATH != '') {
    $path .= '/' . FM_PATH;
}

// check path
if (!is_dir($path)) {
    fm_redirect(FM_SELF_URL . '?p=');
}

// get parent folder
$parent = fm_get_parent_path(FM_PATH);

$objects = is_readable($path) ? scandir($path) : array();
$folders = array();
$files = array();
if (is_array($objects)) {
    foreach ($objects as $file) {
        if ($file == '.' || $file == '..' && in_array($file, $GLOBALS['exclude_items'])) {
            continue;
        }
        if (!FM_SHOW_HIDDEN && substr($file, 0, 1) === '.') {
            continue;
        }
        $new_path = $path . '/' . $file;
        if (is_file($new_path) && !in_array($file, $GLOBALS['exclude_items'])) {
            $files[] = $file;
        } elseif (is_dir($new_path) && $file != '.' && $file != '..' && !in_array($file, $GLOBALS['exclude_items'])) {
            $folders[] = $file;
        }
    }
}

if (!empty($files)) {
    natcasesort($files);
}
if (!empty($folders)) {
    natcasesort($folders);
}

// upload form
if (isset($_GET['upload']) && !FM_READONLY) {
    fm_show_header(); // HEADER
    fm_show_nav_path(FM_PATH); // current path
    ?>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.js"></script>
    <script>
        Dropzone.options.fileUploader = {
            init: function () {
                this.on("sending", function (file) {
                    let _path = (file.fullPath) ? file.fullPath : file.name;
                    document.getElementById("fullpath").value = _path
                }).on("success", function (res) {
                    console.log('Upload Status >> ', res.status);
                })
            }
        }
    </script>
    <div class="path">
        <p><b>Uploading files</b></p>
        <p class="break-word">Destination folder: <?php echo fm_enc(fm_convert_win(FM_ROOT_PATH . '/' . FM_PATH)) ?></p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?p=' . fm_enc(FM_PATH) ?>" class="dropzone"
              id="fileUploader" enctype="multipart/form-data">
            <input type="hidden" name="p" value="<?php echo fm_enc(FM_PATH) ?>">
            <input type="hidden" name="fullpath" id="fullpath" value="<?php echo fm_enc(FM_PATH) ?>">
            <div class="fallback">
                <input name="file" type="file" multiple/>
            </div>
        </form>

    </div>
    <?php
    fm_show_footer();
    exit;
}

// copy form POST
if (isset($_POST['copy']) && !FM_READONLY) {
    $copy_files = $_POST['file'];
    if (!is_array($copy_files) || empty($copy_files)) {
        fm_set_msg('Nothing selected', 'alert');
        fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
    }

    fm_show_header(); // HEADER
    fm_show_nav_path(FM_PATH); // current path
    ?>
    <div class="path">
        <p><b>Copying</b></p>
        <form action="" method="post">
            <input type="hidden" name="p" value="<?php echo fm_enc(FM_PATH) ?>">
            <input type="hidden" name="finish" value="1">
            <?php
            foreach ($copy_files as $cf) {
                echo '<input type="hidden" name="file[]" value="' . fm_enc($cf) . '">' . PHP_EOL;
            }
            ?>
            <p class="break-word">Files: <b><?php echo implode('</b>, <b>', $copy_files) ?></b></p>
            <p class="break-word">Source folder: <?php echo fm_enc(fm_convert_win(FM_ROOT_PATH . '/' . FM_PATH)) ?><br>
                <label for="inp_copy_to">Destination folder:</label>
                <?php echo FM_ROOT_PATH ?>/<input type="text" name="copy_to" id="inp_copy_to"
                                                  value="<?php echo fm_enc(FM_PATH) ?>">
            </p>
            <p><label><input type="checkbox" name="move" value="1"> Move'</label></p>
            <p>
                <button type="submit" class="btn"><i class="fa fa-check-circle"></i> Copy</button> &nbsp;
                <b><a href="?p=<?php echo urlencode(FM_PATH) ?>"><i class="fa fa-times-circle"></i> Cancel</a></b>
            </p>
        </form>
    </div>
    <?php
    fm_show_footer();
    exit;
}

// copy form
if (isset($_GET['copy']) && !isset($_GET['finish']) && !FM_READONLY) {
    $copy = $_GET['copy'];
    $copy = fm_clean_path($copy);
    if ($copy == '' || !file_exists(FM_ROOT_PATH . '/' . $copy)) {
        fm_set_msg('File not found', 'error');
        fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
    }

    fm_show_header(); // HEADER
    fm_show_nav_path(FM_PATH); // current path
    ?>
    <div class="path">
        <p><b>Copying</b></p>
        <p class="break-word">
            Source path: <?php echo fm_enc(fm_convert_win(FM_ROOT_PATH . '/' . $copy)) ?><br>
            Destination folder: <?php echo fm_enc(fm_convert_win(FM_ROOT_PATH . '/' . FM_PATH)) ?>
        </p>
        <p>
            <b><a href="?p=<?php echo urlencode(FM_PATH) ?>&amp;copy=<?php echo urlencode($copy) ?>&amp;finish=1"><i
                            class="fa fa-check-circle"></i> Copy</a></b> &nbsp;
            <b><a href="?p=<?php echo urlencode(FM_PATH) ?>&amp;copy=<?php echo urlencode($copy) ?>&amp;finish=1&amp;move=1"><i
                            class="fa fa-check-circle"></i> Move</a></b> &nbsp;
            <b><a href="?p=<?php echo urlencode(FM_PATH) ?>"><i class="fa fa-times-circle"></i> Cancel</a></b>
        </p>
        <p><i>Select folder</i></p>
        <ul class="folders break-word">
            <?php
            if ($parent !== false) {
                ?>
                <li><a href="?p=<?php echo urlencode($parent) ?>&amp;copy=<?php echo urlencode($copy) ?>"><i
                                class="fa fa-chevron-circle-left"></i> ..</a></li>
                <?php
            }
            foreach ($folders as $f) {
                ?>
                <li>
                    <a href="?p=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>&amp;copy=<?php echo urlencode($copy) ?>"><i
                                class="fa fa-folder"></i> <?php echo fm_convert_win($f) ?></a></li>
                <?php
            }
            ?>
        </ul>
    </div>
    <?php
    fm_show_footer();
    exit;
}

// file viewer
if (isset($_GET['view'])) {
    $file = $_GET['view'];
    $file = fm_clean_path($file);
    $file = str_replace('/', '', $file);
    if ($file == '' || !is_file($path . '/' . $file)) {
        fm_set_msg('File not found', 'error');
        fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
    }

    fm_show_header(); // HEADER
    fm_show_nav_path(FM_PATH); // current path

    $file_url = FM_ROOT_URL . fm_convert_win((FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $file);
    $file_path = $path . '/' . $file;

    $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    $mime_type = fm_get_mime_type($file_path);
    $filesize = filesize($file_path);

    $is_zip = false;
    $is_image = false;
    $is_audio = false;
    $is_video = false;
    $is_text = false;

    $view_title = 'File';
    $filenames = false; // for zip
    $content = ''; // for text

    if ($ext == 'zip') {
        $is_zip = true;
        $view_title = 'Archive';
        $filenames = fm_get_zif_info($file_path);
    } elseif (in_array($ext, fm_get_image_exts())) {
        $is_image = true;
        $view_title = 'Image';
    } elseif (in_array($ext, fm_get_audio_exts())) {
        $is_audio = true;
        $view_title = 'Audio';
    } elseif (in_array($ext, fm_get_video_exts())) {
        $is_video = true;
        $view_title = 'Video';
    } elseif (in_array($ext, fm_get_text_exts()) || substr($mime_type, 0, 4) == 'text' || in_array($mime_type, fm_get_text_mimes())) {
        $is_text = true;
        $content = file_get_contents($file_path);
    }

    ?>
    <div class="path">
        <p class="break-word"><b><?php echo $view_title ?> "<?php echo fm_enc(fm_convert_win($file)) ?>"</b></p>
        <p class="break-word">
            Full path: <?php echo fm_enc(fm_convert_win($file_path)) ?><br>
            File
            size: <?php echo fm_get_filesize($filesize) ?><?php if ($filesize >= 1000): ?> (<?php echo sprintf('%s bytes', $filesize) ?>)<?php endif; ?>
            <br>
            MIME-type: <?php echo $mime_type ?><br>
            <?php
            // ZIP info
            if ($is_zip && $filenames !== false) {
                $total_files = 0;
                $total_comp = 0;
                $total_uncomp = 0;
                foreach ($filenames as $fn) {
                    if (!$fn['folder']) {
                        $total_files++;
                    }
                    $total_comp += $fn['compressed_size'];
                    $total_uncomp += $fn['filesize'];
                }
                ?>
                Files in archive: <?php echo $total_files ?><br>
                Total size: <?php echo fm_get_filesize($total_uncomp) ?><br>
                Size in archive: <?php echo fm_get_filesize($total_comp) ?><br>
                Compression: <?php echo round(($total_comp / $total_uncomp) * 100) ?>%<br>
                <?php
            }
            // Image info
            if ($is_image) {
                $image_size = getimagesize($file_path);
                echo 'Image sizes: ' . (isset($image_size[0]) ? $image_size[0] : '0') . ' x ' . (isset($image_size[1]) ? $image_size[1] : '0') . '<br>';
            }
            // Text info
            if ($is_text) {
                $is_utf8 = fm_is_utf8($content);
                if (function_exists('iconv')) {
                    if (!$is_utf8) {
                        $content = iconv(FM_ICONV_INPUT_ENC, 'UTF-8//IGNORE', $content);
                    }
                }
                echo 'Charset: ' . ($is_utf8 ? 'utf-8' : '8 bit') . '<br>';
            }
            ?>
        </p>
        <p>
            <b><a href="?p=<?php echo urlencode(FM_PATH) ?>&amp;dl=<?php echo urlencode($file) ?>"><i
                            class="fa fa-cloud-download"></i> Download</a></b> &nbsp;
            <b><a href="<?php echo fm_enc($file_url) ?>" target="_blank"><i class="fa fa-external-link-square"></i> Open</a></b>
            &nbsp;
            <?php
            // ZIP actions
            if (!FM_READONLY && $is_zip && $filenames !== false) {
                $zip_name = pathinfo($file_path, PATHINFO_FILENAME);
                ?>
                <b><a href="?p=<?php echo urlencode(FM_PATH) ?>&amp;unzip=<?php echo urlencode($file) ?>"><i
                                class="fa fa-check-circle"></i> UnZip</a></b> &nbsp;
                <b><a href="?p=<?php echo urlencode(FM_PATH) ?>&amp;unzip=<?php echo urlencode($file) ?>&amp;tofolder=1"
                      title="UnZip to <?php echo fm_enc($zip_name) ?>"><i class="fa fa-check-circle"></i>
                        UnZip to folder</a></b> &nbsp;
                <?php
            }
            if ($is_text && !FM_READONLY) {
                ?>
                <b><a href="?p=<?php echo urlencode(trim(FM_PATH)) ?>&amp;edit=<?php echo urlencode($file) ?>"
                      class="edit-file"><i class="fa fa-pencil-square"></i> Edit</a></b> &nbsp;
                <b><a href="?p=<?php echo urlencode(trim(FM_PATH)) ?>&amp;edit=<?php echo urlencode($file) ?>&env=ace"
                      class="edit-file"><i class="fa fa-pencil-square"></i> Advanced Edit</a></b> &nbsp;
            <?php } ?>
            <b><a href="?p=<?php echo urlencode(FM_PATH) ?>"><i class="fa fa-chevron-circle-left"></i> Back</a></b>
        </p>
        <?php
        if ($is_zip) {
            // ZIP content
            if ($filenames !== false) {
                echo '<code class="maxheight">';
                foreach ($filenames as $fn) {
                    if ($fn['folder']) {
                        echo '<b>' . fm_enc($fn['name']) . '</b><br>';
                    } else {
                        echo $fn['name'] . ' (' . fm_get_filesize($fn['filesize']) . ')<br>';
                    }
                }
                echo '</code>';
            } else {
                echo '<p>Error while fetching archive info</p>';
            }
        } elseif ($is_image) {
            // Image content
            if (in_array($ext, array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'ico'))) {
                echo '<p><img src="' . fm_enc($file_url) . '" alt="" class="preview-img"></p>';
            }
        } elseif ($is_audio) {
            // Audio content
            echo '<p><audio src="' . fm_enc($file_url) . '" controls preload="metadata"></audio></p>';
        } elseif ($is_video) {
            // Video content
            echo '<div class="preview-video"><video src="' . fm_enc($file_url) . '" width="640" height="360" controls preload="metadata"></video></div>';
        } elseif ($is_text) {
            if (FM_USE_HIGHLIGHTJS) {
                // highlight
                $hljs_classes = array(
                    'shtml' => 'xml',
                    'htaccess' => 'apache',
                    'phtml' => 'php',
                    'lock' => 'json',
                    'svg' => 'xml',
                );
                $hljs_class = isset($hljs_classes[$ext]) ? 'lang-' . $hljs_classes[$ext] : 'lang-' . $ext;
                if (empty($ext) || in_array(strtolower($file), fm_get_text_names()) || preg_match('#\.min\.(css|js)$#i', $file)) {
                    $hljs_class = 'nohighlight';
                }
                $content = '<pre class="with-hljs"><code class="' . $hljs_class . '">' . fm_enc($content) . '</code></pre>';
            } elseif (in_array($ext, array('php', 'php4', 'php5', 'phtml', 'phps'))) {
                // php highlight
                $content = highlight_string($content, true);
            } else {
                $content = '<pre>' . fm_enc($content) . '</pre>';
            }
            echo $content;
        }
        ?>
    </div>
    <?php
    fm_show_footer();
    exit;
}

// file editor
if (isset($_GET['edit'])) {
    $file = $_GET['edit'];
    $file = fm_clean_path($file);
    $file = str_replace('/', '', $file);
    if ($file == '' || !is_file($path . '/' . $file)) {
        fm_set_msg('File not found', 'error');
        fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
    }

    fm_show_header(); // HEADER
    fm_show_nav_path(FM_PATH); // current path

    $file_url = FM_ROOT_URL . fm_convert_win((FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $file);
    $file_path = $path . '/' . $file;

    //normal editer
    $isNormalEditor = true;
    if (isset($_GET['env'])) {
        if ($_GET['env'] == "ace") {
            $isNormalEditor = false;
        }
    }

    //Save File
    if (isset($_POST['savedata'])) {
        $writedata = $_POST['savedata'];
        $fd = fopen($file_path, "w");
        @fwrite($fd, $writedata);
        fclose($fd);
        fm_set_msg('File Saved Successfully', 'alert');
    }

    $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    $mime_type = fm_get_mime_type($file_path);
    $filesize = filesize($file_path);
    $is_text = false;
    $content = ''; // for text

    if (in_array($ext, fm_get_text_exts()) || substr($mime_type, 0, 4) == 'text' || in_array($mime_type, fm_get_text_mimes())) {
        $is_text = true;
        $content = file_get_contents($file_path);
    }

    ?>
    <div class="path">
        <div class="edit-file-actions">
            <a title="Cancel"
               href="?p=<?php echo urlencode(trim(FM_PATH)) ?>&amp;view=<?php echo urlencode($file) ?>"><i
                        class="fa fa-reply-all"></i> Cancel</a>
            <a title="Backup"
               href="javascript:backup('<?php echo urlencode($path) ?>','<?php echo urlencode($file) ?>')"><i
                        class="fa fa-database"></i> Backup</a>
            <?php if ($is_text) { ?>
                <?php if ($isNormalEditor) { ?>
                    <a title="Advanced"
                       href="?p=<?php echo urlencode(trim(FM_PATH)) ?>&amp;edit=<?php echo urlencode($file) ?>&amp;env=ace"><i
                                class="fa fa-paper-plane"></i> Advanced Editor</a>
                    <button type="button" name="Save" data-url="<?php echo fm_enc($file_url) ?>"
                            onclick="edit_save(this,'nrl')"><i class="fa fa-floppy-o"></i> Save
                    </button>
                <?php } else { ?>
                    <a title="Plain Editor"
                       href="?p=<?php echo urlencode(trim(FM_PATH)) ?>&amp;edit=<?php echo urlencode($file) ?>"><i
                                class="fa fa-text-height"></i> Plain Editor</a>
                    <button type="button" name="Save" data-url="<?php echo fm_enc($file_url) ?>"
                            onclick="edit_save(this,'ace')"><i class="fa fa-floppy-o"></i> Save
                    </button>
                <?php } ?>
            <?php } ?>
        </div>
        <?php
        if ($is_text && $isNormalEditor) {
            echo '<textarea id="normal-editor" rows="33" cols="120" style="width: 99.5%;">' . htmlspecialchars($content) . '</textarea>';
        } elseif ($is_text) {
            echo '<div id="editor" contenteditable="true">' . htmlspecialchars($content) . '</div>';
        } else {
            fm_set_msg('FILE EXTENSION HAS NOT SUPPORTED', 'error');
        }
        ?>
    </div>
    <?php
    fm_show_footer();
    exit;
}

// chmod (not for Windows)
if (isset($_GET['chmod']) && !FM_READONLY && !FM_IS_WIN) {
    $file = $_GET['chmod'];
    $file = fm_clean_path($file);
    $file = str_replace('/', '', $file);
    if ($file == '' || (!is_file($path . '/' . $file) && !is_dir($path . '/' . $file))) {
        fm_set_msg('File not found', 'error');
        fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
    }

    fm_show_header(); // HEADER
    fm_show_nav_path(FM_PATH); // current path

    $file_url = FM_ROOT_URL . (FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $file;
    $file_path = $path . '/' . $file;

    $mode = fileperms($path . '/' . $file);

    ?>
    <div class="path">
        <p><b><?php echo 'Change Permissions'; ?></b></p>
        <p>
            <?php echo 'Full path:'; ?> <?php echo $file_path ?><br>
        </p>
        <form action="" method="post">
            <input type="hidden" name="p" value="<?php echo fm_enc(FM_PATH) ?>">
            <input type="hidden" name="chmod" value="<?php echo fm_enc($file) ?>">

            <table class="compact-table">
                <tr>
                    <td></td>
                    <td><b>Owner</b></td>
                    <td><b>Group</b></td>
                    <td><b>Other</b></td>
                </tr>
                <tr>
                    <td style="text-align: right"><b>Read</b></td>
                    <td><label><input type="checkbox" name="ur"
                                      value="1"<?php echo ($mode & 00400) ? ' checked' : '' ?>></label></td>
                    <td><label><input type="checkbox" name="gr"
                                      value="1"<?php echo ($mode & 00040) ? ' checked' : '' ?>></label></td>
                    <td><label><input type="checkbox" name="or"
                                      value="1"<?php echo ($mode & 00004) ? ' checked' : '' ?>></label></td>
                </tr>
                <tr>
                    <td style="text-align: right"><b>Write</b></td>
                    <td><label><input type="checkbox" name="uw"
                                      value="1"<?php echo ($mode & 00200) ? ' checked' : '' ?>></label></td>
                    <td><label><input type="checkbox" name="gw"
                                      value="1"<?php echo ($mode & 00020) ? ' checked' : '' ?>></label></td>
                    <td><label><input type="checkbox" name="ow"
                                      value="1"<?php echo ($mode & 00002) ? ' checked' : '' ?>></label></td>
                </tr>
                <tr>
                    <td style="text-align: right"><b>Execute</b></td>
                    <td><label><input type="checkbox" name="ux"
                                      value="1"<?php echo ($mode & 00100) ? ' checked' : '' ?>></label></td>
                    <td><label><input type="checkbox" name="gx"
                                      value="1"<?php echo ($mode & 00010) ? ' checked' : '' ?>></label></td>
                    <td><label><input type="checkbox" name="ox"
                                      value="1"<?php echo ($mode & 00001) ? ' checked' : '' ?>></label></td>
                </tr>
            </table>

            <p>
                <button type="submit" class="btn"><i class="fa fa-check-circle"></i> Change</button> &nbsp;
                <b><a href="?p=<?php echo urlencode(FM_PATH) ?>"><i class="fa fa-times-circle"></i> Cancel</a></b>
            </p>

        </form>

    </div>
    <?php
    fm_show_footer();
    exit;
}

//--- FILEMANAGER MAIN
fm_show_header(); // HEADER
fm_show_nav_path(FM_PATH); // current path

// messages
fm_show_message();

$num_files = count($files);
$num_folders = count($folders);
$all_files_size = 0;
?>
<style type="text/css">
    a{
        color: #e64980 ;
    }
    a:hover{
        color: #c25e81 !important;
    }
    .contextmenu li a:hover{
        text-decoration: none;
        background-color: #e64980;
        color: white !important;
    }

    .contextmenu li a:hover i{
        color: white; 
    }
    .contextmenu li a i{
            font-size: 1em;
            margin-left: 5px;
            
            color: #e64980;
            padding: 3px;
            border-radius: 3px;
    }
    .contextmenu li a{
        color: #e64980 !important;
        padding:10px !important;
        font-size: 14px;
    }

    .inline-actions a i.fa{
            color: #e64980;
            background-color: transparent;
            background-image: none;
            border-color: #007bff;
            display: inline-block;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
            border: 1px solid transparent;
            padding: .320rem .60rem;
            font-size: 1rem;
            line-height: 1.5;
            border-radius: .25rem;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
   .inline-actions a:hover i.fa{
    color: #fff;
    background-color: #e64980;
    }
    .filename a{
        color: #e64980;
    }
    .custom-checkbox .custom-control-input:checked~.custom-control-label::after{
        background-color: #e64980;
        border-radius: 4px;
    }
</style>
<form action="" method="post" class="pt-3">
    <input type="hidden" name="p" value="<?php echo fm_enc(FM_PATH) ?>">
    <input type="hidden" name="group" value="1">
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm bg-white" id="main-table">
            <thead class="thead-light">
            <tr>
                <?php if (!FM_READONLY): ?>
                    <th style="width:3%">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="js-select-all-items" onclick="checkbox_toggle()">
                            <label class="custom-control-label" for="js-select-all-items"></label>
                        </div>
                    </th><?php endif; ?>
                <th onclick="sortTable(0,this);">Name <span class="fa fa-sort-asc float-right pr-4"></span></th>
                <th onclick="sortTable(0,this)">Size <span class="fa fa-sort-asc float-right pr-4"></span></th>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

                <th onclick="sortTable(0,this);" class="content">Modified <span class="fa fa-sort-asc float-right pr-4"></span></th>
                <?php if (!FM_IS_WIN): ?>
                    <th>Permission</th>
                    <th>Owner</th><?php endif; ?>
                <th>Actions</th>
            </tr>
            </thead>
            <?php
            // link to parent folder
            if ($parent !== false) {
                ?>
                <tr><?php if (!FM_READONLY): ?>
                        <td></td><?php endif; ?>
                    <td colspan="<?php echo !FM_IS_WIN ? '6' : '4' ?>"><a href="?p=<?php echo urlencode($parent) ?>"><i
                                    class="fa fa-chevron-circle-left"></i> ..</a></td>
                </tr>
                <?php
            }
            $ii = 3399;
            foreach ($folders as $f) {
                $is_link = is_link($path . '/' . $f);
                $img = $is_link ? 'icon-link_folder' : 'fa fa-folder';
                $modif = date(FM_DATETIME_FORMAT, filemtime($path . '/' . $f));
                $perms = substr(decoct(fileperms($path . '/' . $f)), -4);
                if (function_exists('posix_getpwuid') && function_exists('posix_getgrgid')) {
                    $owner = posix_getpwuid(fileowner($path . '/' . $f));
                    $group = posix_getgrgid(filegroup($path . '/' . $f));
                } else {
                    $owner = array('name' => '?');
                    $group = array('name' => '?');
                }
                ?>
                <tr class="table_tr">
                    <?php if (!FM_READONLY): ?>
                        <td>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="<?php echo $ii ?>" name="file[]" value="<?php echo fm_enc($f) ?>">
                            <label class="custom-control-label" for="<?php echo $ii ?>"></label>
                        </div>
                        </td><?php endif; ?>
                    <td>
                        <div class="filename"><a href="?p=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>"><i
                                        class="<?php echo $img ?>"></i> <?php echo fm_convert_win($f) ?>
                            </a><?php echo($is_link ? ' &rarr; <i>' . readlink($path . '/' . $f) . '</i>' : '') ?></div>
                        
                    </td>
                    <td>Folder</td>
                    <td><?php echo $modif ?></td>
                    <?php if (!FM_IS_WIN): ?>
                        <td><?php if (!FM_READONLY): ?><a title="Change Permissions"
                                                          href="?p=<?php echo urlencode(FM_PATH) ?>&amp;chmod=<?php echo urlencode($f) ?>"><?php echo $perms ?></a><?php else: ?><?php echo $perms ?><?php endif; ?>
                        </td>
                        <td><?php echo $owner['name'] . ':' . $group['name'] ?></td>
                    <?php endif; ?>
                    <td class="inline-actions"><?php if (!FM_READONLY): ?>
                            <a title="Delete" href="?p=<?php echo urlencode(FM_PATH) ?>&amp;del=<?php echo urlencode($f) ?>"
                               onclick="alert(this)" rel="<?php echo fm_enc($f) ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                            <a title="Rename" href="#" onclick="rename('<?php echo fm_enc(FM_PATH) ?>', '<?php echo fm_enc($f) ?>');return false;"><i
                                        class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <a title="Copy to..."
                               href="?p=&amp;copy=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>"><i
                                        class="fa fa-files-o" aria-hidden="true"></i></a>
                        <?php endif; ?>
                        <a title="Direct link"
                           href="<?php echo fm_enc(FM_ROOT_URL . (FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $f . '/') ?>"
                           target="_blank"><i class="fa fa-link" aria-hidden="true"></i></a>
                    </td>
                </tr>
                <?php
                flush();
                $ii++;
            }
            $ik = 6070;
            foreach ($files as $f) {
                $is_link = is_link($path . '/' . $f);
                $img = $is_link ? 'fa fa-file-text-o' : fm_get_file_icon_class($path . '/' . $f);
                $modif = date(FM_DATETIME_FORMAT, filemtime($path . '/' . $f));
                $filesize_raw = filesize($path . '/' . $f);
                $filesize = fm_get_filesize($filesize_raw);
                $filelink = '?p=' . urlencode(FM_PATH) . '&amp;view=' . urlencode($f);
                $all_files_size += $filesize_raw;
                $perms = substr(decoct(fileperms($path . '/' . $f)), -4);
                if (function_exists('posix_getpwuid') && function_exists('posix_getgrgid')) {
                    $owner = posix_getpwuid(fileowner($path . '/' . $f));
                    $group = posix_getgrgid(filegroup($path . '/' . $f));
                } else {
                    $owner = array('name' => '?');
                    $group = array('name' => '?');
                }
                ?>
                <tr class="table_tr">
                    <?php if (!FM_READONLY): ?>
                        <td>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="<?php echo $ik ?>" name="file[]" value="<?php echo fm_enc($f) ?>">
                            <label class="custom-control-label" for="<?php echo $ik ?>"></label>
                        </div>
                        </td><?php endif; ?>
                    <td>
                        <div class="filename"><a href="<?php echo $filelink ?>" title="File info"><i
                                        class="<?php echo $img ?>"></i> <?php echo fm_convert_win($f) ?>
                            </a><?php echo($is_link ? ' &rarr; <i>' . readlink($path . '/' . $f) . '</i>' : '') ?></div>
                    </td>
                    <td><span title="<?php printf('%s bytes', $filesize_raw) ?>"><?php echo $filesize ?></span></td>
                    <td><?php echo $modif ?></td>
                    <?php if (!FM_IS_WIN): ?>
                        <td><?php if (!FM_READONLY): ?><a title="<?php echo 'Change Permissions' ?>"
                                                          href="?p=<?php echo urlencode(FM_PATH) ?>&amp;chmod=<?php echo urlencode($f) ?>"><?php echo $perms ?></a><?php else: ?><?php echo $perms ?><?php endif; ?>
                        </td>
                        <td><?php echo fm_enc($owner['name'] . ':' . $group['name']) ?></td>
                    <?php endif; ?>
                    <td class="inline-actions">
                        <?php if (!FM_READONLY): ?>
                            <a title="Delete" href="?p=<?php echo urlencode(FM_PATH) ?>&amp;del=<?php echo urlencode($f) ?>"
                               onclick="return confirm('Delete file?');"><i class="fa fa-trash-o"></i></a>
                            <a title="Rename" href="#"><i
                                        class="fa fa-pencil-square-o"></i></a>
                            <a title="Copy to..."
                               href="?p=<?php echo urlencode(FM_PATH) ?>&amp;copy=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>"><i
                                        class="fa fa-files-o"></i></a>
                        <?php endif; ?>
                        <a title="Direct link"
                           href="<?php echo fm_enc(FM_ROOT_URL . (FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $f) ?>"
                           target="_blank"><i class="fa fa-link"></i></a>
                        <a title="Download" href="?p=<?php echo urlencode(FM_PATH) ?>&amp;dl=<?php echo urlencode($f) ?>"><i
                                    class="fa fa-download"></i></a>
                    </td>
                </tr>
                <?php
                flush();
                $ik++;
            }

            if (empty($folders) && empty($files)) {
                ?>
                <tfoot>
                    <tr><?php if (!FM_READONLY): ?>
                            <td></td><?php endif; ?>
                        <td colspan="<?php echo !FM_IS_WIN ? '6' : '4' ?>"><em><?php echo 'Folder is empty' ?></em></td>
                    </tr>
                </tfoot>
                <?php
            } else {
                ?>
                <tfoot>
                    <tr><?php if (!FM_READONLY): ?>
                            <td class="gray"></td><?php endif; ?>
                        <td class="gray" colspan="<?php echo !FM_IS_WIN ? '6' : '4' ?>">
                            Full size: <span
                                    title="<?php printf('%s bytes', $all_files_size) ?>"><?php echo fm_get_filesize($all_files_size) ?></span>,
                            files: <?php echo $num_files ?>,
                            folders: <?php echo $num_folders ?>
                        </td>
                    </tr>
                </tfoot>
                <?php
            }
            ?>
        </table>
    </div>
    <?php if (!FM_READONLY): ?>
        <div class="row">
            <div class="col-xs-12 col-sm-9">
                <ul class="list-inline footer-action">
                    <li class="list-inline-item"> <a href="#/select-all" class="btn btn-small btn-outline-primary btn-2" onclick="select_all();return false;"><i class="fa fa-check-square"></i> Select all</a></li>
                    <li class="list-inline-item"><a href="#/unselect-all" class="btn btn-small btn-outline-primary btn-2" onclick="unselect_all();return false;"><i class="fa fa-window-close"></i> Unselect all</a></li>
                    <li class="list-inline-item"><a href="#/invert-all" class="btn btn-small btn-outline-primary btn-2" onclick="invert_all();return false;"><i class="fa fa-th-list"></i> Invert selection</a></li>
                    <li class="list-inline-item"><input type="submit" class="hidden" name="delete" id="a-delete" value="Delete" onclick="return confirm('Delete selected files and folders?')">
                        <a href="javascript:document.getElementById('a-delete').click();" class="btn btn-small btn-outline-primary btn-2"><i class="fa fa-trash"></i> Delete </a></li>
                    <li class="list-inline-item"><input type="submit" class="hidden" name="zip" id="a-zip" value="Zip" onclick="return confirm('Create archive?')">
                        <a href="javascript:document.getElementById('a-zip').click();" class="btn btn-small btn-outline-primary btn-2"><i class="fa fa-file-archive-o"></i> Zip </a></li>
                        <li class="list-inline-item">
                        <!--<input type="submit" class="hidden" name="zip" id="a-zip" value="Zip" onclick="return confirm('Create archive?')">-->
                        <a href="" id="unzip_file" class="btn btn-small btn-outline-primary btn-2"><i class="fa fa-file-archive-o"></i> UnZip </a>
                    </li>
                    <li class="list-inline-item"><input type="submit" class="hidden" name="copy" id="a-copy" value="Copy">
                        <a href="javascript:document.getElementById('a-copy').click();" class="btn btn-small btn-outline-primary btn-2"><i class="fa fa-files-o"></i> Copy </a></li>
                </ul>
            </div>

    <?php endif; ?>
</form>

<?php
fm_show_footer();

//--- END

// Functions

/**
 * Delete  file or folder (recursively)
 * @param string $path
 * @return bool
 */
function fm_rdelete($path)
{
    if (is_link($path)) {
        return unlink($path);
    } elseif (is_dir($path)) {
        $objects = scandir($path);
        $ok = true;
        if (is_array($objects)) {
            foreach ($objects as $file) {
                if ($file != '.' && $file != '..') {
                    if (!fm_rdelete($path . '/' . $file)) {
                        $ok = false;
                    }
                }
            }
        }
        return ($ok) ? rmdir($path) : false;
    } elseif (is_file($path)) {
        return unlink($path);
    }
    return false;
}

/**
 * Recursive chmod
 * @param string $path
 * @param int $filemode
 * @param int $dirmode
 * @return bool
 * @todo Will use in mass chmod
 */
function fm_rchmod($path, $filemode, $dirmode)
{
    if (is_dir($path)) {
        if (!chmod($path, $dirmode)) {
            return false;
        }
        $objects = scandir($path);
        if (is_array($objects)) {
            foreach ($objects as $file) {
                if ($file != '.' && $file != '..') {
                    if (!fm_rchmod($path . '/' . $file, $filemode, $dirmode)) {
                        return false;
                    }
                }
            }
        }
        return true;
    } elseif (is_link($path)) {
        return true;
    } elseif (is_file($path)) {
        return chmod($path, $filemode);
    }
    return false;
}

/**
 * Safely rename
 * @param string $old
 * @param string $new
 * @return bool|null
 */
function fm_rename($old, $new)
{
    return (!file_exists($new) && file_exists($old)) ? rename($old, $new) : null;
}

/**
 * Copy file or folder (recursively).
 * @param string $path
 * @param string $dest
 * @param bool $upd Update files
 * @param bool $force Create folder with same names instead file
 * @return bool
 */
function fm_rcopy($path, $dest, $upd = true, $force = true)
{
    if (is_dir($path)) {
        if (!fm_mkdir($dest, $force)) {
            return false;
        }
        $objects = scandir($path);
        $ok = true;
        if (is_array($objects)) {
            foreach ($objects as $file) {
                if ($file != '.' && $file != '..') {
                    if (!fm_rcopy($path . '/' . $file, $dest . '/' . $file)) {
                        $ok = false;
                    }
                }
            }
        }
        return $ok;
    } elseif (is_file($path)) {
        return fm_copy($path, $dest, $upd);
    }
    return false;
}

/**
 * Safely create folder
 * @param string $dir
 * @param bool $force
 * @return bool
 */
function fm_mkdir($dir, $force)
{
    if (file_exists($dir)) {
        if (is_dir($dir)) {
            return $dir;
        } elseif (!$force) {
            return false;
        }
        unlink($dir);
    }
    return mkdir($dir, 0777, true);
}

/**
 * Safely copy file
 * @param string $f1
 * @param string $f2
 * @param bool $upd
 * @return bool
 */
function fm_copy($f1, $f2, $upd)
{
    $time1 = filemtime($f1);
    if (file_exists($f2)) {
        $time2 = filemtime($f2);
        if ($time2 >= $time1 && $upd) {
            return false;
        }
    }
    $ok = copy($f1, $f2);
    if ($ok) {
        touch($f2, $time1);
    }
    return $ok;
}

/**
 * Get mime type
 * @param string $file_path
 * @return mixed|string
 */
function fm_get_mime_type($file_path)
{
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file_path);
        finfo_close($finfo);
        return $mime;
    } elseif (function_exists('mime_content_type')) {
        return mime_content_type($file_path);
    } elseif (!stristr(ini_get('disable_functions'), 'shell_exec')) {
        $file = escapeshellarg($file_path);
        $mime = shell_exec('file -bi ' . $file);
        return $mime;
    } else {
        return '--';
    }
}

/**
 * HTTP Redirect
 * @param string $url
 * @param int $code
 */
function fm_redirect($url, $code = 302)
{
    header('Location: ' . $url, true, $code);
    exit;
}

/**
 * Clean path
 * @param string $path
 * @return string
 */
function fm_clean_path($path)
{
    $path = trim($path);
    $path = trim($path, '\\/');
    $path = str_replace(array('../', '..\\'), '', $path);
    if ($path == '..') {
        $path = '';
    }
    return str_replace('\\', '/', $path);
}

/**
 * Get parent path
 * @param string $path
 * @return bool|string
 */
function fm_get_parent_path($path)
{
    $path = fm_clean_path($path);
    if ($path != '') {
        $array = explode('/', $path);
        if (count($array) > 1) {
            $array = array_slice($array, 0, -1);
            return implode('/', $array);
        }
        return '';
    }
    return false;
}

/**
 * Get nice filesize
 * @param int $size
 * @return string
 */
function fm_get_filesize($size)
{
    if ($size < 1000) {
        return sprintf('%s B', $size);
    } elseif (($size / 1024) < 1000) {
        return sprintf('%s KiB', round(($size / 1024), 2));
    } elseif (($size / 1024 / 1024) < 1000) {
        return sprintf('%s MiB', round(($size / 1024 / 1024), 2));
    } elseif (($size / 1024 / 1024 / 1024) < 1000) {
        return sprintf('%s GiB', round(($size / 1024 / 1024 / 1024), 2));
    } else {
        return sprintf('%s TiB', round(($size / 1024 / 1024 / 1024 / 1024), 2));
    }
}

/**
 * Get info about zip archive
 * @param string $path
 * @return array|bool
 */
function fm_get_zif_info($path)
{
    if (function_exists('zip_open')) {
        $arch = zip_open($path);
        if ($arch) {
            $filenames = array();
            while ($zip_entry = zip_read($arch)) {
                $zip_name = zip_entry_name($zip_entry);
                $zip_folder = substr($zip_name, -1) == '/';
                $filenames[] = array(
                    'name' => $zip_name,
                    'filesize' => zip_entry_filesize($zip_entry),
                    'compressed_size' => zip_entry_compressedsize($zip_entry),
                    'folder' => $zip_folder
                    //'compression_method' => zip_entry_compressionmethod($zip_entry),
                );
            }
            zip_close($arch);
            return $filenames;
        }
    }
    return false;
}

/**
 * Encode html entities
 * @param string $text
 * @return string
 */
function fm_enc($text)
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/**
 * This function scans the files folder recursively, and builds a large array
 * @param string $dir
 * @return json
 */
function scan($dir)
{
    $files = array();
    $_dir = $dir;
    $dir = FM_ROOT_PATH . '/' . $dir;
    // Is there actually such a folder/file?
    if (file_exists($dir)) {
        foreach (scandir($dir) as $f) {
            if (!$f || $f[0] == '.') {
                continue; // Ignore hidden files
            }

            if (is_dir($dir . '/' . $f)) {
                // The path is a folder
                $files[] = array(
                    "name" => $f,
                    "type" => "folder",
                    "path" => $_dir . '/' . $f,
                    "items" => scan($dir . '/' . $f), // Recursively get the contents of the folder
                );
            } else {
                // It is a file
                $files[] = array(
                    "name" => $f,
                    "type" => "file",
                    "path" => $_dir,
                    "size" => filesize($dir . '/' . $f) // Gets the size of this file
                );
            }
        }
    }
    return $files;
}

/**
 * Save message in session
 * @param string $msg
 * @param string $status
 */
function fm_set_msg($msg, $status = 'ok')
{
    $_SESSION['message'] = $msg;
    $_SESSION['status'] = $status;
}

/**
 * Check if string is in UTF-8
 * @param string $string
 * @return int
 */
function fm_is_utf8($string)
{
    return preg_match('//u', $string);
}

/**
 * Convert file name to UTF-8 in Windows
 * @param string $filename
 * @return string
 */
function fm_convert_win($filename)
{
    if (FM_IS_WIN && function_exists('iconv')) {
        $filename = iconv(FM_ICONV_INPUT_ENC, 'UTF-8//IGNORE', $filename);
    }
    return $filename;
}

/**
 * Get CSS classname for file
 * @param string $path
 * @return string
 */
function fm_get_file_icon_class($path)
{
    // get extension
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

    switch ($ext) {
        case 'ico':
        case 'gif':
        case 'jpg':
        case 'jpeg':
        case 'jpc':
        case 'jp2':
        case 'jpx':
        case 'xbm':
        case 'wbmp':
        case 'png':
        case 'bmp':
        case 'tif':
        case 'tiff':
        case 'svg':
            $img = 'fa fa-picture-o';
            break;
        case 'passwd':
        case 'ftpquota':
        case 'sql':
        case 'js':
        case 'json':
        case 'sh':
        case 'config':
        case 'twig':
        case 'tpl':
        case 'md':
        case 'gitignore':
        case 'c':
        case 'cpp':
        case 'cs':
        case 'py':
        case 'map':
        case 'lock':
        case 'dtd':
            $img = 'fa fa-file-code-o';
            break;
        case 'txt':
        case 'ini':
        case 'conf':
        case 'log':
        case 'htaccess':
            $img = 'fa fa-file-text-o';
            break;
        case 'css':
        case 'less':
        case 'sass':
        case 'scss':
            $img = 'fa fa-css3';
            break;
        case 'zip':
        case 'rar':
        case 'gz':
        case 'tar':
        case '7z':
            $img = 'fa fa-file-archive-o';
            break;
        case 'php':
        case 'php4':
        case 'php5':
        case 'phps':
        case 'phtml':
            $img = 'fa fa-code';
            break;
        case 'htm':
        case 'html':
        case 'shtml':
        case 'xhtml':
            $img = 'fa fa-html5';
            break;
        case 'xml':
        case 'xsl':
            $img = 'fa fa-file-excel-o';
            break;
        case 'wav':
        case 'mp3':
        case 'mp2':
        case 'm4a':
        case 'aac':
        case 'ogg':
        case 'oga':
        case 'wma':
        case 'mka':
        case 'flac':
        case 'ac3':
        case 'tds':
            $img = 'fa fa-music';
            break;
        case 'm3u':
        case 'm3u8':
        case 'pls':
        case 'cue':
            $img = 'fa fa-headphones';
            break;
        case 'avi':
        case 'mpg':
        case 'mpeg':
        case 'mp4':
        case 'm4v':
        case 'flv':
        case 'f4v':
        case 'ogm':
        case 'ogv':
        case 'mov':
        case 'mkv':
        case '3gp':
        case 'asf':
        case 'wmv':
            $img = 'fa fa-file-video-o';
            break;
        case 'eml':
        case 'msg':
            $img = 'fa fa-envelope-o';
            break;
        case 'xls':
        case 'xlsx':
            $img = 'fa fa-file-excel-o';
            break;
        case 'csv':
            $img = 'fa fa-file-text-o';
            break;
        case 'bak':
            $img = 'fa fa-clipboard';
            break;
        case 'doc':
        case 'docx':
            $img = 'fa fa-file-word-o';
            break;
        case 'ppt':
        case 'pptx':
            $img = 'fa fa-file-powerpoint-o';
            break;
        case 'ttf':
        case 'ttc':
        case 'otf':
        case 'woff':
        case 'woff2':
        case 'eot':
        case 'fon':
            $img = 'fa fa-font';
            break;
        case 'pdf':
            $img = 'fa fa-file-pdf-o';
            break;
        case 'psd':
        case 'ai':
        case 'eps':
        case 'fla':
        case 'swf':
            $img = 'fa fa-file-image-o';
            break;
        case 'exe':
        case 'msi':
            $img = 'fa fa-file-o';
            break;
        case 'bat':
            $img = 'fa fa-terminal';
            break;
        default:
            $img = 'fa fa-info-circle';
    }

    return $img;
}

/**
 * Get image files extensions
 * @return array
 */
function fm_get_image_exts()
{
    return array('ico', 'gif', 'jpg', 'jpeg', 'jpc', 'jp2', 'jpx', 'xbm', 'wbmp', 'png', 'bmp', 'tif', 'tiff', 'psd');
}

/**
 * Get video files extensions
 * @return array
 */
function fm_get_video_exts()
{
    return array('webm', 'mp4', 'm4v', 'ogm', 'ogv', 'mov');
}

/**
 * Get audio files extensions
 * @return array
 */
function fm_get_audio_exts()
{
    return array('wav', 'mp3', 'ogg', 'm4a');
}

/**
 * Get text file extensions
 * @return array
 */
function fm_get_text_exts()
{
    return array(
        'txt', 'css', 'ini', 'conf', 'log', 'htaccess', 'passwd', 'ftpquota', 'sql', 'js', 'json', 'sh', 'config',
        'php', 'php4', 'php5', 'phps', 'phtml', 'htm', 'html', 'shtml', 'xhtml', 'xml', 'xsl', 'm3u', 'm3u8', 'pls', 'cue',
        'eml', 'msg', 'csv', 'bat', 'twig', 'tpl', 'md', 'gitignore', 'less', 'sass', 'scss', 'c', 'cpp', 'cs', 'py',
        'map', 'lock', 'dtd', 'svg',
    );
}

/**
 * Get mime types of text files
 * @return array
 */
function fm_get_text_mimes()
{
    return array(
        'application/xml',
        'application/javascript',
        'application/x-javascript',
        'image/svg+xml',
        'message/rfc822',
    );
}

/**
 * Get file names of text files w/o extensions
 * @return array
 */
function fm_get_text_names()
{
    return array(
        'license',
        'readme',
        'authors',
        'contributors',
        'changelog',
    );
}

/**
 * Class to work with zip files (using ZipArchive)
 */
class FM_Zipper
{
    private $zip;

    public function __construct()
    {
        $this->zip = new ZipArchive();
    }

    /**
     * Create archive with name $filename and files $files (RELATIVE PATHS!)
     * @param string $filename
     * @param array|string $files
     * @return bool
     */
    public function create($filename, $files)
    {
        $res = $this->zip->open($filename, ZipArchive::CREATE);
        if ($res !== true) {
            return false;
        }
        if (is_array($files)) {
            foreach ($files as $f) {
                if (!$this->addFileOrDir($f)) {
                    $this->zip->close();
                    return false;
                }
            }
            $this->zip->close();
            return true;
        } else {
            if ($this->addFileOrDir($files)) {
                $this->zip->close();
                return true;
            }
            return false;
        }
    }

    /**
     * Extract archive $filename to folder $path (RELATIVE OR ABSOLUTE PATHS)
     * @param string $filename
     * @param string $path
     * @return bool
     */
    public function unzip($filename, $path)
    {
        $res = $this->zip->open($filename);
        if ($res !== true) {
            return false;
        }
        if ($this->zip->extractTo($path)) {
            $this->zip->close();
            return true;
        }
        return false;
    }

    /**
     * Add file/folder to archive
     * @param string $filename
     * @return bool
     */
    private function addFileOrDir($filename)
    {
        if (is_file($filename)) {
            return $this->zip->addFile($filename);
        } elseif (is_dir($filename)) {
            return $this->addDir($filename);
        }
        return false;
    }

    /**
     * Add folder recursively
     * @param string $path
     * @return bool
     */
    private function addDir($path)
    {
        if (!$this->zip->addEmptyDir($path)) {
            return false;
        }
        $objects = scandir($path);
        if (is_array($objects)) {
            foreach ($objects as $file) {
                if ($file != '.' && $file != '..') {
                    if (is_dir($path . '/' . $file)) {
                        if (!$this->addDir($path . '/' . $file)) {
                            return false;
                        }
                    } elseif (is_file($path . '/' . $file)) {
                        if (!$this->zip->addFile($path . '/' . $file)) {
                            return false;
                        }
                    }
                }
            }
            return true;
        }
        return false;
    }
}

//--- templates functions

/**
 * Show nav block
 * @param string $path
 */
function fm_show_nav_path($path)
{
    global $lang;
    ?>
    <nav class="navbar navbar-expand-md fixed-top navbar-light bg-white mb-4 main-nav">
        <a class="navbar-brand" href="">Lite File Manager</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <?php
            $path = fm_clean_path($path);
            $root_url = "<a href='?p='><i class='fa fa-home' aria-hidden='true' title='" . FM_ROOT_PATH . "'></i></a>";
            $sep = '<i class="fa fa-caret-right"></i>';
            if ($path != '') {
                $exploded = explode('/', $path);
                $count = count($exploded);
                $array = array();
                $parent = '';
                for ($i = 0; $i < $count; $i++) {
                    $parent = trim($parent . '/' . $exploded[$i], '/');
                    $parent_enc = urlencode($parent);
                    $array[] = "<a href='?p={$parent_enc}'>" . fm_enc(fm_convert_win($exploded[$i])) . "</a>";
                }
                $root_url .= $sep . implode($sep, $array);
            }
            echo '<div class="col-xs-6 col-sm-7">' . $root_url . '</div>';
            ?>

            <div class="col-xs-12 col-sm-5 text-right">
                <ul class="navbar-nav mr-auto float-right">
                    <?php if (!FM_READONLY): ?>
                     <li class="nav-item">
                        <a title="Refresh" class="nav-link" href="<?php echo urlencode(FM_PATH) ?>"><i
                                    class="fa fa-refresh"></i> Refresh</a>
                    </li>
                     
                    <li class="nav-item">
                        <a title="Search" class="nav-link" href="javascript:showSearch('<?php echo urlencode(FM_PATH) ?>')"><i
                                    class="fa fa-search"></i> Search</a>
                    </li>
                    <li class="nav-item">
                        <a title="Upload files" class="nav-link" href="?p=<?php echo urlencode(FM_PATH) ?>&amp;upload"><i class="fa fa-cloud-upload" aria-hidden="true"></i> Upload</a>
                    </li>
                    <li class="nav-item">
                        <a title="New folder" class="nav-link" href="#createNewItem" data-toggle="modal" data-target="#createNewItem"><i class="fa fa-plus-square"></i> New Item</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <?php if (FM_USE_AUTH): ?><a title="Logout" class="nav-link" href="?logout=1"><i class="fa fa-sign-out" aria-hidden="true"></i> Sign out</a><?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php
}

/**
 * Show message from session
 */
function fm_show_message()
{
    if (isset($_SESSION['message'])) {
        $class = isset($_SESSION['status']) ? $_SESSION['status'] : 'ok';
        echo '<p class="message ' . $class . '">' . $_SESSION['message'] . '</p>';
        unset($_SESSION['message']);
        unset($_SESSION['status']);
    }
}

/**
 * Show page header in Login Form
 */
function fm_show_header_login()
{
$sprites_ver = '20160315';
header("Content-Type: text/html; charset=utf-8");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
header("Pragma: no-cache");

global $lang;
?>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="PHP Lite File Manager, Manage your files efficiently and easily">
    <meta name="author" content="Two Color Ocean Media">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex">
    <title>PHP Lite File Manager</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="asset/style.css">
</head>
<body style="background-color: #f5f5f5;">
<div id="wrapper" class="container-fluid">

    <?php
    }

    /**
     * Show page footer in Login Form
     */
    function fm_show_footer_login()
    {
    ?>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
<?php
}

/**
 * Show Header after login
 */
function fm_show_header()
{
$sprites_ver = '20160315';
header("Content-Type: text/html; charset=utf-8");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
header("Pragma: no-cache");

global $lang;
?>
    <!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="PHP Lite File Manager, Manage your files efficiently and easily">
    <meta name="author" content="Two Color Ocean Media">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex">
    <title>PHP Lite File Manager</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <?php if (isset($_GET['view']) && FM_USE_HIGHLIGHTJS): ?>
        <link rel="stylesheet"
              href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.2.0/styles/<?php echo FM_HIGHLIGHTJS_STYLE ?>.min.css">
    <?php endif; ?>
    <style>
        .filename, td, th {
            white-space: nowrap
        }

        body {
            margin-top: 55px;
            font: 13px/16px Tahoma, Arial, sans-serif;
            color: #222;
            background: #F7F7F7;
        }

        .navbar-brand {
            font-weight: bold;
        }

        #main-table a {
            text-decoration: none;
        }

        .table td, .table th {
            vertical-align: middle !important;
        }

        .hidden {
            display: none
        }

        pre.with-hljs {
            padding: 0
        }

        pre.with-hljs code {
            margin: 0;
            border: 0;
            overflow: visible
        }

        code.maxheight, pre.maxheight {
            max-height: 512px
        }

        .fa.fa-caret-right {
            font-size: 1.2em;
            margin: 0 4px;
            vertical-align: middle;
            color: #ececec
        }

        .fa.fa-home {
            font-size: 1.2em;
            vertical-align: bottom
        }

        .path {
            margin-bottom: 10px
        }

        .right {
            text-align: right
        }

        .center, .close, .login-form {
            text-align: center
        }

        .message {
            padding: 4px 7px;
            border: 1px solid #ddd;
            background-color: #fff
        }

        .message.ok {
            border-color: green;
            color: green
        }

        .message.error {
            border-color: red;
            color: red
        }

        .message.alert {
            border-color: orange;
            color: orange
        }

        .preview-img {
            max-width: 100%;
            background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAACQkWg2AAAAKklEQVR42mL5//8/Azbw+PFjrOJMDCSCUQ3EABZc4S0rKzsaSvTTABBgAMyfCMsY4B9iAAAAAElFTkSuQmCC)
        }

        .inline-actions > a > i {
            font-size: 1em;
            margin-left: 5px;
            background: #3785c1;
            color: #fff;
            padding: 3px;
            border-radius: 3px
            -webkit-transition: all;
            transition: all;
            transition-timing-function: ease;
            transition-duration: 1s;
        }

        .preview-video {
            position: relative;
            max-width: 100%;
            height: 0;
            padding-bottom: 62.5%;
            margin-bottom: 10px
        }

        .preview-video video {
            position: absolute;
            width: 100%;
            height: 100%;
            left: 0;
            top: 0;
            background: #000
        }

        .compact-table {
            border: 0;
            width: auto
        }

        .compact-table td, .compact-table th {
            width: 100px;
            border: 0;
            text-align: center
        }

        .compact-table tr:hover td {
            background-color: #fff
        }

        .filename {
            max-width: 420px;
            overflow: hidden;
            text-overflow: ellipsis
        }

        .break-word {
            word-wrap: break-word;
            margin-left: 30px
        }

        .break-word.float-left a {
            color: #7d7d7d
        }

        .break-word + .float-right {
            padding-right: 30px;
            position: relative
        }

        .break-word + .float-right > a {
            color: #7d7d7d;
            font-size: 1.2em;
            margin-right: 4px
        }

        #editor, .edit-file-actions {
            position: absolute;
            right: 30px
        }

        #editor {
            top: 50px;
            bottom: 5px;
            left: 30px
        }

        .edit-file-actions {
            top: 0;
            background: #fff;
            margin-top: 10px;
            z-index: 9999;
        }

        .edit-file-actions > a, .edit-file-actions > button {
            background: #fff;
            padding: 5px 15px;
            cursor: pointer;
            color: #296ea3;
            border: 1px solid #296ea3
        }

        .btn-2 {
            border-radius: 0;
            padding: 3px 6px;
            font-size: small;
        }

        div#searchresultWrapper {
            max-height: 320px;
            overflow: auto
        }

        div#searchresultWrapper li {
            margin: 10px 0;
            list-style: none
        }

        li.file:before, li.folder:before {
            font: normal normal normal 14px/1 FontAwesome;
            content: "\f016";
            margin-right: 5px
        }

        li.folder:before {
            content: "\f114"
        }

        i.fa.fa-folder-o {
            color: #e64980;
        }

        i.fa.fa-picture-o {
            color: #e64980
        }

        i.fa.fa-file-archive-o {
            color: #e64980
        }

        .btn-2 i.fa.fa-file-archive-o {
            color: inherit;
        }

        i.fa.fa-css3 {
            color: #f36fa0
        }

        i.fa.fa-file-code-o {
            color: #ec6630
        }

        i.fa.fa-code {
            color: #cc4b4c
        }

        i.fa.fa-file-text-o {
            color: #0096e6
        }

        i.fa.fa-html5 {
            color: #d75e72
        }

        i.fa.fa-file-excel-o {
            color: #09c55d
        }

        i.fa.fa-file-powerpoint-o {
            color: #f6712e
        }

        .main-nav {
            box-shadow: 0 4px 5px 0 rgba(0, 0, 0, .14), 0 1px 10px 0 rgba(0, 0, 0, .12), 0 2px 4px -1px rgba(0, 0, 0, .2)
        }

        .footer-action li {
            margin-bottom: 10px;
        }
        .list-inline-item a{
            border-color: #e64980;
            color: #e64980;
            -webkit-transition: all;
            transition: all;
            transition-timing-function: ease;
            transition-duration: 1s;
        }
        .list-inline-item a:hover {
            color: white !important;
                margin-top: -5px;
                box-shadow: 1px 1px 5px grey;
        }
        .inline-actions > a:hover > i{
            margin-top: -5px;
        }
        .btn-outline-primary:not(:disabled):not(.disabled).active, .btn-outline-primary:not(:disabled):not(.disabled):active, .show>.btn-outline-primary.dropdown-toggle,.btn-outline-primary:not(:disabled):not(.disabled):hover {
            background-color: #e64980;
            border-color: #e64980;
        }
    </style>
</head>
<body>
<div id="wrapper" class="container-fluid">

    <!-- New Item creation -->
    <div class="modal fade" id="createNewItem" tabindex="-1" role="dialog" aria-label="newItemModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newItemModalLabel"><i class="fa fa-plus-square fa-fw"></i>Create New Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><label for="newfile">Item Type </label></p>

                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="customRadioInline1" name="newfile" value="file" class="custom-control-input">
                        <label class="custom-control-label" for="customRadioInline1">File</label>
                    </div>

                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="customRadioInline2" name="newfile" value="folder" class="custom-control-input" checked="">
                        <label class="custom-control-label" for="customRadioInline2">Folder</label>
                    </div>

                    <p class="mt-3"><label for="newfilename">Item Name </label></p>
                    <input type="text" name="newfilename" id="newfilename" value="" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="newfolder('<?php echo fm_enc(FM_PATH) ?>');return false;">Create Now</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Itema -->
    <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModalLabel"><i class="fa fa-search fa-fw"></i>  Search Files and Folders</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="search" class="form-control" name="search" value="" placeholder="Find a item in current folder...">
                    <div id="searchresultWrapper" class="mt-3"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <?php
    }

    /**
     * Show page footer
     */
    function fm_show_footer()
    {
    ?>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script>
    /**
     * Create new Folder/file
     * @param path {String}
     */
    function newfolder(e) {
        var t = document.getElementById("newfilename").value,
            n = document.querySelector('input[name="newfile"]:checked').value;
        null !== t && "" !== t && n && (window.location.hash = "#", window.location.search = "p=" + encodeURIComponent(e) + "&new=" + encodeURIComponent(t) + "&type=" + encodeURIComponent(n))
    }

    function rename(e, t) {
        var n = prompt("New name", t);
        null !== n && "" !== n && n != t && (window.location.search = "p=" + encodeURIComponent(e) + "&ren=" + encodeURIComponent(t) + "&to=" + encodeURIComponent(n))
    }

    function change_checkboxes(e, t) {
        for (var n = e.length - 1; n >= 0; n--) e[n].checked = "boolean" == typeof t ? t : !e[n].checked
    }

    function get_checkboxes() {
        for (var e = document.getElementsByName("file[]"), t = [], n = e.length - 1; n >= 0; n--) (e[n].type = "checkbox") && t.push(e[n]);
        return t
    }

    /* Coded by Ameer Hamza START*/

    let checkBox = get_checkboxes();

    for(let i = 0; i < checkBox.length; i++) {
        checkBox[i].addEventListener('click', unCheckedCheckBox);
    }

    let selectAllCheckBox = document.getElementById('js-select-all-items');

    function unCheckedCheckBox(){ // this function is used to check
        if(this.checked !== true){
                selectAllCheckBox.checked = false;
        }
        if(checkBox.length === 1 && this.checked === true){
            selectAllCheckBox.checked = true;
        }
        let allChecked = true;
        for(let i = 0; i < checkBox.length; i++){
            if(checkBox[i].checked === false){
                allChecked = false;
            }
        }
        if(allChecked === true){
            selectAllCheckBox.checked = true;
        }
    }



    /*CODED BY AMEER HAMZA ----END*/

    function select_all() {
        change_checkboxes(get_checkboxes(), !0)
    }

    function unselect_all() {
        change_checkboxes(get_checkboxes(), !1)
    }

    function invert_all() {
        change_checkboxes(get_checkboxes())
    }

    function mailto(e, t) {
        var n = new XMLHttpRequest,
            a = "path=" + e + "&file=" + t + "&type=mail&ajax=true";
        n.open("POST", "", !0), n.setRequestHeader("Content-type", "application/x-www-form-urlencoded"), n.onreadystatechange = function () {
            4 == n.readyState && 200 == n.status && alert(n.responseText)
        }, n.send(a)
    }

    function showSearch(e) {
        $('#searchModal').modal('show');
        var t = new XMLHttpRequest,
            n = "path=" + e + "&type=search&ajax=true";
        t.open("POST", "", !0), t.setRequestHeader("Content-type", "application/x-www-form-urlencoded"), t.onreadystatechange = function () {
            4 == t.readyState && 200 == t.status && (window.searchObj = t.responseText, document.getElementById("searchresultWrapper").innerHTML = "", window.location.hash = "#searchResult")
        }, t.send(n)
    }

    function getSearchResult(e, t) {
        var n = [],
            a = [];
        return e.forEach(function (e) {
            "folder" === e.type ? (getSearchResult(e.items, t), e.name.toLowerCase().match(t) && n.push(e)) : "file" === e.type && e.name.toLowerCase().match(t) && a.push(e)
        }), {
            folders: n,
            files: a
        }
    }

    function checkbox_toggle() {
        var e = get_checkboxes();
        e.push(this), change_checkboxes(e)
    }

    function backup(e, t) {
        var n = new XMLHttpRequest,
            a = "path=" + e + "&file=" + t + "&type=backup&ajax=true";
        return n.open("POST", "", !0), n.setRequestHeader("Content-type", "application/x-www-form-urlencoded"), n.onreadystatechange = function () {
            4 == n.readyState && 200 == n.status && alert(n.responseText)
        }, n.send(a), !1
    }

    function edit_save(e, t) {
        var n = "ace" == t ? editor.getSession().getValue() : document.getElementById("normal-editor").value;
        if (n) {
            var a = document.createElement("form");
            a.setAttribute("method", "POST"), a.setAttribute("action", "");
            var o = document.createElement("textarea");
            o.setAttribute("type", "textarea"), o.setAttribute("name", "savedata");
            var c = document.createTextNode(n);
            o.appendChild(c), a.appendChild(o), document.body.appendChild(a), a.submit()
        }
    }

    function init_php_file_tree() {
        if (document.getElementsByTagName) {
            for (var e = document.getElementsByTagName("LI"), t = 0; t < e.length; t++) {
                var n = e[t].className;
                if (n.indexOf("pft-directory") > -1)
                    for (var a = e[t].childNodes, o = 0; o < a.length; o++) "A" == a[o].tagName && (a[o].onclick = function () {
                        for (var e = this.nextSibling; ;) {
                            if (null == e) return !1;
                            if ("UL" == e.tagName) {
                                var t = "none" == e.style.display;
                                return e.style.display = t ? "block" : "none", this.className = t ? "open" : "closed", !1
                            }
                            e = e.nextSibling
                        }
                        return !1
                    }, a[o].className = n.indexOf("open") > -1 ? "open" : "closed"), "UL" == a[o].tagName && (a[o].style.display = n.indexOf("open") > -1 ? "block" : "none")
            }
            return !1
        }
    }

    var searchEl = document.querySelector("input[type=search]"),
        timeout = null;
    searchEl.onkeyup = function (e) {
        clearTimeout(timeout);
        var t = JSON.parse(window.searchObj),
            n = document.querySelector("input[type=search]").value;
        timeout = setTimeout(function () {
            if (n.length >= 2) {
                var e = getSearchResult(t, n),
                    a = "",
                    o = "";
                e.folders.forEach(function (e) {
                    a += '<li class="' + e.type + '"><a href="?p=' + e.path + '">' + e.name + "</a></li>"
                }), e.files.forEach(function (e) {
                    o += '<li class="' + e.type + '"><a href="?p=' + e.path + "&view=" + e.name + '">' + e.name + "</a></li>"
                }), document.getElementById("searchresultWrapper").innerHTML = '<div class="model-wrapper">' + a + o + "</div>"
            }
        }, 500)
    }, window.onload = init_php_file_tree;
    if (document.getElementById("file-tree-view")) {
        var tableViewHt = document.getElementById("main-table").offsetHeight - 2;
        document.getElementById("file-tree-view").setAttribute("style", "height:" + tableViewHt + "px")
    }
    ;
</script>
<?php if (isset($_GET['view']) && FM_USE_HIGHLIGHTJS): ?>
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
<?php endif; ?>
<?php if (isset($_GET['edit']) && isset($_GET['env']) && FM_EDIT_FILE): ?>
    <script src="//cdnjs.cloudflare.com/ajax/libs/ace/1.2.9/ace.js"></script>
    <script>var editor = ace.edit("editor");
        editor.getSession().setMode("ace/mode/javascript");</script>
<?php endif; ?>
</body>
</html>
<?php
}

/**
 * Show image
 * @param string $img
 */
function fm_show_image($img)
{
    $modified_time = gmdate('D, d M Y 00:00:00') . ' GMT';
    $expires_time = gmdate('D, d M Y 00:00:00', strtotime('+1 day')) . ' GMT';

    $img = trim($img);
    $images = fm_get_images();
    $image = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAEElEQVR42mL4//8/A0CAAQAI/AL+26JNFgAAAABJRU5ErkJggg==';
    if (isset($images[$img])) {
        $image = $images[$img];
    }
    $image = base64_decode($image);
    if (function_exists('mb_strlen')) {
        $size = mb_strlen($image, '8bit');
    } else {
        $size = strlen($image);
    }

    if (function_exists('header_remove')) {
        header_remove('Cache-Control');
        header_remove('Pragma');
    } else {
        header('Cache-Control:');
        header('Pragma:');
    }

    header('Last-Modified: ' . $modified_time, true, 200);
    header('Expires: ' . $expires_time);
    header('Content-Length: ' . $size);
    header('Content-Type: image/png');
    echo $image;

    exit;
}

/**
 * Get base64-encoded images
 * @return array
 */


?>

<!-- <div id="contextMenu" class="dropdown clearfix">
    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" style="display:block;position:static;margin-bottom:5px;">
      <li><a tabindex="-1" href="#">Action 1</a>
      </li>
      <li><a tabindex="-1" href="#">Action 2</a>
      </li>
      <li><a tabindex="-1" href="#">Some More Actions</a>
      </li>
      <li class="divider"></li>
      <li><a tabindex="-1" href="#">Final Action</a>
      </li>
    </ul>
  </div> -->
  
</div>

<script>
    function toggleSortClass(){
        $('.fa.fa-sort-asc');
    }
function sortTable(n,evt) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("main-table");
  switching = true;
  //Set the sorting direction to ascending:
  dir = "asc"; let e = evt;
  console.log($(e).find('.float-right').toggleClass('fa-sort-asc fa-sort-desc'));
  /*Make a loop that will continue until
  no switching has been done:*/
  while (switching) {
    //start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /*Loop through all table rows (except the
    first, which contains table headers):*/
    for (i = 1; i < (rows.length - 1); i++) {
      //start by saying there should be no switching:
      shouldSwitch = false;
      /*Get the two elements you want to compare,
      one from current row and one from the next:*/
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /*check if the two rows should switch place,
      based on the direction, asc or desc:*/
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch= true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          //if so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /*If a switch has been marked, make the switch
      and mark that a switch has been done:*/
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      //Each time a switch is done, increase this count by 1:
      switchcount ++;      
    } else {
      /*If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again.*/
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}

$('.contextmenu').hide();

$('.table_tr').contextmenu(function(e){
    if(e.preventDefault != undefined)
      e.preventDefault();
     if(e.stopPropagation != undefined)
      e.stopPropagation();
        
        /*get current selected row*/
        let tr = this;
        
        /*select all td columns of selected row*/
        let td = tr.getElementsByTagName('td');
        let td_length = td.length;

        /* select last column of the selected row*/
        let last_td = td[td_length-1];

        /* select all anchor tags of selected row from last column*/
        let anchor_tag = last_td.getElementsByTagName('a');


        /* select contextmenu */
        let ul = $('.contextmenu');
        let li_length = $('.contextmenu li').length;        

        /* select all li tags of context menu*/
        let li = ul[0].getElementsByTagName('li');

  /* this code get delete file uri from actions column and set it in to contextmenu anchor tag*/
        let deleteFileURI = anchor_tag[0].getAttribute('href');
        //console.log(deleteFileURI);

  /* this code get delete file uri from actions column and set it in to contextmenu anchor tag*/
        
        /* select delete button of context menu*/
        let ul_li_anchor_first = li[0].getElementsByTagName('a');

        /* put the values of of delete file uri to context menu first anchor tag*/
        let delteBTN = ul_li_anchor_first[0].setAttribute('href',deleteFileURI);

        /* this code get delete file uri from actions column and set it in to contextmenu anchor tag*/
        let editFileURI = anchor_tag[1].getAttribute('onclick');
       // console.log(editFileURI);

  /* this code get delete file uri from actions column and set it in to contextmenu anchor tag*/
        
        /* select delete button of context menu*/
        let ul_li_anchor_second = li[1].getElementsByTagName('a');

        /* put the values of of delete file uri to context menu first anchor tag*/
        ul_li_anchor_second[0].setAttribute('onclick',editFileURI);

         /* this code get delete file uri from actions column and set it in to contextmenu anchor tag*/
        let copyFileURI = anchor_tag[2].getAttribute('href');
        //console.log(copyFileURI);
  /* this code get delete file uri from actions column and set it in to contextmenu anchor tag*/
        
        /* select delete button of context menu*/
        let ul_li_anchor_third = li[2].getElementsByTagName('a');

        /* put the values of of delete file uri to context menu first anchor tag*/
        let copyBTN = ul_li_anchor_third[0].setAttribute('href',copyFileURI);

         /* this code get delete file uri from actions column and set it in to contextmenu anchor tag*/
        let directLinkFileURI = anchor_tag[3].getAttribute('href');
        //console.log(directLinkFileURI);

        /* this code get delete file uri from actions column and set it in to contextmenu anchor tag*/
        
        /* select delete button of context menu*/
        let ul_li_anchor_fourth = li[3].getElementsByTagName('a');

        /* put the values of of delete file uri to context menu first anchor tag*/
         ul_li_anchor_fourth[0].setAttribute('href',directLinkFileURI);

        // console.log(dirctLinkBTN);
        if(anchor_tag.length == 4){
            if( li_length == 5){
                $('.contextmenu')[0].style.width = '140px';
                $('.contextmenu li').last().remove('li');
            }
        }else
        if(anchor_tag.length == 5){
            if( li_length == 4){
        
                 /* this code get delete file uri from actions column and set it in to contextmenu anchor tag*/
                let downloadFileURI = anchor_tag[4].getAttribute('href');

                /* this code get delete file uri from actions column and set it in to contextmenu anchor tag*/
                
                $('.contextmenu')[0].style.width = '160px';
                $('.contextmenu').append("<li><a class='dropdown-item' href='"+downloadFileURI+"' title='Download'><i class='fa fa-download'></i> Download</a></li>");

                /* select delete button of context menu*/
                //let ul_li_anchor_fifth = li[4].getElementsByTagName('a');

                /* put the values of of delete file uri to context menu first anchor tag*/
                //console.log(downloadFileURI);
                //let dwonloadBTN = ul_li_anchor_fifth[0].setAttribute('href',downloadFileURI);
            }
        }
        
        if(anchor_tag.length === 5 && li_length === 5 ){
                let downloadFileURI = anchor_tag[4].getAttribute('href');
                //console.log(downloadFileURI);

                let last_li = $('.contextmenu li').last();
                lastAnchor = last_li[0];
                //console.log(lastAnchor.getElementsByTagName('a')[0].setAttribute('href',downloadFileURI));
        }



        $('.contextmenu').show(2000);
        $('.contextmenu').offset({left: e.pageX,top : e.pageY});
});
$('body').click(function(){
    $('.contextmenu').hide();
});

</script>
<ul class="contextmenu dropdown-menu" style="display: none; margin:0;padding: 0;position:absolute;">
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="fa fa-trash-o" aria-hidden="true" onclick="return confirm('Delete folder');"></i>
                            Delete
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                            Rename
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#"  target="_blank"><i class="fa fa-files-o" aria-hidden="true"></i>
                        Copy
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="" target="_blank">
                    <i class="fa fa-link" aria-hidden="true"></i>
                        Direct Link
                        </a>
                    </li>
                </ul>
                <script type="text/javascript">
                    $('#main-table input[type=checkbox]').on('click',function(){
    if($('#main-table input[type=checkbox]:checked').length == 1){
        let parents = $('#main-table input[type=checkbox]:checked').parents();
        //parents[2].getElementsByClassName('filename');

        // parents[2].getElementsByClassName('filename')[0].getElementsByTagName('a')[0];

        // return <a href"...."></a>


        let anchor = parents[2].getElementsByClassName('filename')[0].getElementsByTagName('a')[0];
        // anchor.hasAttribute('href');
        // if have return 'true' otherwise 'false'

        // anchor.getAttribute('href');
        // return the value of href

        let anchor_v = anchor.getAttribute('href').split("=");
        // return array
        let len = anchor_v.length;

        // console.log(anchor_v[len-1]);

        let zip_file_name = anchor_v[len-1];
        let check_name = zip_file_name.split('.');
        let check_name_len = check_name.length;

        if(check_name[check_name_len-1] === "zip" || check_name[check_name_len-1] === "rar"){
            //console.log(zip_file_name);
            $('#unzip_file').attr('href', window.location.href+'&unzip='+anchor_v[len-1]+'');
        }
        else{
            $('#unzip_file').attr('href','');
        }
        // return name of the file..
    }
    else{
        $('#unzip_file').attr('href','');
    }
});
                </script>
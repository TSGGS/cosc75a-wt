<?php

    function changeTitle(string $path, string $title) {
        // Output Buffer to replace title of every page
        ob_start();
        require($path);
        $buffer=ob_get_contents();
        ob_end_clean();

        $buffer=str_replace("%TITLE%", $title, $buffer);
        echo $buffer;
    }

    function validatePassword(string $password) {
        $uppercase = preg_match("@[A-Z]@", $password);
        $lowercase = preg_match("@[a-z]@", $password);
        $characase = preg_match("@[^\W]@", $password);
        $numbecase = preg_match("@[0-9]@", $password);

        if(!$uppercase || !$lowercase || !$numbecase || !$characase || strlen($password) < 8) {
            return false;
        }

        return true;
    }

    function validateUserPage(int $tid, string $path) {
        if($tid == 1 && strpos($path, "admin") === false) {
            header("Location: ../admin/dashboard.php");
            die();
        }elseif($tid == 2 && strpos($path, "operation") === false) {
            header("Location: ../operation/dashboard.php");
            die();
        }elseif($tid == 3 && strpos($path, "marketing") === false) {
            header("Location: ../marketing/dashboard.php");
            die();
        }elseif($tid == 4 && strpos($path, "support") === false) {
            header("Location: ../support/dashboard.php");
            die();
        }
    }

    function isImage($imgExt) {
        $ext = array("gif", "png", "jpg", "jpeg");
        
        return in_array(strtolower($imgExt), $ext);
    }

    function isEmpty($element, string $element_id) {
        if(!isset($element) || empty($element)) {
            echo '
                <script>
                    toggleError("'.$element_id.'-error", "show");
                </script>
            ';

            return true;
        } else {
            echo '
                <script>
                    toggleError("'.$element_id.'-error", "hide");
                </script>
            ';

            return false;
        }
    }

    function validateDateRange($start, $end,  $type = "new") {
        $today = strtotime(date("Y-m-d H:i:s"));

        if($type == "new") {
            if($start < $today || $end < $today || $end < $start) {
                if($end < $start || $start < $today) {
                    return -3;
                } elseif($end < $today || $end < $start) {
                    return -2;
                } else {
                    return -1;
                }
            } else {
                return 1;
            }
        } else {
            if($end <= $start) {
                return -2;
            } else {
                return 1;
            }
        }
    }

    function validateName($name) {
        if(!preg_match("/^[^-\s][a-zA-Z .'-]+$/", $name)) {
            return false;
        }

        return true;
    }

    function validateAddress($addr) {
        if(!preg_match("/^[^-\s][a-zA-Z0-9 ,.'-]+$/", $addr)) {
            return false;
        }

        return true;
    }

    function validateMobile($mobile) {
        if(!preg_match("/^\+?\d{12}$/", $mobile)) {
            return false;
        }

        return true;
    }

    function validateEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    function validateCustDate($date, $time) {
        $cur = strtotime(date("Y-m-d H:i"));
        $custDate = strtotime(date("Y-m-d H:i", strtotime("$date $time")));

        if($custDate < $cur) {
            return false;
        }

        return true;
    }


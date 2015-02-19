<?php

/**
 * Created by PhpStorm.
 * User: Asmoria-Y
 * Date: 20.01.2015
 * Time: 21:20
 */
class Profiler
{
    static $_instance;

    private function __Construct()
    {

    }

    private function __Clone()
    {

    }

    public function test()
    {
        return 6;
    }

    public function actionRegister()
    {
        $result = "";
        if (!empty($_POST['mail']) && !empty($_POST['pass'])) {
            $email = $_POST['mail'];
            $pass = $_POST['pass'];

            $passlen = strlen($pass);
            $emaillen = strlen($email);
            $max = 255;
            $minpass = 2;
            $minemail = 3;

            if($passlen < $minpass){
                $errors[] = "pass must be at least 2 characters";
            } elseif($passlen > $max){
                $errors[] = "pass must be less than 255 characters";
            }

            if($emaillen < $minemail){
                $errors[] = "email must be at least 3 characters";
            } elseif($emaillen > $max){
                $errors[] = "email must be less than 255 characters";
            }

            if(empty($pass)){
                $errors[] = "pass is required";
            }

            if(empty($email)){
                $errors[] = "email cannot be left empty";
            }

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                $errors[] = "invalid email";
            }
            if (!empty($errors)) {
                echo "<ul>";
                foreach ($errors as $error) {
                    echo "<li>$error</li>";
                }
                echo "</ul>";
                exit;
            }
            $db = Configuration::getInstance();
            $mail = $_POST['mail'];
            $pass = $_POST['pass'];
            $sql_ = $db->connection->prepare("INSERT INTO `profile` (`mail`, `pass`)VALUES(:mail, :pass)");
            $sql_->bindValue(':mail', $mail, PDO::PARAM_STR);
            $sql_->bindValue(':pass', $pass, PDO::PARAM_STR);

            $result = $sql_->execute();
        }

        if ($result) {
            echo "Success";
            exit;
        } else die('Error');

    }

    public function actionAuth()
    {
        if (!empty($_SESSION['u_mail']) && !empty($_SESSION['u_id'])) {
//            echo "Hi" . $_SESSION['u_mail'];

//        $form = <<<EOL
//Hi  <h1> {$_SESSION['u_mail']} </h1>;
//EOL;
            exit;
        }
        $result = "";
        if (!empty($_POST['a_email']) && !empty($_POST['a_pass'])) {
            $db = Configuration::getInstance();
            $mail = $_POST['a_email'];
            $pass = $_POST['a_pass'];
            $sql_ = $db->connection->prepare("SELECT * FROM `profile` WHERE `mail` = :mail AND `pass` = :pass");
            $sql_->bindValue(':mail', $mail, PDO::PARAM_STR);
            $sql_->bindValue(':pass', $pass, PDO::PARAM_STR);
            $result = $sql_->execute();
            $check = $sql_->fetch(PDO::FETCH_ASSOC);
            if ($check['id'] && empty($_SESSION['u_id'])) {
                $_SESSION['u_id'] = $check['id'];
                $_SESSION['u_mail'] = $check['mail'];
            }
        }
    }

    public function actionLogout(){
        session_destroy();
        header('Location: http://asmoria');
    }

    public function actionCabinet(){

    }

    public function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}

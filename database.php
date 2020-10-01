<?php
class DatabaseManager
{
    var $link;

    function __construct()
    {
        //print "In Component constructor.\n";
        $secret = file('secret');
        // Create connection
        $this->link = mysqli_connect(trim($secret[0]), trim($secret[1]), trim($secret[2]), trim($secret[3])) or die(mysqli_connect_error());
    }
    //Retrieves all the user information which is later used to find the role of user using the Session userID.
    function getRole($userID)
    {
        if ($this->link) {
            //Gets all the user details
            $sql_select_query = "SELECT * FROM user where userID = $userID";
            $sql_select_result = mysqli_query($this->link, $sql_select_query) or die('query failed' . mysqli_error($this->link));

            if (mysqli_num_rows($sql_select_result) > 0) {
                return $sql_select_result;
            }
        }
    }
    //Retrieves all quiz results of a user by selecting all that have the flag value 0 and match the session userID.
    function getResults($userID)
    {
        if ($this->link) {
            $sql_select_query = "SELECT * FROM quizresult where flag = 0 AND userID = $userID";
            $sql_select_result = mysqli_query($this->link, $sql_select_query) or die('query failed' . mysqli_error($this->link));

            if (mysqli_num_rows($sql_select_result) > 0) {
                return $sql_select_result;
            }
        }
    }
    //Retrieves all results that have been done with flag value of 0.
    function getAllResults()
    {
        if ($this->link) {
            $sql_select_query = "SELECT * FROM quizresult where flag = 0";
            $sql_select_result = mysqli_query($this->link, $sql_select_query) or die('query failed' . mysqli_error($this->link));

            if (mysqli_num_rows($sql_select_result) > 0) {
                return $sql_select_result;
            }
        }
    }
    //Creates a quiz through the use of user input
    function createQuiz($name)
    {
        if ($this->link) {
            //Declaring flag value of 0, 0 indicated that the value is allowed to be visible to the user.
            $flagValue = 0;
            //Escaping values that were inputted by the user.
            $name1 = htmlentities($name);
            $name2 = mysqli_real_escape_string($this->link, $name1);

            //Preparing and binding values.
            $stmt = $this->link->prepare("INSERT INTO quiz (quizName, flag) VALUES (?, ?)");
            $stmt->bind_param("si", $finalName, $flagValue);

            //Setting parameters and execute.
            $finalName = $name2;
            $stmt->execute();

            if ($stmt) {
                //After statment has been executed the user will be redirected to the index page.
                header('Location: index.php');
            }
        }
    }
    //Create's a question using the input of the user.
    function createQuestions($question)
    {
        if ($this->link) {
            //Flag value of 0 represents that the question is visible.
            $flagValue = 0;
            $question1 = htmlentities($question);
            $question2 = mysqli_real_escape_string($this->link, $question1);

            //Using insert statement to create a record of a new question
            $stmt = $this->link->prepare("INSERT INTO question (question, flag) VALUES (?, ?)");
            $stmt->bind_param("si", $finalQuestion, $flagValue);

            // set parameters and execute.
            $finalQuestion = $question2;
            $stmt->execute();

            if ($stmt) {
                //After the statment is successfully executed the user will be redirected to the questionbank.php.
                header('Location: questionBank.php');
            }
        }
    }
    //Function creates a grade using the grade inputted by a teacher role, a quizID of the completed quiz, and user ID of the user who has completed the quiz.
    function grade($grade, $quizID, $userID)
    {
        if ($this->link) {
            $flagValue = 0;
            $grade1 = htmlentities($grade);
            $grade2 = mysqli_real_escape_string($this->link, $grade1);

            //Using insert statement to create a record of a grade
            $stmt = $this->link->prepare("INSERT INTO quizresult (grade, quizID, userID, flag) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiii", $finalGrade, $quizID, $userID, $flagValue);

            // set parameters and execute
            $finalGrade = $grade2;
            $stmt->execute();

            if ($stmt) {
                header('Location: gradeQuizzes.php');  
            }
        }
    }

    //Function allows for updating of a grade record
    function updateGrade($grade, $quizID, $userID)
    {
        if ($this->link) {
            $flagValue = 0;
            $grade1 = htmlentities($grade);
            $grade2 = mysqli_real_escape_string($this->link, $grade1);


            //Using an update statment to edit/update a record of a grade that was previously made.
            $stmt = $this->link->prepare('UPDATE quizresult SET grade=? WHERE quizID=? AND userID=? AND flag=?');
            $stmt->bind_param("iiii", $finalGrade, $quizID, $userID, $flagValue);

            //seting parameters and execute.
            $finalGrade = $grade2;
            $stmt->execute();

            if ($stmt) {
                header('Location: gradeQuizzes.php');
            }
        }
    }

    //Function allows users to assign questions to quiz that they have selected
    function assignQuestions($questions, $id)
    {
        if ($this->link) {
            $flagValue = 0;

            $length = count($questions);
            for ($i = 0; $i < $length; $i++) {
                echo $questions[$i];

                $questions1 = htmlentities($questions[$i]);
                $questions2 = mysqli_real_escape_string($this->link, $questions1);

                // prepare and bind
                $stmt = $this->link->prepare("INSERT INTO question (question, quizID, flag) VALUES (?, ?, ?)");
                $stmt->bind_param("sii", $finalQuestion, $id, $flagValue);

                // set parameters and execute
                $finalQuestion = $questions2;

                $stmt->execute();
                if ($stmt) {
                    header('Location: index.php');
                }
            }
        }
    }

    //Gets the total questions.
    function totalQuestions()
    {
        if ($this->link) {
            $sql_select_query = "SELECT COUNT(*) FROM quiz";
            $sql_select_result = mysqli_query($this->link, $sql_select_query) or die('query failed' . mysqli_error($this->link));

            if (mysqli_num_rows($sql_select_result) > 0) {
                $totalRows = mysqli_fetch_assoc($sql_select_result);
                return $totalRows;
            }
        }
    }

    //Gets all the row of quizzes that are allowed to be visible to the user.
    function getAllQuiz()
    {
        if ($this->link) {
            //Selecting all quizzes with the flag value of 0/
            $sql_select_query = "SELECT * FROM quiz where flag = 0";
            // $sql_select_query = 'SELECT * FROM `quiz` ORDER BY Developer';
            $sql_select_result = mysqli_query($this->link, $sql_select_query) or die('query failed' . mysqli_error($this->link));

            if (mysqli_num_rows($sql_select_result) > 0) {
                return $sql_select_result;
            }
        }
    }
    //Function retrieves all questions 
    function getAllQuestions()
    {
        if ($this->link) {
            //Selects all questions that have the flag value of 0
            $sql_select_query = "SELECT * FROM question where flag = 0";
            $sql_select_result = mysqli_query($this->link, $sql_select_query) or die('query failed' . mysqli_error($this->link));

            if (mysqli_num_rows($sql_select_result) > 0) {
                return $sql_select_result;
            }
        }
    }
    //Retrieves all quiz answers that have been completed by a certain user.
    function getAllQuizAnswers($quizID, $userID)
    {
        if ($this->link) {
            $flagValue = 0;
            //Selects all answers from a certain quiz that has been completed by a certain user and has a flag value of 0.
            $sql_select_query = "SELECT * FROM answer where quizID = $quizID AND userID = $userID AND flag = $flagValue";
            $sql_select_result = mysqli_query($this->link, $sql_select_query) or die('query failed' . mysqli_error($this->link));

            if (mysqli_num_rows($sql_select_result) > 0) {
                return $sql_select_result;
            }
        }
    }
    //Function retrieves all questions that have not already been assigned.
    function getAllQuestionsAssigning()
    {
        if ($this->link) {
            $sql_select_query = "SELECT * FROM question where quizID IS NULL AND flag = 0";
            $sql_select_result = mysqli_query($this->link, $sql_select_query) or die('query failed' . mysqli_error($this->link));

            if (mysqli_num_rows($sql_select_result) > 0) {
                return $sql_select_result;
            }
        }
    }
    //Function retieves all questions that were assigned to a quiz.
    function getQuizFinal($id)
    {
        if ($this->link) {
            $sql_select_query = "SELECT * FROM question where quizID = $id AND flag = 0";
            $sql_select_result = mysqli_query($this->link, $sql_select_query) or die('query failed' . mysqli_error($this->link));

            if (mysqli_num_rows($sql_select_result) > 0) {
                return $sql_select_result;
            }
        }
    }
    //Function allows user's to edit the quiz name.
    function editQuiz($id, $nameP)
    {
        if ($this->link) {
            $name = htmlentities($nameP);
            $name2 = mysqli_real_escape_string($this->link, $name);


            //Statment update's the quiz name with user input
            $stmt = $this->link->prepare('UPDATE quiz SET quizName=? WHERE quizID=?');
            $stmt->bind_param("si", $finalName, $id);

            //Set parameters and execute
            $finalName = $name2;
            $stmt->execute();

            if ($stmt) {
                header('Location: index.php');
            }
        }
    }
    //Function allows user's to edit the question.
    function editQuestion($id, $question)
    {
        if ($this->link) {
            $question2 = htmlentities($question);
            $question3 = mysqli_real_escape_string($this->link, $question2);


            //Statment updates the question with user input of the question
            $stmt = $this->link->prepare('UPDATE question SET question=? WHERE questionID=?');
            $stmt->bind_param("si", $finalQuestion, $id);

            //Set parameters and execute
            $finalQuestion = $question3;
            $stmt->execute();

            if ($stmt) {
                header('Location: questionBank.php');
            }
        }
    }
    //Function get's one quiz.
    function getOne($id)
    {
        if ($id > 0) {
            if ($this->link) {
                $sql_select_query = 'SELECT*FROM quiz WHERE quizID="' . $id . '"';
                $sql_select_result = mysqli_query($this->link, $sql_select_query) or die('query failed' . mysqli_error($this->link));

                if (mysqli_num_rows($sql_select_result) > 0) {
                    return $sql_select_result;
                }
            }
        }
    }
    //Function get's one question from a quiz
    function getOneQuestion($id)
    {
        if ($id > 0) {
            if ($this->link) {
                $sql_select_query = 'SELECT*FROM question WHERE questionID="' . $id . '"';
                $sql_select_result = mysqli_query($this->link, $sql_select_query) or die('query failed' . mysqli_error($this->link));

                if (mysqli_num_rows($sql_select_result) > 0) {
                    return $sql_select_result;
                }
            }
        }
    }
    //Retrieves all questions assigned to a quiz.
    function getAllQuizQuestions($id)
    {
        if ($this->link) {
            $flagValue = 0; 
            //Select's all questions that belong to the quiz with a flag value of 0.
            $sql_select_query = 'SELECT*FROM question WHERE quizID="' . $id . '" AND flag="' . $flagValue . '" ';
            $sql_select_result = mysqli_query($this->link, $sql_select_query) or die('query failed' . mysqli_error($this->link));

            if (mysqli_num_rows($sql_select_result) > 0) {
                return $sql_select_result;
            }
        }
    }
    //Retrieves the records of all completed quizzes.
    function getAllCompleted()
    {
        if ($this->link) {
            $flagValue = 0; 
            $sql_select_query = 'SELECT*FROM quizcompleted WHERE flag="' . $flagValue . '"';
            $sql_select_result = mysqli_query($this->link, $sql_select_query) or die('query failed' . mysqli_error($this->link));

            if (mysqli_num_rows($sql_select_result) > 0) {
                return $sql_select_result;
            }
        }
    }
    //Function returns row of a record showing whether the quiz was completed or not.
    function completedQuizCheck($quizID, $userID)
    {
        if ($this->link) {
            $flagValue = 0; 
            $sql_select_query = "SELECT * FROM quizcompleted where quizID= $quizID AND userID = $userID AND flag = $flagValue";
            $sql_select_result = mysqli_query($this->link, $sql_select_query) or die('query failed' . mysqli_error($this->link));

            if (mysqli_num_rows($sql_select_result) > 0) {
                return $sql_select_result;
            }
        }
    }
    //Retrieves record of the all User's grades.
    function resultCheck($quizID, $userID)
    {
        if ($this->link) {
            $flagValue = 0; 
            $sql_select_query = "SELECT * FROM quizresult where quizID= $quizID AND userID = $userID AND flag = $flagValue";
            $sql_select_result = mysqli_query($this->link, $sql_select_query) or die('query failed' . mysqli_error($this->link));

            if (mysqli_num_rows($sql_select_result) > 0) {
                return $sql_select_result;
            }
        }
    }
    //The functions sets the flag value of a quiz to 1 to indicate that it is not visible and has been deleted.
    function deleteQuiz($id)
    {
        $flagValue = 1;
        if ($this->link) {
            $stmt = $this->link->prepare('UPDATE quiz SET flag=? WHERE quizID=?');
            $stmt->bind_param("ii", $flagValue, $id);
            $stmt->execute();
        }
        if ($stmt) {
            header('Location: index.php');
        }
    }
    //The functions sets the flag value of a question to 1 to indicate that it is not visible and has been deleted.
    function deleteQuestion($id)
    {
        $flagValue = 1;
        if ($this->link) {
            $stmt = $this->link->prepare('UPDATE question SET flag=? WHERE questionID=?');
            $stmt->bind_param("ii", $flagValue, $id);
            $stmt->execute();
        }
        if ($stmt) {
            header('Location: questionBank.php');
        }
    }

    //The functions sets the flag value of answers to 1 to indicate that it is not visible and has been deleted.
    function deleteAnswers($quizID,$userID)
    {
        $flagValue = 1;
        if ($this->link) {
            $stmt = $this->link->prepare('UPDATE answer SET flag=? WHERE quizID=? AND userID=?');
            $stmt->bind_param("iii", $flagValue, $quizID, $userID);
            $stmt->execute();
        }
    }
    //The functions sets the flag value of a completed quiz to 1 to indicate that it is not visible and has been deleted.
    function deleteQuizCompleted($quizID,$userID)
    {
        $flagValue = 1;
        if ($this->link) {
            $stmt = $this->link->prepare('UPDATE quizcompleted SET flag=? WHERE quizID=? AND userID=?');
            $stmt->bind_param("iii", $flagValue, $quizID, $userID);
            $stmt->execute();
        }
    }
    //The functions sets the flag value of a quiz result to 1 to indicate that it is not visible and has been deleted.
    function deleteQuizResult($quizID,$userID)
    {
        $flagValue = 1;
        if ($this->link) {
            $stmt = $this->link->prepare('UPDATE quizresult SET flag=? WHERE quizID=? AND userID=?');
            $stmt->bind_param("iii", $flagValue, $quizID, $userID);
            $stmt->execute();
        }
        if ($stmt) {
            header('Location: gradeQuizzes.php');
        }
    }

    //Functions registers a user through user Input such as email and password.
    function register($email, $password, $role)
    {
        if ($this->link) {
            $output1 = htmlentities($email);
            $uName = mysqli_real_escape_string($this->link, $output1);

            $output2 = htmlentities($password);
            $pass = mysqli_real_escape_string($this->link, $output2);

            $output3 = htmlentities($role);
            $rRole = mysqli_real_escape_string($this->link, $output3);

            // prepare and bind
            $stmt = $this->link->prepare("INSERT INTO user (email, password, role) VALUES(?, ?, ?)");
            $stmt->bind_param("sss", $finalUserName, $finalPassword, $finalRole);

            // set parameters and execute
            $finalUserName = $uName;
            //Password is hashed
            $finalPassword = password_hash($pass, PASSWORD_DEFAULT);
            $finalRole = $rRole;
            $stmt->execute();

            if ($stmt) {
                header('Location: login.php');
            }
        }
    }
    //Function records all the answers the user has answered for each question of the quiz that they are taking.
    function recordAnswers($answers, $questions, $quizID, $userID)
    {
        $flagValue = 0; 
        if ($this->link) {
            $length = count($answers);
            for ($i = 0; $i < $length; $i++) {

                $answers1 = htmlentities($answers[$i]);
                $answers2 = mysqli_real_escape_string($this->link, $answers1);

                // prepare and bind
                $stmt = $this->link->prepare("INSERT INTO answer (answer, questionID, quizID, userID, flag) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("siiii", $finalAnswers, $finalQuestionID, $finalQuizID, $finalUserID, $flagValue);

                // set parameters and execute
                $finalAnswers = $answers2;
                $finalQuestionID = $questions[$i];
                $finalQuizID = $quizID;
                $finalUserID = $userID;

                $stmt->execute();
            }
            if ($stmt) {
            }
        }
    }
    //Function creates a record that a user has completed a certain quiz.
    function quizCompleted($userID, $quizID)
    {
        if ($this->link) {
            $flagValue = 0; 

            // prepare and bind
            $stmt = $this->link->prepare("INSERT INTO quizcompleted (userID, quizID, flag) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $finalUserID, $finalQuizID, $flagValue);

            // set parameters and execute
            $finalQuizID = $quizID;
            $finalUserID = $userID;

            $stmt->execute();
        }
        if ($stmt) {
            header('Location: index.php');
        } else {
            echo "Not successful";
        }
    }

    //Function allows user's to login, the function finds if the email exists and whether the hash password matched before allowing access.
    function login($email, $password)
    {
        if ($this->link) {
            $email1 = htmlentities($email);
            $email2 = mysqli_real_escape_string($this->link, $email1);

            $password1 = htmlentities($password);
            $password2 = mysqli_real_escape_string($this->link, $password1);

            $sql_select_query = 'SELECT * FROM user WHERE email ="' . $email2 . '"';
            $sql_select_result = mysqli_query($this->link, $sql_select_query) or die('query failed' . mysqli_error($this->link));

            if (mysqli_num_rows($sql_select_result) > 0) {
                while ($row = mysqli_fetch_assoc($sql_select_result)) {
                    if (password_verify($password2, $row['password'])) {
                        $_SESSION['userID'] = $row['userID'];
                        header('Location: index.php');
                    }
                }
            }
        }
    }

    function __destruct()
    {
        //print "In Component destructor.\n";
        if ($this->link) {
            mysqli_close($this->link);
        }
    }
}

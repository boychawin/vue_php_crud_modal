<?php

$conn = new PDO("mysql:host=localhost;dbname=vuecrud", "root", "");

$received_data = json_decode(file_get_contents("php://input"));
$data = array();

if ($received_data->action == "fetchall") {
    $query = "SELECT * FROM users";
    $statement = $conn->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }

    echo json_encode($data);
}


if ($received_data->action == "Insert") {
    $data = array(
        ':fname' => $received_data->fname,
        ':lname' => $received_data->lname,
        ':email' => $received_data->email,
    );

    $query = "INSERT INTO users (fname,lname,email) VALUES 
    (:fname, :lname, :email)";

    $statement = $conn->prepare($query);
    $statement->execute($data);
    $output = array('message' => 'Data Inserted');

    echo json_encode($output);
}

if ($received_data->action == "fetchSingle") {

    $statement = $conn->prepare("SELECT *  FROM users WHERE id = :id ");
    $statement->execute([":id" => $received_data->id]);
    $result = $statement->fetchAll();

    foreach ($result as $row) {
        $data['id'] = $row['id'];
        $data['fname'] = $row['fname'];
        $data['lname'] = $row['lname'];
        $data['email'] = $row['email'];
    }


    // $data = array('message' => 'Data');

    echo json_encode($data);
}




if ($received_data->action == "Update") {
    $data = array(
        ':fname' => $received_data->fname,
        ':lname' => $received_data->lname,
        ':email' => $received_data->email,
        ':id' => $received_data->hiddenId,
    );

    $query = "UPDATE users SET fname=:fname,lname=:lname,email=:email WHERE id=:id";

    $statement = $conn->prepare($query);
    $statement->execute($data);
    $output = array('message' => 'Update Inserted');

    echo json_encode($output);
}


if ($received_data->action == "delete") {
    $data = array(
        ':id' => $received_data->hiddenId,
    );

    $query = "DELETE FROM users  WHERE id=:id";

    $statement = $conn->prepare($query);
    $statement->execute($data);
    $output = array('message' => 'Delete Inserted');

    echo json_encode($output);
}


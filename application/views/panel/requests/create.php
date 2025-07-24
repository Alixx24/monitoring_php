<?php require_once __DIR__ . '/../layout/header.php';

session_start();


if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
?>


<body>
    <div>
        <a class="btn btn-success m-3" href="<?php echo "requests/create"; ?>">Create</a>
    </div>
    <table class="table table-striped m-3 ms-5">
        <form class="col-6 ms-5" action="<?php echo "/request/request/store"; ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

            <div class="form-group col-6 ms-5">
                <label for="exampleInputEmail1">Url address</label>
                <input type="text" name="url" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                <small id="emailHelp" class="form-text text-muted"></small>
            </div>
            
             <div class="form-group col-6 ms-5">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
           <div class="form-group col-6 ms-5">
                <label for="exampleInputEmail1">Request name</label>
                <input type="name" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                <small id="emailHelp" class="form-text text-muted"></small>
            </div>
              <div class="form-group col-6 ms-5">
                <label for="exampleInputEmail1">Time</label>
                <input type="number" name="time" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                <small id="emailHelp" class="form-text text-muted"></small>
            </div>
            <button type="submit" class="btn btn-primary col-6 ms-5 mt-2">Submit</button>
        </form>
    </table>
</body>
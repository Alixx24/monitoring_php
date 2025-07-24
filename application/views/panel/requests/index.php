<?php require_once __DIR__ . '/../layout/header.php'; ?>

<body>
<?php
session_start();

if (!empty($_SESSION['success'])) {
    echo "<div style='color:green'>{$_SESSION['success']}</div>";
    unset($_SESSION['success']);
}

if (!empty($_SESSION['warning'])) {
    echo "<div style='color:orange'>{$_SESSION['warning']}</div>";
    unset($_SESSION['warning']);
}

if (!empty($_SESSION['error'])) {
    echo "<div style='color:red'>{$_SESSION['error']}</div>";
    unset($_SESSION['error']);
}
?>

<div>
  <a class="btn btn-success m-3" href="<?php echo "request/create"; ?>">Create</a>
</div>
  <table class="table table-striped ">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">id</th>
        <th scope="col">url</th>
        <th scope="col">name</th>
                <th scope="col">email</th>
                 <th scope="col">time(ms)</th>
        <th scope="col">created_at</th>
   

      </tr>
    </thead>
    <tbody>
      <?php foreach ($requests as $request) { ?>
        <tr>
          <th scope="row">1</th>
          <td><?= $request['id']; ?></td>
          <td><?= $request['url']; ?></td>
          <td><?= $request['name']; ?></td>
          <td><?= $request['email']; ?></td>
            <td><?= ($request['time']) ? $request['time'] : '-' ?></td>
          <td><?= $request['created_at']; ?></td>
        </tr>

      <?php } ?>
    </tbody>
  </table>
</body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="<?= base_url("admin"); ?>">Law Calendar</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor03" aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <?php
    if ($this->session->userdata('admin_active_website') == 'jury_forms') {
      $switch_website = "set_deadlines";
    } else {
      $switch_website = "jury_forms";
    }
  ?>
  <div class="collapse navbar-collapse" id="navbarColor03">
    <ul class="navbar-nav mr-auto">
     <!--  <li class="nav-item">
        <?= anchor('cases','Cases',array('class' => 'nav-link active'));?>
      </li> -->
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url("admin/set_deadlines"); ?>">Rules</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url("admin/set_deadlines/users"); ?>">Users</a>
      </li>
      <!--
      <li class="nav-item">
        <?= anchor('users','Users',array('class' => 'nav-link'));?>
      </li>
    -->
      <li class="nav-item">
        <!--a class="nav-link" href="#">About</a-->
      </li>
      </ul>
      <form class="form-inline my-2 my-lg-0">
        <a href="<?= base_url("select_website/set_deadlines"); ?>"><button class="btn btn-secondary my-2 my-sm-0" type="button">User Dashboard</button></a>
      </form>
      <form class="form-inline my-2 my-lg-0">
        <a href="<?= base_url("admin/select_website/".$switch_website); ?>"><button class="btn btn-secondary my-2 my-sm-0" type="button">Switch Website</button></a>
      </form>
      <form class="form-inline my-2 my-lg-0">
        <a href="<?= base_url("admin/set_deadlines/settings"); ?>"><button class="btn btn-secondary my-2 my-sm-0" type="button">Settings</button></a>
      </form>
      <form class="form-inline my-2 my-lg-0">
        <a href="<?= base_url("admin/logout"); ?>"><button class="btn btn-secondary my-2 my-sm-0" type="button">Logout</button></a>
      </form>
  </div>
</nav>

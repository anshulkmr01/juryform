
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="<?= base_url("set_deadlines"); ?>">Setdeadlines</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor03" aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
<?php
  if ($this->session->userdata('active_website') == 'jury_forms') {
    $switch_website = "set_deadlines";
  } else {
    $switch_website = "jury_forms";
  }
?>
  <div class="collapse navbar-collapse" id="navbarColor03">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <?= anchor('set_deadlines/user_cases','My Cases',array('class' => 'nav-link'));?>
      </li>
      <li class="nav-item">
       <?= anchor('set_deadlines/see_all_events','See all Events',array('class' => 'nav-link'));?>
      </li>
      <li class="nav-item">
       <?= anchor('set_deadlines/user_rules','Setup My Rules',array('class' => 'nav-link'));?>
      </li>
      <li class="nav-item">
        <?= anchor('set_deadlines/import_rules','Import Rules',array('class' => 'nav-link'));?>
      </li>
      <li class="nav-item">
        <!--a class="nav-link" href="#">About</a-->
      </li>
      </ul>
      <form class="form-inline my-2 my-lg-0">
        <?php if($this->session->userdata('adminId')){?>
        <a href="<?= base_url("admin/set_deadlines/AdminController/users"); ?>"><button class="btn btn-secondary my-2 my-sm-0" type="button">All Users</button></a>
      <?php }?>
      <form class="form-inline my-2 my-lg-0">
        <a href="<?= base_url("select_website/".$switch_website); ?>"><button class="btn btn-secondary my-2 my-sm-0" type="button">Switch to Juryforms</button></a>
      </form>
        <a href="<?= base_url("set_deadlines/settings"); ?>"><button class="btn btn-secondary my-2 my-sm-0" type="button">Settings</button></a>
      </form>
      <form class="form-inline my-2 my-lg-0">
        <a href="<?= base_url("userLogout"); ?>"><button class="btn btn-secondary my-2 my-sm-0" type="button">Logout</button></a>
      </form>
  </div>
</nav>
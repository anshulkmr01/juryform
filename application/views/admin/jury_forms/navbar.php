<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="<?= base_url("admin/jury_forms"); ?>">Admin Panel</a>
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
      <li class="nav-item">
        <?= anchor('admin/jury_forms/create_new_category','Create New Category',array('class' => 'nav-link'));?>
      </li>
      <li class="nav-item parent-menu">
        <a class="nav-link" href="<?= base_url("admin/jury_forms/categories"); ?>">Categories</a>
        <?php if($categoryData = $this->session->userdata('categoryData_')):?>
        <ul class="sub-menu">
          <?php foreach($categoryData as $categories):?>
            <a href="<?= base_url('admin/jury_forms/HomeController/documents')?>?categoryId=<?=$categories->CategoryId ?>&categoryName=<?=$categories->Categoryname?>" class=""><li><?= $categories->Categoryname ?></li></a>
         
        <?php endforeach;?>
        </ul>
      <?php endif; ?>
      </li>
      <li class="nav-item">
        <?= anchor('admin/jury_forms/dynamic_fields','Dynamic Fields',array('class' => 'nav-link'));?>
      </li>
      <li class="nav-item">
        <!--a class="nav-link" href="#">About</a-->
      </li>
      </ul>
      <form class="form-inline my-2 my-lg-0">
        <a href="<?= base_url("select_website/jury_forms"); ?>"><button class="btn btn-secondary my-2 my-sm-0" type="button">User Dashboard</button></a>
      </form>
      <form class="form-inline my-2 my-lg-0">
        <a href="<?= base_url("admin/select_website/".$switch_website); ?>"><button class="btn btn-secondary my-2 my-sm-0" type="button">Switch Website</button></a>
      </form>
      <form class="form-inline my-2 my-lg-0">
        <a href="<?= base_url("admin/jury_forms/settings"); ?>"><button class="btn btn-secondary my-2 my-sm-0" type="button">Settings</button></a>
      </form>
      <form class="form-inline my-2 my-lg-0">
        <a href="<?= base_url("admin/logout"); ?>"><button class="btn btn-secondary my-2 my-sm-0" type="button">Logout</button></a>
      </form>
  </div>
</nav>

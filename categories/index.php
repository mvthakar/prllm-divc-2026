<?php require_once '../@/lib/init.php'; ?>
<?php
require_once pathOf('/@/lib/database.php');

$db = Database::getInstance();
$categories = $db->getAll("SELECT `id`, `name` FROM `categories` WHERE `deleted_at` IS NULL");

?>
<?php require_once pathOf('/@/includes/header.php'); ?>

<div class="content-wrapper">
  <div class="container">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row">&nbsp;</div>
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Categories</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Categories</li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <section class="content">
      <div class="row">
        <div class="col text-right">
          <a role="button" class="btn btn-success" href="<?= urlOf('/categories/add.php') ?>">Add New</a>
        </div>
      </div>
      <div class="row">&nbsp;</div>
      <div class="card card-outline card-info">
        <div class="card-body">
          <div class="col col-md-12">
            <div class="row">
              <div class="col col-lg-4 offset-lg-8 text-right mb-4 col-12">
                <form action="" method="get" class="input-group">
                  <input type="search" class="form-control" name="search" placeholder="Search..." autofocus value="<?= isset($search_term) ? $search_term : "" ?>">
                  <div class="input-group-append">
                    <button type="submit" class="btn btn-info">
                      <i class="fa fa-search"></i>
                    </button>
                  </div>
                </form>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <table class="table table-striped item-list">
                  <thead>
                    <tr>
                      <th scope="col" class="item-list-title">Name</th>
                      <th scope="col" class="item-list-actions" style="width: 20%">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($categories as $category): ?>
                    <tr>
                      <td class="item-list-name"><?= $category->name ?></td>
                      <td class="item-list-actions">
                        <div class="btn-group" role="group" aria-label="Actions">
                          <a role="button" class="btn btn-warning" href="<?= urlOf('/categories/edit.php?id=' . $category->id) ?>" data-toggle="tooltip" data-placement="bottom" title="Edit">
                            <i class="far fa-edit"></i>
                          </a>
                          <a role="button" class="btn btn-danger" href="<?= urlOf('/categories/delete.php?id=' . $category->id) ?>" data-toggle="tooltip" data-placement="bottom" title="Delete">
                            <i class="far fa-trash-alt"></i>
                          </a>
                        </div>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <br>
    </section>
  </div>
</div>

<?php require_once pathOf('/@/includes/scripts.php'); ?>
<?php require_once pathOf('/@/includes/footer.php'); ?>
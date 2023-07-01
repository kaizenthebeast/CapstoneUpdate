<?php
    include ("../home/header.php");
?>


<!-- SUBMIT FORM -->

<div class="content py-4">
  <div class="card card-outline card-primary shadow rounded-0">
    <div class="card-header rounded-0">
      <h5 class="card-title"><?= isset($ID) ? "Update Archive-$archive_code} Details" : "Submit Thesis   " ?></h5>
    </div>
    <div class="card-body rounded-0">
      <div class="container-fluid">
      <form action="../submit/submit_thesis2copy.php" id="archive-form" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="ID" value="<?= isset($ID) ? $ID : "" ?>">
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="title" class="control-label text-navy">Thesis Title</label>
                <input type="text" name="title" id="title" autofocus placeholder="Project Title" class="form-control form-control-border" value="<?= isset($title) ?$title : "" ?>" required>
                <div class="invalid-feedback">Please enter a valid thesis title.</div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label for="year" class="control-label text-navy">Year</label>
                <select name="year" id="year" class="form-control form-control-border" required>
                  <?php
                    for($i = 0; $i < 51; $i++):
                  ?>
                  <option <?= isset($year) && $year == date("Y",strtotime(date("Y")." -{$i} years")) ? "selected" : "" ?>><?= date("Y",strtotime(date("Y")." -{$i} years")) ?></option>
                  <?php endfor; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group">
                <label for="abstract" class="control-label text-navy">Abstract</label>
                <textarea rows="3" name="abstract" id="abstract" placeholder="Abstract" class="form-control form-control-border summernote" required><?= isset($abstract) ? html_entity_decode($abstract) : "" ?></textarea>
                <div class="invalid-feedback">Please provide a valid abstract.</div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group">
                <label for="members" class="control-label text-navy">Thesis Members</label>
                <textarea rows="3" name="members" id="members" placeholder="Members" class="form-control form-control-border summernote-list-only" required><?= isset($members) ? html_entity_decode($members) : "" ?></textarea>
                <div class="invalid-feedback">Please enter valid thesis members.</div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group">
                <label for="email" class="control-label text-navy">Email</label>
                <input type="email" name="email" id="email" placeholder="email@example.com" class="form-control form-control-border" required>
                <div class="invalid-feedback">Please enter a valid email address.</div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group">
                <label for="tags" class="control-label text-navy">Tags</label>
                <input type="text" name="tags" id="tags" placeholder="Enter tags" class="form-control form-control-border">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group">
                <label for="pdf" class="control-label text-muted">Thesis Document (PDF File Only)</label>
                <input type="file" id="pdf" name="pdf_path" class="form-control form-control-border" accept="application/pdf" <?= !isset($id) ? "required" : "" ?>>
                <div class="invalid-feedback">Please upload a PDF file.</div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="form-group text-center">
                <p>By clicking the submit button, you agree to allow us to use your data for the intended purpose of this system.</p>
                <button class="btn btn-default bg-navy btn-flat">Submit</button>
                <a href="./?page=profile" class="btn btn-light border btn-flat">Cancel</a>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  function displayImg(input, target) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function(e) {
        target.attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
    }
  }
</script>

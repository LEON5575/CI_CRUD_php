<form action="<?php echo site_url('Validation/file_upload')?>"
method="post" enctype="multipart/form-data"
>
<div class="d-flex ms-auto">
          <input type="file" name="fileToUpload" id="fileToUpload" class="form-control" required>
          <button class="btn btn-outline-light" type="submit">Upload</button>
          <button class="btn btn-outline-info" type="submit">Download</button>
      </div>
</form>
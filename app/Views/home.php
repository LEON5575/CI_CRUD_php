
<script>
    $(document).on('click', '.edit', function(e) {
        e.preventDefault();
        // Assuming the first sibling is an input element
        var id = $(this).parent().siblings()[0].value; // Adjust selector as needed

        $.ajax({
            url: "<?php echo base_url(); ?>"+"/getUser/"+id, // Correctly concatenate the id
            method: "GET",
            success: function(result) {
               var r = JSON.parse(result);
			   $(".updateUsername").val(r.name);
			   $(".updateEmail").val(r.email);
			   $(".updateNumber").val(r.mobile);
			   $(".updateId").val(r.id);
            },
        });
    });




	// !delete user
	// $(document).on('click','.delete',function(e){
	// 	e.preventDefault();
	// 	var id = $(this).parent().siblings()[0].value;
	// 	$.ajax({
    //         url: "<?php echo base_url(); ?>"+"/deleteUser",
    //         method: "POST",
	// 		data: {id:id},
	// 		success: function(result){
	// 			console.log(result);
    //                   if(result.includes("1")){
	// 					window.location.href = window.location.href;
	// 				  }
	// 		}
	// })
	// })
	$(document).on('click', '.delete', function(e) {
    e.preventDefault();
    var confirmation = confirm("Are you sure!!!");
    if (!confirmation) {
        return;
    }
    var id = $(this).parent().siblings()[0].value;
    $.ajax({
        url: "<?php echo base_url(); ?>"+"/deleteUser ",
        method: "POST",
        data: {id: id},
        success: function(result) {
            console.log(result);
            if (result.includes("1")) {
                window.location.href = window.location.href;
            }
        }
    });
});
//!deleteAll User
$(document).on('click', '.delete_all_data', function() {
    var checkboxes = $(".data_checkbox:checked");
    if (checkboxes.length > 0) {
        var confirmation = confirm("Are you sure You Want to delete this data?");
        if (!confirmation) {
            return;
        }
        var ids = [];
        checkboxes.each(function() {
            ids.push($(this).val());
        });
        $.ajax({
            url: "<?php echo base_url().'/deleteAll'?>",
            method: "POST",
            data: { ids: ids },
            success: function(result) {
                console.log(result);
                checkboxes.each(function() {
                    $(this).parent().parent().parent().hide(1000);
                });
            }
        });
    } else {
        alert("Please select at least one user to delete.");
    }
});

//!filter
// Filter users
$('body').on('shown.bs.modal', '.modal', function() {
  $(this).find('select').each(function() {
    var dropdownParent = $(document.body);
    if ($(this).parents('.modal.in:first').length !== 0)
      dropdownParent = $(this).parents('.modal.in:first');
    $(this).select2({
      dropdownParent: dropdownParent
      // ...
    });
  });
});

// Filter users
$(document).on('click', '#filterBtn', function(e) {
  e.preventDefault();
  var filterType1 = $('select[name="filterType1"]').val();
  var searchTerm1 = $('input[name="searchTerm1"]').val();
  var filterType2 = $('select[name="filterType2"]').val();
  var searchTerm2 = $('input[name="searchTerm2"]').val();

  $.ajax({
    url: "<?php echo site_url('filterUser')?>",
    method: "POST",
    data: {
      filterType1: filterType1,
      searchTerm1: searchTerm1,
      filterType2: filterType2,
      searchTerm2: searchTerm2
    },
    success: function(result) {
      console.log(result);
      $('#myTable tbody').html(result);
    }
  });
});
</script>
<!-- //!DOWNLOAD AND UPLOAD BUTTON -->
<div style="margin-top: 0.8rem;" >
    <form action="<?php echo base_url('upload') ?>" method="post" enctype="multipart/form-data" id="upload_file">
    <span style="color: whitesmoke;">Upload File Here</span>
    <input type="file" name="upload_file" required id="upload_file">
    <button type="submit" name="file_submit" id="file_submit" style="margin-right: 23rem;">Submit</button>
</form>
    </div >
	<div style="margin-left: 76.5rem; margin-top: -2rem;">
    <a href="<?php echo site_url('spreadsheet')?>" class="btn btn-warning" role="button">Download CSV File</a>
    </div>


<div class="container-xl">
	<div class="table-responsive d-flex flex-column">

	<?php
if(session()->getFlashdata('success')){
?>
	<div class="alert w-50 align-self-center alert-success alert-dismissible fade show" role="alert">
		<?php echo session()->getFlashdata('success')?>
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>
	</div>
	<?php } ?>
	<div class="table-wrapper">
		<div class="table-title">
			<div class="row">
				<div class="col-sm-6">
					<h2><b>CRUD OPERATION</b></h2>
				</div>
				<div class="col-sm-6">	
<!-- Button to trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Filter</button>
					<a href="#addEmployeeModal" class="btn btn-dark" data-toggle="modal"><i class="material-icons">&#xE147;</i> <span>Add New User</span></a>
					<a href="#" class="delete_all_data btn btn-primary"><i class="material-icons">&#xE15C;</i> <span>Delete</span></a>
				</div>
			</div>
		</div>
		<table class="table table-striped table-hover" id="myTable">
			<thead>
				<tr>
					<th>
						<span class="custom-checkbox">
							<input type="checkbox" id="selectAll">
							<label for="selectAll"></label>
						</span>
					</th>
					<th>Id</th>
					<th>Name</th>
					<th>Email</th>
					<th>Mobile</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
					<?php
				if($users){
					foreach($users as $user){
				?>
				<tr>
                    <input type="hidden" id="userId" name="id" value = "<?php echo $user['id']; ?>" >
					<td>
						<span class="custom-checkbox">
							<input type="checkbox" id="data_checkbox" class="data_checkbox" name="data_checkbox" value="<?php echo $user['id']; ?>">
							<label for="data_checkbox"></label>
						</span>
					</td>
					<td><?php echo $user['id']; ?></td>
					<td><?php echo $user['name']; ?></td>
					<td><?php echo $user['email']; ?></td>
					<td><?php echo $user['mobile']; ?></td>
					<td>
						<a href="#editEmployeeModal" class="edit" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
						<a href="#deleteEmployeeModal" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
					</td>
				</tr>
				<?php
					}
				}
				?>
			</tbody>
		</table>
		<div class="d-flex justify-content-center align-items-center">
<ul class="pagination">
    <?php
    // Output the pagination links
    echo $pager->links('group1', 'bs_pagination');
    ?>
</ul>
 </div>
		</div>
	</div>
</div>
<!-- Add Modal HTML -->
<div id="addEmployeeModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action = "<?php echo base_url().'/saveUser'?>" method = "POST" >
				<div class="modal-header">
					<h4 class="modal-title">Add User Data</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">	
					<!-- //Name				 -->
					<div class="form-group">
						<label>Name</label>
						<input type="text" class="form-control" name="name" required autocomplete="off">
					</div>
					<!-- email -->
					<div class="form-group">
						<label>Email</label>
						<input type="email" class="form-control" name="email" required autocomplete="off">
					</div>
					<!-- mobile -->
					<div class="form-group">
    <label for="mobile">Mobile</label>
    <input type="number" class="form-control" name="mobile" id="mobile" required autocomplete="off" min="1000000000" max="9999999999" oninput="validateMobile(this)">
               <div id="mobileError" style="color: red; display: none;">Please enter a valid 10-digit mobile number.</div>
                  </div>
				</div>
				<div class="modal-footer">
					<input type="button" class="btn btn-default" name="submit" data-dismiss="modal" value="Cancel">
					<input type="submit" class="btn btn-success" value="Add">
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Edit Modal HTML -->
<div id="editEmployeeModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action = "<?php echo base_url().'/updateUser'?>" method = "post">
				<div class="modal-header">
					<h4 class="modal-title">Edit User</h4>
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body">
                    <input type="hidden" name="updateId" class = "updateId" >
					<div class="form-group">
						<label>Name</label>
						<input type="text" class="form-control updateUsername" name = "name" required>
					</div>
					<div class="form-group">
						<label>email</label>
						<input type="email" class="form-control updateEmail" name = "email"  required>
                    </div>			
					<div class="form-group">
						<label>mobile</label>
						<input type="number" class="form-control updateNumber" name = "mobile"  required>
                    </div>	
				</div>
				<div class="modal-footer">
					<input type="button" name = "submit" class="btn btn-default" data-dismiss="modal" value="Cancel">
					<input type="submit" class="btn btn-info" value="Save">
				</div>
			</form>
		</div>
	</div>
</div>

<!-- //?filter modal -->



<!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Filter User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
      </div>
      <form action="<?php echo site_url('filterUser  ')?>" method="post">
        <!-- //id -->
        <div class="modal-body">
        <div class="mb-3">
            <label for="filterType4">Id</label>
            <select name="filterType4" class="form-select" aria-label="Default select example">
              <option selected>Select Id</option>
              <?php if($users) {
                foreach($users as $user) {
              ?>
              <option value="<?php echo $user['id']; ?>"><?php echo $user['id']; ?></option>
              <?php
                }
              }
              ?>
            </select>
          </div>
            <!-- //name -->
          <div class="mb-3">
            <label for="filterType3">Name</label>
            <select name="filterType3" class="form-select" aria-label="Default select example">
              <option selected>Select Name</option>
              <?php if($users) {
                foreach($users as $user) {
              ?>
              <option value="<?php echo $user['name']; ?>"><?php echo $user['name']; ?></option>
              <?php
                }
              }
              ?>
            </select>
          </div>
         <!-- //email -->
          <div class="mb-3">
            <label for="filterType1">Email</label>
            <select name="filterType1" class="form-select" aria-label="Default select example">
              <option selected>Select Email</option>
              <?php if($users) {
                foreach($users as $user) {
              ?>
              <option value="<?php echo $user['email']; ?>"><?php echo $user['email']; ?></option>
              <?php
                }
              }
              ?>
            </select>
          </div>
            <!-- //mobile -->
          <div class="mb-3">
            <label for="filterType2">Mobile</label>
            <select name="filterType2" class="form-select" aria-label="Default select example">
              <option selected>Select Mobile</option>
              <?php if($users) {
                foreach($users as $user) {
              ?>
              <option value="<?php echo $user['mobile']; ?>"><?php echo $user['mobile']; ?></option>
              <?php
                }
              }
              ?>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" id="filterBtn">Search</button>
        </div>
      </form>
    </div>
  </div>
</div>


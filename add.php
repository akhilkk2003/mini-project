<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                    <input type="hidden" name="learner_id" id="editTblId">
                    <label>Learner Name:</label>
                    <input type="text" name="full_name" id="full_name" required><br>
                    <label>DOB:</label>
                    <input type="text" name="dob" id="dob" required><br>
                    <label>Phone Number:</label>
                    <input type="text" name="phone_number" id="num" required><br>
                    <label>Blood Group:</label>
                    <input type="text" name="blood_group" id="nu" required><br>
                    <button type="submit" name="update_details" class="btn btn-success">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
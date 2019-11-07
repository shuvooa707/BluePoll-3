<!-- login modal -->
<div class="login-modal-back">
    <div class="login-modal-container jello">
        <div class="login-modal-header">
            Login <span class="close-login-container" onclick="closeLogin(this)">X</span>
        </div>
        <div class="login-modal-body">
            <label for="username">User Name : </label>
            <input type="text" name="username" id="username" placeholder="User name">
            <label for="username">Password : </label>
            <input type="password" name="password" id="password" placeholder="Password">
            <button class="signin-button" onclick='login(this.parentElement)'>Sign In</button>
            <a href="signup.php"><button class="signup-button">Sign Up</button></a>
        </div>
    </div>
</div>

<!-- deletion confirmation modal -->
<div class="delete-con-modal-back">
    <div class="delete-con-modal-container jello">
        <div class="delete-con-modal-header">
            <h3>Are You Sure ?</h3>
            <span class="close-delete-con-container" onclick="this.parentElement.parentElement.parentElement.style.display='none'">X</span>
        </div>
        <div class="delete-con-modal-body">
            <button class="yes-delete" onclick='okDeletePoll(this.parentElement)'>Delete</button>
            <button class="cancel" onclick="this.parentElement.parentElement.parentElement.style.display='none'">Cancel</button>
        </div>
    </div>
</div>


<!-- deletion confirmation modal -->
<div class="delete-comment-modal-back">
    <div class="delete-comment-modal-container jello">
        <div class="delete-comment-modal-header">
            <h3>Are You Sure ?</h3>
            <span class="close-delete-comment-container" onclick="this.parentElement.parentElement.parentElement.classList.remove('show')">X</span>
        </div>
        <div class="delete-comment-modal-body">
            <button class="yes-delete" onclick='okDeleteComment()'>Delete</button>
            <button class="cancel" onclick="this.parentElement.parentElement.parentElement.classList.remove('show')">Cancel</button>
        </div>
    </div>
</div>


<!-- poll edit warning modal -->
<div class="edit-warning-modal-back">
    <div class="edit-warning-modal-container jello">
        <div class="edit-warning-modal-header">
            <h3>Warning ⚠ </h3>
            <span style='font-size:14px;display:block;padding:10px;background:white;color:red;'>If you edit the poll it will remove all the votes likes and comments</span>
            <span class="close-edit-warning-container" onclick="this.parentElement.parentElement.parentElement.classList.remove('show')">X</span>
        </div>
        <div class="edit-warning-modal-body">
            <button class="yes-delete" onclick='okEditPoll()'>Edit</button>
            <button class="cancel" onclick="this.parentElement.parentElement.parentElement.classList.remove('show')">Cancel</button>
        </div>
    </div>
</div>
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
            <h3>Are You Sure ? <strong style='font-size:30px;background: #434343;padding: 0px 7px;color: #FF9800;border-radius:4px;font-family: fantasy;'>☢</strong> </h3>
            <span class="close-delete-con-container" onclick="this.parentElement.parentElement.parentElement.classList.remove('show')">✘</span>
        </div>
        <div class="delete-con-modal-body">
            <button class="yes-delete" onclick='okDeletePoll(this.parentElement)'>Delete</button>
            <button class="cancel" onclick="this.parentElement.parentElement.parentElement.classList.remove('show')">Cancel</button>
        </div>
    </div>
</div>


<!-- deletion confirmation modal -->
<div class="delete-comment-modal-back">
    <div class="delete-comment-modal-container jello">
        <div class="delete-comment-modal-header">
            <h3>Are You Sure ? <strong style='font-size:30px;background: #434343;padding: 0px 7px;color: #FF9800;border-radius:4px;font-family: fantasy;'>☢</strong> </h3>
            <span class="close-delete-comment-container" onclick="this.parentElement.parentElement.parentElement.classList.remove('show')">✖</span>
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
            <h3 style="
                        letter-spacing: 2px;
                        font-family: ubuntu bold;
                        font-size: 24px;
                    ">Warning ⚠ </h3>
            <span style='font-size: 17px;display:block;padding:10px;background:white;color: black;border-radius: 5px;'>If you edit the poll it will remove all the votes likes and comments</span>
            <span title="close" class="close-edit-warning-container" onclick="this.parentElement.parentElement.parentElement.classList.remove('show')">✖</span>
        </div>
        <div class="edit-warning-modal-body">
            <button class="yes-delete" onclick='okEditPoll()'>Edit</button>
            <button class="cancel" onclick="this.parentElement.parentElement.parentElement.classList.remove('show')">Cancel</button>
        </div>
    </div>
</div>
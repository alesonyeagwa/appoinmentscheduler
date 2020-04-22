
<div class="row mt-5">
    <div class="col-md-12 d-flex justify-content-end">
        <?php if($user->active == 1 && can('block-user')): ?>
            <a href="<?= base_url('user/block/' . $userID) ?>"><button class="btn btn-danger btn-a">Block user</button></a>
        <?php elseif ($user->active == 0 && can('unblock-user')): ?>
            <a href="<?= base_url('user/unblock/' . $userID) ?>"><button class="btn btn-success btn-a">Unblock user</button></a>
        <?php endif; ?>
    </div>
</div>
<div class="row mt-5">
    <div class="col-md-auto col-sm-12 d-flex justify-content-center">
        <?php
            $style = '';
            if($user->photo){
                $style = 'style="background-image:url(\''. base_url($user->photo) .'\')"';
            }else{
                $style = 'style="background-image:url(\''. base_url('assets/img/user.png') .'\')"';
            }
        ?>
        <div class="profile-image" <?= $style ?>>

        </div>
    </div>
    <div class="col">
        <table class="info-table">
            <tbody>
                <tr>
                    <td>Name: </td>
                    <td><?= $user->name ?></td>
                </tr>
                <tr>
                    <td>Email: </td>
                    <td><?= $user->email ?></td>
                </tr>
                <tr>
                    <td>Phone: </td>
                    <td><?= $user->phone ?></td>
                </tr>
                <tr>
                    <td>Joined: </td>
                    <td><?= $user->created ?></td>
                </tr>
                <tr>
                    <td>Status: </td>
                    <td><?= process_status($user->active) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
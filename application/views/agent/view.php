
<div class="row mt-5">
    <div class="col-md-12 d-flex justify-content-end">
        <?php if($agent->active == 1 && can('block-agent')): ?>
            <a href="<?= base_url('agent/block/' . $agentID) ?>"><button class="btn btn-danger btn-a">Block Agent</button></a>
        <?php elseif ($agent->active == 0 && can('unblock-agent')): ?>
            <a href="<?= base_url('agent/unblock/' . $agentID) ?>"><button class="btn btn-success btn-a">Unblock Agent</button></a>
        <?php endif; ?>
    </div>
</div>
<div class="row mt-5">
    <div class="col-md-auto col-sm-12 d-flex justify-content-center">
        <?php
            $style = '';
            if($agent->photo){
                $style = 'style="background-image:url(\''. base_url($agent->photo) .'\')"';
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
                    <td><?= $agent->agentName ?></td>
                </tr>
                <tr>
                    <td>Email: </td>
                    <td><?= $agent->email ?></td>
                </tr>
                <tr>
                    <td>Phone: </td>
                    <td><?= $agent->phone ?></td>
                </tr>
                <tr>
                    <td>About: </td>
                    <td><?= $agent->description ?></td>
                </tr>
                <tr>
                    <td>Joined: </td>
                    <td><?= $agent->created_at ?></td>
                </tr>
                <tr>
                    <td>Status: </td>
                    <td><?= process_status($agent->active) ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
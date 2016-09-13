<h1>Manage Timesheets</h1>

<div id="form-container" class="d6-form full-width-form" >
    <div class="close-modal"></div>
    <form name="frm_timesheets" id="frm_timesheets" method="post" class="login-form-wrapper" action="<?=site_url('account/timesheets/addedit')?>">
        <input type="hidden" name="timesheet_id" id="timesheet_id" />
        <fieldset>
            <legend class="title">Add a New Timesheet</legend>
            <? /*<div class="field">
                <label>Client</label>
                <select name="client_id" id="client_id">
                    <? foreach($clients as $client) {?>
                        <option value="<?=$client->client->id?>"><?=$client->client->name?></option>
                    <? }?>
                </select>
            </div>*/?>
            <div class="field">
                <label>Project Name</label>
                <select name="project_id" id="project_id">
                    <? foreach($projects as $p) {?>
                        <option value="<?=$p->project->id?>"><?=$p->project->name?></option>
                    <? }?>
                </select>
            </div>
            <div class="field">
                <label>Task</label>
                <select class="js-tasks" name="task_id" id="task_id">
                    <optgroup label="Billable">
                        <option value="6267660">Graphic Design</option>
                        <option value="6267662">Marketing</option>
                        <option value="6267661" selected="">Programming</option>
                        <option value="6267663">Project Management</option>
                    </optgroup>
                    <optgroup label="Non-Billable">
                        <option value="6267664">Business Development</option>
                    </optgroup>
                </select>
            </div>
            <div class="field">
                <label>Duration</label>
                <input name="duration" id="duration" value="" placeholder="0:00" type="text" />
            </div>
            <div class="field">
                <label>Entry Date (Spent at)</label>
                <input name="spent_at" id="spent_at" value="" placeholder="<?=date("Y-m-d")?>" type="text" />
            </div>
            <div class="field">
                <label>Notes</label>
                <textarea name="notes" id="notes" placeholder="Notes"></textarea>
            </div>
            <div class="clea"></div>
        </fieldset>
        <button type="button" class="button button-primary" name="btn_save" id="btn_save">Submit</button>
        <button type="button" class="button button-cancel" name="btn_cancel" id="btn_cancel">Cancel</button>
    </form>
</div>
<button type="button" class="button button-secondary open-modal" name="btn_add" id="btn_add"><i class="fa fa-plus-square-o" aria-hidden="true"></i> Add New</button>

<table id="timesheets_dt" class="display dataTable" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th></th>
            <th>Project</th>
            <th>Duration (Hours)</th>
            <th>Task</th>
            <th>Spent at</th>
            <th>Timer Started at</th>
            <th>Last Updated</th>
        </tr>
    </thead>
    <tbody>

       <?php foreach($timesheets->day_entries as $t){ ?>
            <tr>
                <td align="center" style="width: 100px; height: 50px;">
                    <a title="Edit" class="edit-record button-action" data-id="<?=$t->id?>" href="javascript:">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <a title="Delete" class="delete-record button-action" data-id="<?=$t->id?>" href="javascript:">
                        <i class="fa fa-trash"></i>
                    </a>
                    <div class="clear"></div>
                </td>
                <td><?=$t->project?></td>
                <td><?=$t->hours?></td>
                <td><?=$t->task?></td>
                <td><?=$t->spent_at?></td>
                <td align="center">
                    <a title="Start Timer" class="timer-record button-action" data-id="<?=$t->id?>" href="javascript:">
                        <i class="fa fa-clock-o"></i>
                        <span class="timer-value"><?=$t->timer_started_at?$t->timer_started_at:"Start Timer"?></span>
                    </a>
                </td>
                <td><?=$t->updated_at?></td>
            </tr>
        <? }?>
    </tbody>
</table>
<!--- Third party libraries -->
<link rel="stylesheet" type="text/css" href="<?=base_url()?>static/css/jquery.dataTables.min.css" />
<link rel="stylesheet" type="text/css" href="<?=base_url()?>static/css/dcalendar.picker.css" />
<script type="text/javascript" src="<?=base_url()?>static/js/jquery.form.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>static/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?=base_url()?>static/js/timesheets.js"></script>
<script type="text/javascript" src="<?=base_url()?>static/js/dcalendar.picker.js"></script>
